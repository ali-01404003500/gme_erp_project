<?php

namespace Modules\Sales\Services;

use Modules\Sales\Models\Quotation;
use Modules\Sales\Models\QuotationDetail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\CRM\Models\Customer\Settings\CustomerType;

class QuotationService
{
    
    public function getAll(int $limit = 20) {
        return Quotation::query()
        ->likeSearch('quotation_no')
        ->when(request()->filled('from'), function ($qr) {
            $qr->where('date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->where('date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
        })
        ->likeSearch('customer_name')
        ->likeSearch('phone')
        ->paginate($limit);
    }

    public function getQuotationNumber()
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;
        $quotationToday = Quotation::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();
        
        $quotationNumber = sprintf(
            'QUO-%s-USR-%06d-%03d',
            date('Ymd'),
            $authUser,
            $quotationToday + 1
        );
        return $quotationNumber;
    }
    
    public function store(array $data, array $quotationDetails, array $quotationTerms)
    {
        // dd($data, $quotationDetails, $quotationTerms);
        if (!isset($data['quotation_no'])) {
            $data['quotation_no'] = $this->getQuotationNumber();
        }

        $result['quotation'] = Quotation::create($data);

        $result['quotationTerms'] = $result['quotation']->quotationTerms()->create($quotationTerms);

        $result['quotationDetails'] = [];
        foreach($quotationDetails['product_ids'] as $key => $productId) {
            $result['quotationDetails'][] = $result['quotation']->quotationDetails()->create([
                'product_id' => $productId,
                'quantity'=> $quotationDetails['quantity'][$key],
                'price'=> $quotationDetails['price'][$key],
                'unit_discount'=> $quotationDetails['unit_discount'][$key],
                'total_discount'=> $quotationDetails['total_discount'][$key],
                'amount'=> $quotationDetails['amount'][$key],
            ]);
        }
        return $result;
    }

    public function update(Quotation $quotation, array $data, array $quotationDetails, array $quotationTerms)
    {
        $quotation->update($data);
        $result['quotation'] = $quotation->fresh();

        $quotation->quotationTerms()->update($quotationTerms);
        $result['quotationTerms'] = $quotation->quotationTerms;

        $quotation->quotationDetails()->delete();
        $result['quotationDetails'] = [];

        foreach($quotationDetails['product_ids'] as $key => $productId) {
            $result['quotationDetails'][] = $quotation->quotationDetails()->create([
                'product_id' => $productId,
                'quantity' => $quotationDetails['quantity'][$key],
                'price' => $quotationDetails['price'][$key],
                'unit_discount' => $quotationDetails['unit_discount'][$key],
                'total_discount' => $quotationDetails['total_discount'][$key],
                'amount' => $quotationDetails['amount'][$key],
            ]);
        }

        return $result;
    }

    public function delete(Quotation $quotation)
    {
        $quotation->delete();
    }

    public function show($id)
    {
      $quotation = Quotation::with(['quotationDetails.product.brand.supplier','approvedBy.employee.employementDetail','quotationTerms'])->find($id);
      return $quotation;
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        $customer = null;
        $customerType = null;

        // If customer_name is provided, try to find existing customer and load their details
        if (!empty($jsonData['customer_name'])) {
            $customer = Customer::where('company_name', $jsonData['customer_name'])
                ->with('customerType', 'area')
                ->first();
        }

        // Auto-load customer details if customer exists
        if ($customer) {
            $customerName = $customer->company_name;
            $area = $jsonData['area'] ?? ($customer->area ? $customer->area->area : null);
            $address = $jsonData['address'] ?? $customer->address;
            $phone = $jsonData['phone'] ?? $customer->phone;
            $customerTypeId = $jsonData['customer_type'] ?? $customer->customer_type;
        } else {
            // If customer doesn't exist, use provided data
            $customerName = $jsonData['customer_name'];
            $area = $jsonData['area'] ?? null;
            $address = $jsonData['address'] ?? null;
            $phone = $jsonData['phone'];
            
            // Resolve customer type
            if (isset($jsonData['customer_type_name'])) {
                $customerType = CustomerType::where('name', $jsonData['customer_type_name'])
                    ->orWhere('id', $jsonData['customer_type_name'])
                    ->first();
                if (!$customerType) {
                    throw new \Exception("Customer Type not found: {$jsonData['customer_type_name']}");
                }
                $customerTypeId = $customerType->id;
            } elseif (isset($jsonData['customer_type'])) {
                $customerTypeId = $jsonData['customer_type'];
            } else {
                throw new \Exception("Customer type is required");
            }
        }

        // Prepare main quotation data
        $mainData = [
            'customer_name' => $customerName,
            'area' => $area,
            'address' => $address,
            'phone' => $phone,
            'customer_type' => $customerTypeId,
            'date' => $jsonData['date'] ?? now()->toDateString(),
            'total_amount' => 0, // Will be calculated
            'discount' => 0, // Will be calculated
            'percentage' => $jsonData['percentage'] ?? 0,
            'total' => 0, // Will be calculated
            'net_amount' => 0, // Will be calculated
            'remarks' => $jsonData['remarks'] ?? null,
        ];

        // Prepare quotation details (products)
        $quotationDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
        ];

