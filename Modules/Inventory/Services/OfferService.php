<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\ClearanceOfferRange;
use Modules\Inventory\Models\DiscountSalesProduct;
use Modules\Inventory\Models\GiftOfferProduct;
use Modules\Inventory\Models\GiftSalesProduct;
use Modules\Inventory\Models\Offer;
use Modules\Inventory\Models\OfferDetail;
use Modules\Inventory\Models\OfferDiscount;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\ProductCatalog;

class OfferService
{
    
    public function getAll(int $limit = 20) {
        return Offer::query()
        ->likeSearch('title')
        ->paginate($limit);
    }
    
    public function store(array $data, array $product_details=[])
    {

        //transaction start
        DB::beginTransaction();
        $offer = Offer::create($data);
        $result = [];
        if($data['offer_type'] == 'discount'){
            foreach ($product_details['buying_product_id'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                foreach ($product_detail as $i => $product_id) {
                    $discountSalesProduct = DiscountSalesProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'sales_product' => $product_id,
                        'sales_quentity' => $product_details['buying_quantity'][$key][$i],
                    ]);
                    $result['offerDetailsDiscountSales'][] = $discountSalesProduct;
                }

                foreach ($product_details['discount_type'][$key] as $i => $product_id) {
                    $discountOfferProduct = OfferDiscount::create([
                        'offer_detail_id' => $offerDetails->id,
                        'discount_type' => $product_id,
                        'discount_quentity' => $product_details['discount_amount'][$key][$i],
                    ]);
                    $result['offerDetailsDiscountOffer'][] = $discountOfferProduct;
                }
                $result['offerDetails'][] = $offerDetails;
            }
            

        }else if($data['offer_type'] == 'gift'){
            foreach ($product_details['buying_product_id'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                foreach ($product_detail as $i => $product_id) {
                    $giftSalesProduct = GiftSalesProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => $product_details['buying_quantity'][$key][$i],
                    ]);
                    $result['offerDetailsGiftSales'][] = $giftSalesProduct;
                }

                foreach ($product_details['offer_product_id'][$key] as $i => $product_id) {
                    $giftOfferProduct = GiftOfferProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => $product_details['offer_quantity'][$key][$i],
                    ]);
                    $result['offerDetailsGiftOffer'][] = $giftOfferProduct;
                }

                $result['offerDetails'][] = $offerDetails;
            }

        } else if($data['offer_type'] == 'clearance'){
            foreach ($product_details['buying_amount_from'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                // dd($product_details);
                foreach ($product_detail as $i => $buying_amount_from) {
                    $clearanceOfferRange = ClearanceOfferRange::create([
                        'offer_detail_id' => $offerDetails->id,
                        'buying_amount_from'=>$buying_amount_from,
                        'buying_amount_to'=>$product_details['buying_amount_to'][$key][$i],
                        'gift_type' => $product_details['gift_type'][$key][$i], 
                        'gift_amount' => $product_details['gift_amount'][$key][$i],
                    ]);


                    $result['clearanceOfferRange'][] = $clearanceOfferRange;
                }

                foreach ($product_details['clearance_product_id'][$key]??[] as $i => $product_id) {
                    $giftOfferProduct = GiftOfferProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => 1,
                    ]);
                    $result['offerDetailsGiftOffer'][] = $giftOfferProduct;
                }
                // foreach ($product_details['discount_type'][$key]??[] as $i => $product_id) {
                //     $discountOfferProduct = OfferDiscount::create([
                //         'offer_detail_id' => $offerDetails->id,
                //         'discount_type' => $product_id,
                //         'discount_quentity' => $product_details['discount_amount'][$key][$i],
                //     ]);
                //     $result['offerDetailsDiscountOffer'][] = $discountOfferProduct;
                // }

                $result['offerDetails'][] = $offerDetails;
            }

        }
        $result['offer'] = $offer;
        //transaction end
        // dd($result);
        DB::commit();
        return $result;
    }

    public function update(Offer $offer, array $data, array $product_details=[])
    {
        DB::beginTransaction();

        $offer->update($data);
        
        // Delete existing relationships
        $offer->offerDetails()->each(function($detail) {
            $detail->discountSalesProducts()->delete();
            $detail->giftSalesProducts()->delete();
            $detail->offerDiscounts()->delete();
            $detail->giftOfferProducts()->delete();
            $detail->clearanceOfferRanges()->delete();
            $detail->delete();
        });

        // Recreate relationships
        $result = [];
        if($data['offer_type'] == 'discount'){
            foreach ($product_details['buying_product_id'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                foreach ($product_detail as $i => $product_id) {
                    DiscountSalesProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'sales_product' => $product_id,
                        'sales_quentity' => $product_details['buying_quantity'][$key][$i],
                    ]);
                }
                foreach ($product_details['discount_type'][$key] as $i => $discount_type) {
                    OfferDiscount::create([
                        'offer_detail_id' => $offerDetails->id,
                        'discount_type' => $discount_type,
                        'discount_quentity' => $product_details['discount_amount'][$key][$i],
                    ]);
                }
            }
        } else if($data['offer_type'] == 'gift') {
            foreach ($product_details['buying_product_id'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                foreach ($product_detail as $i => $product_id) {
                    GiftSalesProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => $product_details['buying_quantity'][$key][$i],
                    ]);
                }
                foreach ($product_details['offer_product_id'][$key] as $i => $product_id) {
                    GiftOfferProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => $product_details['offer_quantity'][$key][$i],
                    ]);
                }
            }
        } else if($data['offer_type'] == 'clearance'){
            foreach ($product_details['buying_amount_from'] as $key => $product_detail) {
                $offerDetails = OfferDetail::create([
                    'offer_id' => $offer->id
                ]);
                foreach ($product_detail as $i => $buying_amount_from) {
                    // dd($product_details);
                    $clearanceOfferRange = ClearanceOfferRange::create([
                        'offer_detail_id' => $offerDetails->id,
                        'buying_amount_from'=>$buying_amount_from,
                        'buying_amount_to'=>$product_details['buying_amount_to'][$key][$i],

                        'gift_type' => $product_details['gift_type'][$key][$i],
                        'gift_amount' => $product_details['gift_amount'][$key][$i],
                    ]);


                    $result['clearanceOfferRange'][] = $clearanceOfferRange;
                }

                foreach ($product_details['clearance_product_id'][$key]??[] as $i => $product_id) {
                    $giftOfferProduct = GiftOfferProduct::create([
                        'offer_detail_id' => $offerDetails->id,
                        'product_id' => $product_id,
                        'quantity' => 1,
                    ]);
                    $result['offerDetailsGiftOffer'][] = $giftOfferProduct;
                }

                $result['offerDetails'][] = $offerDetails;
            }

        }

        DB::commit();
        return $offer;
    }

    public function delete(Offer $offer)
    {
        $offer->delete();
    }

    public function show($id)
    {
        return Offer::with(["offerDetails.giftSalesProducts.product","offerDetails.giftOfferProducts.product","offerDetails.discountSalesProducts","offerDetails.offerDiscounts", "offerDetails.clearanceOfferRanges"])->findOrFail($id);
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . \Illuminate\Support\Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);
// dd($jsonData);
        return $this->handleDirectImport($jsonData);
    }

    /**
     * Handle direct data import from API request
     */
    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        $savedCount = 0;
        $errors = [];

        DB::beginTransaction();
        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                // Assuming a mapJson method will be created in OfferService to map the data
                // For now, we'll assume the item structure matches what store() expects.
                // You might need to create a mapJson method similar to other services.
                $mappedData = $this->mapJson($item);
                // dd($mappedData);
                $this->store($mappedData['data'], $mappedData['offerDetails']);
                
                // Since there is no mapJson, we'll assume the structure is correct for now.
                // This might need adjustment based on your JSON structure.
                // $this->store($item['data'], $item['product_details']);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        // dd($errors);
        DB::commit();

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
        ], empty($errors) ? 200 : 207); // 207 Multi-Status if partial success
    }

    public function mapJson(array $jsonData): array
    {
        // === Main Offer Data ===
        $data = [
            'title' => $jsonData['title'],
            'applied_date' => $jsonData['applied_date'],
            'stop_date' => $jsonData['stop_date'],
            'times' => $jsonData['times'] ?? null,
            'offer_type' => $jsonData['offer_type'],
            'invoice_type' => $jsonData['invoice_type'] ?? null,
            'rule_status' => $jsonData['rule_status'] ?? null,
            'rule_type' => $jsonData['rule_type'] ?? null,
        ];

        // === Offer Details ===
        $offerDetails = [];

        switch ($jsonData['offer_type']) {
            case 'discount':
                $offerDetails = $this->mapDiscountOffer($jsonData['discount_rules'] ?? []);
                break;

            case 'gift':
                // dd( $jsonData['gift_rules']);
                $offerDetails = $this->mapGiftOffer($jsonData['gift_rules'] ?? []);
                // dd($offerDetails);
                break;

            case 'clearance':
                $offerDetails = $this->mapClearanceOffer($jsonData['clearance_rules'] ?? []);
                break;

            default:
                throw new \Exception("Unsupported offer type: {$jsonData['offer_type']}");
        }

        return compact('data', 'offerDetails');
    }

    private function mapDiscountOffer(array $rules): array
    {
        $result = [
            'buying_product_id' => [],
            'buying_quantity' => [],
            'discount_type' => [],
            'discount_amount' => [],
        ];

        foreach ($rules as $rule) {
            // Map buying products
            $buyingProductIds = [];
            foreach ($rule['buying_products'] as $product) {
                $productId = ProductCatalog::where('name', $product['product_name'])
                    ->where('model', $product['model'])
                    ->value('id');
                if (!$productId) {
                    throw new \Exception("Product not found: '{$product['product_name']}' (Model: {$product['model']})");
                }
                $buyingProductIds[] = $productId;
            }
            $result['buying_product_id'][] = $buyingProductIds;
            $result['buying_quantity'][] = $rule['buying_quantities'];

            // Map discounts
            $result['discount_type'][] = $rule['discount_types'];
            $result['discount_amount'][] = $rule['discount_amounts'];
        }

        return $result;
    }

    private function mapGiftOffer(array $rules): array
    {
        $result = [
            'buying_product_id' => [],
            'buying_quantity' => [],
            'offer_product_id' => [],
            'offer_quantity' => [],
        ];

        foreach ($rules as $rule) {
            // Map buying products
            $buyingProductIds = [];
            foreach ($rule['buying_products'] as $product) {
                $productId = ProductCatalog::where('name', $product['product_name'])
                    ->where('model', $product['model'])
                    ->value('id');
                if (!$productId) {
                    throw new \Exception("Product not found: '{$product['product_name']}' (Model: {$product['model']})");
                }
                $buyingProductIds[] = $productId;
            }
            $result['buying_product_id'][] = $buyingProductIds;
            $result['buying_quantity'][] = $rule['buying_quantities'];

            // Map offer products
            $offerProductIds = [];
            foreach ($rule['offer_products'] as $product) {
                $productId = ProductCatalog::where('name', $product['product_name'])
                    ->where('model', $product['model'])
                    ->value('id');
                if (!$productId) {
                    throw new \Exception("Product not found: '{$product['product_name']}' (Model: {$product['model']})");
                }
                $offerProductIds[] = $productId;
            }
            $result['offer_product_id'][] = $offerProductIds;
            $result['offer_quantity'][] = $rule['offer_quantities'];
        }

        return $result;
    }

    private function mapClearanceOffer(array $rules): array
    {
        $result = [
            'buying_amount_from' => [],
            'buying_amount_to' => [],
            'gift_type' => [],
            'gift_amount' => [],
            'clearance_product_id' => [],
        ];

        foreach ($rules as $rule) {
            // Map amount ranges
            $result['buying_amount_from'][] = $rule['buying_amount_from'];
            $result['buying_amount_to'][] = $rule['buying_amount_to'];
            $result['gift_type'][] = $rule['gift_types'] ?? [];
            $result['gift_amount'][] = $rule['gift_amounts'] ?? [];

            // Map clearance products (optional)
            if (!empty($rule['clearance_products'])) {
                $productIds = [];
                foreach ($rule['clearance_products'] as $product) {
                    $productId = ProductCatalog::where('name', $product['product_name'])
                        ->where('model', $product['model'])
                        ->value('id');
                    if (!$productId) {
                        throw new \Exception("Product not found: '{$product['product_name']}' (Model: {$product['model']})");
                    }
                    $productIds[] = $productId;
                }
                $result['clearance_product_id'][] = $productIds;
            } else {
                $result['clearance_product_id'][] = [];
            }
        }

        return $result;
    }
}