        $totalAmount = 0;
        $totalDiscount = 0;

        if (isset($jsonData['products']) && is_array($jsonData['products'])) {
            foreach ($jsonData['products'] as $item) {
                $product =null;
                //by product code
                if(isset($item['product_code'])) {
                    $product = ProductCatalog::where('product_code', $item['product_code'])
                    ->first();
                }

                //by product name
                if(isset($item['product_name'])) {
                     ProductCatalog::where('name', $item['product_name'])
                    ->first();
                }
                
                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_name']}");
                }

                // Auto-load product price if not provided
                $price = $item['price'] ?? $product->mrp ?? 0;
                $quantity = $item['quantity'] ?? 1;
                $unitDiscount = $item['unit_discount'] ?? 0;
                
                // Auto-calculate if percentage is provided but unit_discount is not
                if (!isset($item['unit_discount']) && isset($mainData['percentage']) && $mainData['percentage'] > 0) {
                    $unitDiscount = ($price * $mainData['percentage']) / 100;
                }

                $totalDiscountForRow = $quantity * $unitDiscount;
                $amount = ($quantity * $price) - $totalDiscountForRow;

                $quotationDetails['product_ids'][] = $product->id;
                $quotationDetails['quantity'][] = $quantity;
                $quotationDetails['price'][] = $price;
                $quotationDetails['unit_discount'][] = $unitDiscount;
                $quotationDetails['total_discount'][] = $totalDiscountForRow;
                $quotationDetails['amount'][] = $amount;

                $totalAmount += ($quantity * $price);
                $totalDiscount += $totalDiscountForRow;
            }
        }

        // Calculate totals
        $mainData['total_amount'] = $totalAmount;
        $mainData['discount'] = $totalDiscount;
        $mainData['total'] = $totalAmount - $totalDiscount;
        $mainData['net_amount'] = $totalAmount - $totalDiscount;

        // Prepare quotation terms with defaults
        $quotationTerms = [
            'quotation_to' => $jsonData['quotation_to'] ?? 'Director',
            'email' => $jsonData['email'] ?? null,
            'attn' => $jsonData['attn'] ?? null,
            'attn_cell' => $jsonData['attn_cell'] ?? null,
            'payment' => $jsonData['payment'] ?? '100% Advance',
            'payment_method' => $jsonData['payment_method'] ?? 'To be paid by Cheque, Cash or Mobile Banking(bKash). In favor of Global Medical Engineering (BD) Ltd.',
            'tax_vat' => $jsonData['tax_vat'] ?? 'All Prices Excluding TAX & VAT.',
            'installation' => $jsonData['installation'] ?? 'Shall be installed by our Foreign Trained Engineer on prior appointment with your concern person(s) at your recommended site on <strong>OUR COST</strong>.',
            'training' => $jsonData['training'] ?? 'Necessary trainging will be imparted to your designated personnel at site on operation & maintenance of the Equipment on <strong>FREE OF CHARGE</strong>.',
            'warranty' => $jsonData['warranty'] ?? '01 (One) Year standard warranty is offered including servicing, replacement of faulty parts, repair etc. from the date of delivery of Goods.Consumables are not covered under this warranty.Warranty does not cover any Electric Burn & Physical Damaged.',
            'buyers_responsibility' => $jsonData['buyers_responsibility'] ?? 'To use <strong> Air-conditioned dust free room and Stabilized & Noise Free power supply </strong>.',
            'validity' => $jsonData['validity'] ?? '20 Days after submitted quotation.',
            'delivery_info' => $jsonData['delivery_info'] ?? 'All products will be delivered From Ready Stock or Within 60-90 days from the date of order with advance.',
        ];

        return [
            'main_data' => $mainData,
            'quotation_details' => $quotationDetails,
            'quotation_terms' => $quotationTerms,
        ];
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store(
                    $mappedData['main_data'],
                    $mappedData['quotation_details'],
                    $mappedData['quotation_terms']
                );
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Quotations import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Store data from direct API request
     */
    public function storeFromDirectData($data = null)
    {
        if ($data === null) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        $savedCount = 0;
        $errors = [];

        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $this->store(
                    $mappedData['main_data'],
                    $mappedData['quotation_details'],
                    $mappedData['quotation_terms']
                );
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207);
    }
}