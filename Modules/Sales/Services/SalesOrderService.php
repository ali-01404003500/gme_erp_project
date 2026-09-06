<?php

namespace Modules\Sales\Services;

use App\Models\AccessControl\ServiceName;
use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use App\Models\OtpVerification;
use App\Models\SmsInfo;
use App\Services\SmsService;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Account\Controllers\Collections\CollectionController;
use Modules\Account\Models\Account;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Services\AccountTransactionService;
use Modules\Account\Services\Collections\CollectionService;
use Modules\Sales\Models\SalesPayment;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\SalesPaymentBkash;
use Modules\Sales\Models\SalesPaymentCardPayment;
use Modules\Sales\Models\SalesPaymentCash;
use Modules\Sales\Models\SalesPaymentCheque;
use Modules\Sales\Models\SalesPaymentOnlineDeposit;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Models\FreeSalesInvoice;
use Modules\Inventory\Models\Offer;
use Modules\CRM\Models\Customer\Customer;

class SalesOrderService
{
    private $transactionService;
    private $collectionService;
    private $smsService;

    public function __construct(AccountTransactionService $transactionService, CollectionService $collectionService, protected InvoiceShareService $shareService )
    {
        $this->transactionService = $transactionService;
        $this->collectionService = $collectionService; 
        $this->shareService = $shareService;
    }

 


    public function getSalesOrderId($supplier_id)
    {
        $today = date('Y-m-d');

        $customer_count = SalesOrder::whereDate(DB::raw('DATE(created_at)'), $today)->count();

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $SalesOrderToday = SalesOrder::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        $SalesOrderNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-SL-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $SalesOrderToday + 1
        );

        return $SalesOrderNumber;
    }

    public function getAll(int $limit = 20)
    {
        if (!request()->filled('from') && !request()->filled('to')) {
            request()->merge(['from' => Carbon::now()->format('Y-m-d'), 'to' => Carbon::now()->format('Y-m-d')]);
        }
        return SalesOrder::query()
            ->searchByFields(['customer_id',  'sales_type', 'status'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('invoice_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('invoice_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
            ->when(auth()->user()->id != 1, function ($qr) {
                $qr->whereIn('created_by', [auth()->user()->id]);
            })
            ->when(request()->filled('additional_phone'), function ($qr) {
                $qr->where('additional_phone', request('additional_phone'))->whereHas('customer', function ($q) {
                    $q->where('phone', request('additional_phone'));
                });
            })
            ->with(['salesOrderDetails', 'salesOrderDeliveries.salesOrderDeliveryDetails', 'delivery', 'customer', 'createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function store(array $data, array $salesOrderDetails, array $salesOrderShipments, array $payments)
    {
        // Apply offers (including gift products) before creating sales order
        // $this->applyOffers($data, $salesOrderDetails, $payments);
        $this->applyOfferProduct($data, $salesOrderDetails, $payments);


        // dd($payments);
        DB::beginTransaction();
        // Create sales order
        // Only generate a new sales_order_id if one is not already provided in the data
        if (!isset($data['sales_order_id']) || empty($data['sales_order_id'])) {
            $data['sales_order_id'] = $this->getSalesOrderId($data['customer_id']);
        }
        $data['sales_type'] = $data['sales_type'] ?? 'general_sales';
        $data['user_ref_id'] = Customer::find($data['customer_id'])->user_ref_id;
        $result['salesOrder'] = SalesOrder::create($data);
        $result['salesOrderDetails'] = [];
        $cashCollectionAmount = 0;
        $paymentDate = '';

        // Store sales order details
        foreach ($salesOrderDetails['product_ids'] as $key => $productId) {
            $detailData = [
                'product_id' => $productId,
                'quantity' => $salesOrderDetails['quantity'][$key],
                'price' => $salesOrderDetails['price'][$key],
                'unit_discount' => $salesOrderDetails['unit_discount'][$key],
                'total_discount' => $salesOrderDetails['total_discount'][$key],
                'amount' => $salesOrderDetails['amount'][$key],
            ];

            // Add discount_type if it exists in the salesOrderDetails array
            if (isset($salesOrderDetails['discount_type'][$key])) {
                $detailData['discount_type'] = $salesOrderDetails['discount_type'][$key];
            }

            // Add is_offers_product if it exists in the salesOrderDetails array
            if (isset($salesOrderDetails['is_offers_product'][$key])) {
                $detailData['is_offers_product'] = $salesOrderDetails['is_offers_product'][$key];
            } else {
                $detailData['is_offers_product'] = false; // Default value for non-offer products
            }

            $result['salesOrderDetails'][] = $result['salesOrder']->salesOrderDetails()->create($detailData);
        }

        

        // dd($salesOrderShipments);
        // Handle shipment if available
        if (isset($data['is_shipment']) && $data['is_shipment'] == 1) {
            $result['salesOrderShipments'] = $result['salesOrder']->shipment()->create([
                'courier_id' => $salesOrderShipments['courier_id'],
                'area_id' => $salesOrderShipments['area_id']  == 'address' ? null : $salesOrderShipments['area_id'],
                'address' => $salesOrderShipments['address'],
                'contact_person_name' => $salesOrderShipments['contact_person_name'],
                'contact_person_number' => $salesOrderShipments['contact_person_number'],
                'condition' => ($salesOrderShipments['condition'] ?? false) ? true : false,
                'additional_amount' => ($salesOrderShipments['condition'] ?? false) ? $salesOrderShipments['additional_amount'] : null,
                'condition_remarks' => ($salesOrderShipments['condition'] ?? false) ? $salesOrderShipments['condition_remarks'] : null,
            ]);
        }

        // Handle status: approved or pending
        if ($data['status'] == 'approved') {
            // Create Delivery
            $result['delivery'] = Delivery::updateOrCreate([
                'source_id' => $result['salesOrder']->id,
                'source_type' => SalesOrder::class,
            ], [
                'delivery_date' => $data['delivery_date'] ?? $data['invoice_date'],
            ]);
 
            
        } elseif ($data['status'] == 'pending') {
            $result['salesOrder']->update(['status' => 'pending']);
            // Remove delivery if it exists
            $result['salesOrder']->delivery()->delete();
        }

        // Create sales payment record
        $salesPayment = SalesPayment::create([
            'sales_order_id' => $result['salesOrder']->id,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'due_amount' => $data['due_amount'] ?? 0,
            'advance_amount' => $data['advance_amount'] ?? 0,
        ]);

        // Update date values of $salesOrder->otp_verifications
        if (request()->filled('otp_verifications')) {

            foreach (request()->otp_verifications as $otpJson) {
                $otpData = json_decode($otpJson, true);

                $otpData['sourceable_id'] = $result['salesOrder']->id;
                $otpData['sourceable_type'] = SalesOrder::class;

                OtpVerification::updateOrCreate(
                    ['id' => $otpData['id'] ?? null],
                    $otpData
                );
            }
        }


        // Save payments
        foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
            if ($payMode) {
                $result['payments'][] = $result['salesOrder']->payments()->create([
                    'pay_mode' => $payMode,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'remarks' => $payments['payments_remark'][$key] ?? null,
                ]);
                
                /*count cash collection amount*/ 
                if ($payMode == 'Cash') {
                    $cashCollectionAmount = $payments['payments_amount'][$key] ?? 0;  
                    $paymentDate = $payments['payments_date'][$key] ?? null;
                } 
                /*update sales order id in emi*/ 
                if ($payMode == 'EMI') {
                    EMIEntry::where('id', $payments['payments_emi_id'][$key])
                    ->update([
                        'sales_order_id' => $result['salesOrder']->id,   // link sales id
                    ]);
                }
            }
        }

   


        // Process payments
        /*  uncomment later
        foreach ($payments['payment_mode'] as $index => $mode){
            $salesPaymentDetail = $salesPayment->salesPaymentDetails()->create([
                'payment_mode' => $mode,
            ]);
            $this->storePaymentDetails($salesPayment, $salesPaymentDetail, $payments, $index, $mode);
        }*/
        // dd($result);
        // $this->makeTransaction($result['salesOrder']);
        // Commit the transaction
        if ($result['salesOrder']->status == 'approved') {

            $this->makeDummyTransaction($result['salesOrder']);

            // Check if there are payments and create collection
            $collectionData = [
                'payments_total_amount' => $result['salesOrder']->net_amount ?? 0,
                'payments_advance_amount' => $result['salesOrder']->payments()->sum('amount') - $result['salesOrder']->net_amount,
                'collection_type' => "customer",
                'collection_from' => $data['customer_id'] ?? $result['salesOrder']->customer_id,
                'collection_date' => $data['invoice_date']
            ];

            if (count($result['salesOrder']->payments) > 0) {
                $this->collectionService->storeForSales($collectionData, $result['salesOrder']->payments, $result['salesOrder']);
            }


            /**Create:: sms send for sales invoice create */ 

            // $serviceName = ServiceName::where('code', 'S01')->where('status', 1)->first();
            $triggerName = TriggerName::where('code', 'T14')->where('status', 1)->first();
            $sms = SmsTemplate::where('code_name','TEM012')->first(); 
            $smsTemplate = $sms->template_body;

            $customerInfo = Customer::where('id', $result['salesOrder']->customer_id)->first(); 

            $phone =   $customerInfo->contact_for_sms;
            $customerName = $customerInfo->company_name;
            $invoiceAmount = $result['salesOrder']->net_amount; 
            $invoiceLink = $this->generateShareLink($result['salesOrder']->id)['link'];

            $smsdata = [
                'customer_name' =>  $customerName,
                'invoice_amount' => $invoiceAmount,
                'invoice_link' => $invoiceLink
            ];  

            foreach ($smsdata as $key => $value) {
                $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
            } 

            $time = Carbon::parse(now()); 
            $newTime = $time->addMinutes($triggerName->after_send_time);

           if (!empty($phone)) {
                SmsInfo::updateOrCreate(
                    [
                        'sms_reference' => $result['salesOrder']->id,
                        'sms_mem_id' => $result['salesOrder']->customer_id,
                        'sms_status' => 'pending', // condition
                        'trigger_name' => 'T14', 
                    ],
                    [
                        'sms_send_time' => $newTime,
                        'sms_to' => $phone,
                        'sms_text' => $smsTemplate, 
                    ]
                );
            }

            
            
            
    

            
            /*Create:: sms send for cash collection*/ 
            if ($cashCollectionAmount > 0) {

                // $serviceName = ServiceName::where('code', 'collection')->where('status', 1)->first();
                $triggerName = TriggerName::where('code', 'T03')->where('status', 1)->first();
                $sms = SmsTemplate::where('code_name', "TEM003")->first(); 
                $smsTemplate = $sms->template_body;

                $customerInfo = Customer::where('id', $result['salesOrder']->customer_id)->first(); 

                $phone =   $customerInfo->contact_for_sms; 
                $customerName = $customerInfo->company_name; 
                $customerPreBalance = Customer::find($result['salesOrder']->customer_id)->getAccount()->balance;
                $collectionAmount = $cashCollectionAmount; 
                $receivedDate = $paymentDate; 
                $customerBalance =  $customerPreBalance +  $result['salesOrder']->net_amount - $collectionAmount;
                
                $data = [
                    'customer_name' => $customerName,
                    'customer_pre_balance ' => $customerPreBalance,
                    'collection_amount' => $collectionAmount,
                    'received_date' => $receivedDate,
                    'customer_current_balance ' => $customerBalance
                ];   

            
                foreach ($data as $key => $value) {
                    $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
                } 

                $time = Carbon::parse(now()); 
                $newTime = $time->addMinutes($triggerName->after_send_time);

                if (!empty($phone)) {
                    SmsInfo::updateOrCreate(
                        [
                            'sms_reference' => $result['salesOrder']->id,
                            'sms_mem_id' => $result['salesOrder']->customer_id,
                            'sms_status' => 'pending', // condition
                            'trigger_name' => 'T03', 
                            
                        ],
                        [
                            'sms_send_time' => $newTime,
                            'sms_to' => $phone,
                            'sms_text' => $smsTemplate, 
                        ]
                    );
                }

                
                // dd($smsTemplate);  
            }
        }

        $clearance = $this->getClearageDetailsForProducts($result['salesOrder']);
        // dd();
        $clearanceOffers = [];
        foreach ($clearance as $range) {
            $clearanceOffers[] = $range->offerDetails->offer->id;
            // dd($range->offerDetails->offer);
        }

        $result['salesOrder']->offers()->sync($clearanceOffers);
        // dd( $result['salesOrder']->offers);
        DB::commit();
        return $result;
    }

    public function generateShareLink($id)
    {
        $order = SalesOrder::findOrFail($id);

        $share = $this->shareService->createShare($order, $this);

        $link = $this->shareService->getShareUrl($share);

        return [
            'success' => true,
            'link' => $link,
            'expires_at' => $share->expires_at,
        ];
    }

    /**
     * Apply offer logic to the sales order data and details
     */
    protected function applyOffers(array &$data, array &$salesOrderDetails, array &$payments)
    {
        $offers = Offer::with(['offerDetails.giftSalesProducts', 'offerDetails.giftOfferProducts', 'offerDetails.discountSalesProducts', 'offerDetails.offerDiscounts'])
            ->where('rule_status', 'running')
            ->where('applied_date', '<=', Carbon::parse($data['invoice_date']))
            ->where('stop_date', '>=', Carbon::parse($data['invoice_date']))
            ->get();

        $buyingMap = [];
        $tentativeTotal = 0;
        $tentativeDiscount = $data['discount'] ?? 0;
        $additionalDiscount = 0; // Initialize additional discount counter

        // Build buying map and calculate initial totals
        foreach ($salesOrderDetails['product_ids'] as $key => $productId) {
            // dd( $salesOrderDetails);
            $qty = $salesOrderDetails['quantity'][$key];
            $price = $salesOrderDetails['price'][$key];
            $unitDisc = $salesOrderDetails['unit_discount'][$key];
            $totalDisc = $salesOrderDetails['total_discount'][$key];
            $amount = $salesOrderDetails['amount'][$key];

            $buyingMap[$productId] = [
                'quantity' => $qty,
                'price' => $price,
                'unit_discount' => $unitDisc,
                'total_discount' => $totalDisc,
                'amount' => $amount,
                'key' => $key
            ];

            $tentativeTotal += $amount;
        }

        $tentativeNet = $tentativeTotal - $tentativeDiscount + ($data['vat'] ?? 0);
        $nextKey = count($salesOrderDetails['product_ids']); // Track next key for appending free products

        // dd($buyingMap, $offers->whereIn('offer_type', ['gift', 'discount']));
        // dd($offers->whereIn('offer_type', ['gift', 'discount'])->pluck('offerDetails')->flatten()->pluck('discountSalesProducts'));
        // Process product-based offers (gift and discount)
        $matchTest = [];
        foreach ($offers->whereIn('offer_type', ['gift', 'discount']) as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $buyingProducts = $offer->offer_type == 'gift' ? $detail->giftSalesProducts : $detail->discountSalesProducts;

                $match = true;
                $multiplier = PHP_INT_MAX;

                foreach ($buyingProducts as $buy) {
                    // dd($buy);
                    $prod = $buy->sales_product ?? $buy->product_id;
                    $reqQty = $buy->sales_quentity ?? $buy->quantity;

                    // dd($prod, $reqQty, $buyingMap);

                    $matchTest[] = [
                        'offer_details' => $buyingProducts->toArray(),
                        'buy_qty' => $buyingMap,
                        'is_match' => isset($buyingMap[$prod])  && $buyingMap[$prod]['quantity'] == $reqQty
                    ];
                    // For exact matching, the customer must have exactly the required quantity (no multiples allowed)
                    if (!isset($buyingMap[$prod]) || $buyingMap[$prod]['quantity'] != $reqQty) {
                        $match = false;
                        break;
                    }

                    // Since we require exact match, multiplier is always 1 if quantities match
                    $multiplier = 1;
                }

                if ($match) {
                    // dd($match, $multiplier, $buyingProducts);

                    if ($offer->offer_type == 'discount') {
                        $disc = $detail->offerDiscounts->first();
                        $discType = $disc->discount_type;
                        $discAmount = $disc->discount_quentity;

                        $bundleCost = 0;
                        foreach ($buyingProducts as $buy) {
                            $prod = $buy->sales_product;
                            $reqQty = $buy->sales_quentity;
                            $unitPrice = $buyingMap[$prod]['price'];
                            $bundleCost += $unitPrice * $reqQty;
                        }

                        $discountPerBundle = $discType == 'percentage_discount' ? ($bundleCost * $discAmount / 100) : $discAmount;
                        $totalDiscount = $multiplier * $discountPerBundle;

                        // Add to additional discount
                        $additionalDiscount += $totalDiscount;

                        foreach ($buyingProducts as $buy) {
                            $prod = $buy->sales_product;
                            $reqQty = $buy->sales_quentity;
                            $unitPrice = $buyingMap[$prod]['price'];
                            $prodBundleShare = $unitPrice * $reqQty / $bundleCost;

                            $prodDiscount = $totalDiscount * $prodBundleShare;

                            $map = &$buyingMap[$prod];
                            $map['total_discount'] += $prodDiscount;
                            $map['unit_discount'] = $map['total_discount'] / $map['quantity'];
                            $map['amount'] = ($map['price'] * $map['quantity']) - $map['total_discount'];

                            $k = $map['key'];
                            $salesOrderDetails['unit_discount'][$k] = $map['unit_discount'];
                            $salesOrderDetails['total_discount'][$k] = $map['total_discount'];
                            $salesOrderDetails['amount'][$k] = $map['amount'];
                        }

                        $tentativeDiscount += $totalDiscount;
                        $tentativeTotal -= $totalDiscount;
                        $tentativeNet = $tentativeTotal - $tentativeDiscount + ($data['vat'] ?? 0);
                    } elseif ($offer->offer_type == 'gift') {
                        foreach ($detail->giftOfferProducts as $gift) {
                            $giftProd = $gift->product_id;
                            $giftQty = $gift->quantity * $multiplier;
                            $giftPrice = $gift->product->mrp ?? 0; // Assume product has MRP

                            // Calculate the value of free products as additional discount
                            $giftProductDiscount = $giftPrice * $giftQty;
                            // $additionalDiscount += $giftProductDiscount;

                            // Append free product to salesOrderDetails with full discount
                            $salesOrderDetails['product_ids'][$nextKey] = $giftProd;
                            $salesOrderDetails['quantity'][$nextKey] = $giftQty;
                            $salesOrderDetails['price'][$nextKey] = $giftPrice;
                            $salesOrderDetails['unit_discount'][$nextKey] = $giftPrice; // Full discount
                            $salesOrderDetails['total_discount'][$nextKey] = $giftProductDiscount;
                            $salesOrderDetails['amount'][$nextKey] = 0;
                            $salesOrderDetails['is_offers_product'][$nextKey] = true; // Mark as offer product

                            $nextKey++;
                        }
                    }
                }
            }
        }
        // dd($matchTest);


        // Process clearance offers (amount-based)
        foreach ($offers->where('offer_type', 'clearance') as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $ranges = $detail->clearanceRanges ?? [];

                foreach ($ranges as $range) {
                    $from = $range->from;
                    $to = $range->to ?? PHP_INT_MAX;

                    if ($tentativeNet >= $from && $tentativeNet <= $to) {
                        if ($detail->offerDiscounts->count() > 0) {
                            $disc = $detail->offerDiscounts->first();
                            $discType = $disc->discount_type;
                            $discAmount = $disc->discount_quentity;

                            $clearanceDiscount = $discType == 'percentage_discount' ? ($tentativeNet * $discAmount / 100) : $discAmount;

                            // Add to additional discount
                            $additionalDiscount += $clearanceDiscount;

                            $tentativeDiscount += $clearanceDiscount;
                            $tentativeTotal -= $clearanceDiscount;
                            $tentativeNet -= $clearanceDiscount;
                        } elseif ($detail->giftOfferProducts->count() > 0) {
                            foreach ($detail->giftOfferProducts as $gift) {
                                $giftProd = $gift->product_id;
                                $giftQty = $gift->quantity;
                                $giftPrice = $gift->product->mrp ?? 0; // Assume product has MRP

                                // Calculate the value of free products as additional discount
                                $giftProductDiscount = $giftPrice * $giftQty;
                                // $additionalDiscount += $giftProductDiscount;

                                // Append free product to salesOrderDetails with full discount
                                $salesOrderDetails['product_ids'][$nextKey] = $giftProd;
                                $salesOrderDetails['quantity'][$nextKey] = $giftQty;
                                $salesOrderDetails['price'][$nextKey] = $giftPrice;
                                $salesOrderDetails['unit_discount'][$nextKey] = $giftPrice; // Full discount
                                $salesOrderDetails['total_discount'][$nextKey] = $giftProductDiscount;
                                $salesOrderDetails['amount'][$nextKey] = 0;
                                $salesOrderDetails['is_offers_product'][$nextKey] = true; // Mark as offer product

                                $nextKey++;
                            }
                        }
                        break;
                    }
                }
            }
        }

        // Store the additional discount in data
        $data['additional_discount'] = $additionalDiscount;
        $data['discount'] = $tentativeDiscount;
        $data['total'] = $tentativeTotal;
        $data['net_amount'] = $tentativeNet;

        // $data['due_amount'] = $tentativeNet - ($data['paid_amount'] ?? 0);
        // if ($data['due_amount'] < 0) {
        //     $data['advance_amount'] = abs($data['due_amount']);
        //     $data['due_amount'] = 0;
        // }
    }


    /**
     * Apply discount-based offers
     */
    protected function applyOfferDiscount(array &$data, array &$salesOrderDetails, array &$payments)
    {
        $offers = Offer::with([
            'offerDetails.discountSalesProducts',
            'offerDetails.offerDiscounts'
        ])
            ->where('rule_status', 'running')
            ->where('applied_date', '<=', Carbon::parse($data['invoice_date']))
            ->where('stop_date', '>=', Carbon::parse($data['invoice_date']))
            ->where('offer_type', 'discount')
            ->get();

        // Build buying map and calculate initial totals
        $buyingMap = [];
        $tentativeTotal = 0;
        $tentativeDiscount = $data['discount'] ?? 0;
        $additionalDiscount = 0;

        foreach ($salesOrderDetails['product_ids'] as $key => $productId) {
            $qty = $salesOrderDetails['quantity'][$key];
            $price = $salesOrderDetails['price'][$key];
            $unitDisc = $salesOrderDetails['unit_discount'][$key];
            $totalDisc = $salesOrderDetails['total_discount'][$key];
            $amount = $salesOrderDetails['amount'][$key];

            $buyingMap[$productId] = [
                'quantity' => $qty,
                'price' => $price,
                'unit_discount' => $unitDisc,
                'total_discount' => $totalDisc,
                'amount' => $amount,
                'key' => $key,
            ];

            $tentativeTotal += $amount;
        }

        $tentativeNet = $tentativeTotal - $tentativeDiscount + ($data['vat'] ?? 0);

        // Process discount offers
        foreach ($offers as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $buyingProducts = $detail->discountSalesProducts;
                $match = true;

                foreach ($buyingProducts as $buy) {
                    $prod = $buy->sales_product ?? $buy->product_id;
                    $reqQty = $buy->sales_quentity ?? $buy->quantity;

                    if (!isset($buyingMap[$prod]) || $buyingMap[$prod]['quantity'] != $reqQty) {
                        $match = false;
                        break;
                    }
                }

                if ($match && $detail->offerDiscounts->count() > 0) {
                    $disc = $detail->offerDiscounts->first();
                    $discType = $disc->discount_type;
                    $discAmount = $disc->discount_quentity;

                    // Calculate total bundle cost
                    $bundleCost = 0;
                    foreach ($buyingProducts as $buy) {
                        $prod = $buy->sales_product;
                        $reqQty = $buy->sales_quentity;
                        $bundleCost += $buyingMap[$prod]['price'] * $reqQty;
                    }

                    // Discount amount
                    $discountPerBundle = $discType == 'percentage_discount'
                        ? ($bundleCost * $discAmount / 100)
                        : $discAmount;

                    $totalDiscount = $discountPerBundle;
                    $additionalDiscount += $totalDiscount;

                    // Apply discount to products
                    foreach ($buyingProducts as $buy) {
                        $prod = $buy->sales_product;
                        $reqQty = $buy->sales_quentity;
                        $unitPrice = $buyingMap[$prod]['price'];
                        $prodBundleShare = $unitPrice * $reqQty / $bundleCost;
                        $prodDiscount = $totalDiscount * $prodBundleShare;

                        $map = &$buyingMap[$prod];
                        $map['total_discount'] += $prodDiscount;
                        $map['unit_discount'] = $map['total_discount'] / $map['quantity'];
                        $map['amount'] = ($map['price'] * $map['quantity']) - $map['total_discount'];

                        $k = $map['key'];
                        $salesOrderDetails['unit_discount'][$k] = $map['unit_discount'];
                        $salesOrderDetails['total_discount'][$k] = $map['total_discount'];
                        $salesOrderDetails['amount'][$k] = $map['amount'];
                    }

                    $tentativeDiscount += $totalDiscount;
                    $tentativeTotal -= $totalDiscount;
                    $tentativeNet = $tentativeTotal - $tentativeDiscount + ($data['vat'] ?? 0);
                }
            }
        }

        // Update totals
        $data['additional_discount'] = $additionalDiscount;
        $data['discount'] = $tentativeDiscount;
        $data['total'] = $tentativeTotal;
        $data['net_amount'] = $tentativeNet;
    }


    /**
     * Apply gift-based (offer product) offers
     */
    protected function applyOfferProduct(array &$data, array &$salesOrderDetails, array &$payments)
    {
        $offers = Offer::with([
            'offerDetails.giftSalesProducts',
            'offerDetails.giftOfferProducts'
        ])
            ->where('rule_status', 'running')
            ->where('applied_date', '<=', Carbon::parse($data['invoice_date']))
            ->where('stop_date', '>=', Carbon::parse($data['invoice_date']))
            ->where('offer_type', 'gift')
            ->get();

        // Build buying map
        $buyingMap = [];
        foreach ($salesOrderDetails['product_ids'] as $key => $productId) {
            $buyingMap[$productId] = [
                'quantity' => $salesOrderDetails['quantity'][$key],
                'price' => $salesOrderDetails['price'][$key],
            ];
        }

        $nextKey = count($salesOrderDetails['product_ids']);

        // Process gift offers
        foreach ($offers as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $buyingProducts = $detail->giftSalesProducts;
                $match = true;

                foreach ($buyingProducts as $buy) {
                    $prod = $buy->sales_product ?? $buy->product_id;
                    $reqQty = $buy->sales_quentity ?? $buy->quantity;

                    if (!isset($buyingMap[$prod]) || $buyingMap[$prod]['quantity'] != $reqQty) {
                        $match = false;
                        break;
                    }
                }

                if ($match) {
                    foreach ($detail->giftOfferProducts as $gift) {
                        $giftProd = $gift->product_id;
                        $giftQty = $gift->quantity;
                        $giftPrice = $gift->product->mrp ?? 0;
                        $giftProductDiscount = $giftPrice * $giftQty;

                        $salesOrderDetails['product_ids'][$nextKey] = $giftProd;
                        $salesOrderDetails['quantity'][$nextKey] = $giftQty;
                        $salesOrderDetails['price'][$nextKey] = $giftPrice;
                        $salesOrderDetails['unit_discount'][$nextKey] = $giftPrice;
                        $salesOrderDetails['total_discount'][$nextKey] = $giftProductDiscount;
                        $salesOrderDetails['amount'][$nextKey] = 0;
                        $salesOrderDetails['is_offers_product'][$nextKey] = true;

                        $nextKey++;
                    }
                }
            }
        }

        // dd($salesOrderDetails);
    }


    /**
     * Get total discount amount for given products and quantities
     *
     * @param  array  $productsWithQty  Example: [product_id => quantity, ...]
     * @param  string|null  $invoiceDate  (Optional) Date to check offer validity, defaults to today
     * @return float  The total applicable discount amount
     */
    protected function getDiscountAmountForProducts(array $productsWithQty, ?string $invoiceDate = null): float
    {
        $invoiceDate = $invoiceDate ? Carbon::parse($invoiceDate) : Carbon::today();

        $offers = Offer::with([
            'offerDetails.discountSalesProducts',
            'offerDetails.offerDiscounts'
        ])
            ->where('rule_status', 'running')
            ->where('applied_date', '<=', $invoiceDate)
            ->where('stop_date', '>=', $invoiceDate)
            ->where('offer_type', 'discount')
            ->get();

        $totalDiscountAmount = 0;

        foreach ($offers as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $buyingProducts = $detail->discountSalesProducts;
                $match = true;

                // Check if the required products and quantities match
                foreach ($buyingProducts as $buy) {
                    $prod = $buy->sales_product ?? $buy->product_id;
                    $reqQty = $buy->sales_quentity ?? $buy->quantity;

                    if (!isset($productsWithQty[$prod]) || $productsWithQty[$prod] != $reqQty) {
                        $match = false;
                        break;
                    }
                }

                if ($match && $detail->offerDiscounts->count() > 0) {
                    $disc = $detail->offerDiscounts->first();
                    $discType = $disc->discount_type;
                    $discAmount = $disc->discount_quentity;

                    // Calculate bundle cost using product prices
                    $bundleCost = 0;
                    foreach ($buyingProducts as $buy) {
                        $product = $buy->product ?? $buy->salesProduct;
                        $prodId = $buy->sales_product ?? $buy->product_id;
                        $reqQty = $buy->sales_quentity ?? $buy->quantity;

                        // Fallback to product MRP if available
                        $price = $product->mrp ?? 0;
                        $bundleCost += $price * $reqQty;
                    }

                    // Calculate discount amount
                    $discountPerBundle = $discType === 'percentage_discount'
                        ? ($bundleCost * $discAmount / 100)
                        : $discAmount;

                    $totalDiscountAmount += $discountPerBundle;
                }
            }
        }

        return $totalDiscountAmount;
    }

    public function calculateDiscountAmountForProducts(array $productsWithQty, ?string $invoiceDate = null): float
    {
        return $this->getDiscountAmountForProducts($productsWithQty, $invoiceDate);
    }

    /**
     * Get discount details (including product IDs) for given products and quantities
     *
     * @param  array  $productsWithQty  Example: [product_id => quantity, ...]
     * @param  string|null  $invoiceDate  Optional invoice date to check validity
     * @return array
     */
    public function getDiscountDetailsForProducts(array $productsWithQty, ?string $invoiceDate = null): array
    {
        // dd($productsWithQty);
        $invoiceDate = $invoiceDate ? Carbon::parse($invoiceDate) : Carbon::today();

        $offers = Offer::with([
            'offerDetails.discountSalesProducts.product',
            'offerDetails.offerDiscounts'
        ])
            ->where('rule_status', 'running')
            ->whereDate('applied_date', '<=', $invoiceDate)
            ->whereDate('stop_date', '>=', $invoiceDate)
            ->where('offer_type', 'discount')
            ->get();

        $discountResults = [];

        foreach ($offers as $offer) {
            foreach ($offer->offerDetails as $detail) {
                $buyingProducts = $detail->discountSalesProducts;
                $match = true;

                // ✅ Check if required products and quantities match
                foreach ($buyingProducts as $buy) {
                    $prod = $buy->sales_product ?? $buy->product_id;
                    $reqQty = $buy->sales_quentity ?? $buy->quantity;

                    // if (!isset($productsWithQty[$prod]) || $productsWithQty[$prod] < $reqQty) {
                    if (!isset($productsWithQty[$prod])) {
                        $match = false;
                        break;
                    }
                }

                // ✅ Apply discount if matched
                if ($match && $detail->offerDiscounts->count() > 0) {
                    $disc = $detail->offerDiscounts->first();
                    $discType = $disc->discount_type;
                    $discAmount = $disc->discount_quentity;

                    // ✅ Calculate total bundle cost
                    $bundleCost = 0;
                    $productDetails = [];
                    foreach ($buyingProducts as $buy) {
                        $product = $buy->product ?? $buy->salesProduct;
                        $prodId = $buy->sales_product ?? $buy->product_id;
                        $reqQty = $buy->sales_quentity ?? $buy->quantity;
                        $price = $product->mrp ?? 0;

                        $bundleCost += $price * $reqQty;

                        $productDetails[] = [
                            'product_id' => $prodId,
                            'product_name' => $product->name ?? null,
                            'required_qty' => $reqQty,
                            'price' => $price,
                            'subtotal' => $price * $reqQty,
                        ];
                    }

                    // ✅ Calculate discount amount
                    $discountPerBundle = $discType === 'percentage_discount'
                        ? ($bundleCost * $discAmount / 100)
                        : $discAmount;

                    // ✅ Format discount type
                    $formattedDiscountType = $discType === 'percentage_discount' ? 'percentage' : 'fixed';

                    $discountResults[] = [
                        'offer_id' => $offer->id,
                        'offer_name' => $offer->name ?? null,
                        'discount_type' => $formattedDiscountType,
                        'discount_value' => $discAmount,
                        'discount_amount' => $discountPerBundle,
                        'products' => $productDetails,
                    ];
                }
            }
        }

        return $discountResults;
    }


    public function getClearageDetailsForProducts($salesOrder, ?string $invoiceDate = null)
    {
        $invoiceDate = $invoiceDate ? Carbon::parse($invoiceDate) : Carbon::today();

        $offers = Offer::with([
            'offerDetails.clearanceOfferRanges',
        ])
            ->where('rule_status', 'running')
            ->whereDate('applied_date', '<=', $invoiceDate)
            ->whereDate('stop_date', '>=', $invoiceDate)
            ->where('offer_type', 'clearance')
            ->get();

        $matchedOffers = [];
        $netAmount = $salesOrder->net_amount;
        // dd($netAmount);

        foreach ($offers as $offer) {
            foreach ($offer->offerDetails as $detail) {
                foreach ($detail->clearanceOfferRanges as $range) {
                    // dd($range);
                    if ($netAmount >= $range->buying_amount_from && $netAmount <= $range->buying_amount_to) {
                        $matchedOffers[] = $range;
                    }
                }
            }
        }

        return $matchedOffers;
    }


    /**
     * Process delivery with stock details.
     *
     * @deprecated since v1.0.0
     * @param Delivery $delivery
     * @param array $salesOrderDetails
     * @return void
     */
    protected function processDeliverywithStockDetails($delivery, $salesOrderDetails)
    {
        $deliveryService = app(DeliveryService::class);
        $deliveryDetails = ['product_id' => [], 'quantity' => []];
        $deliveryStockDetails = ['lot_no' => [], 'lots_quantity' => [], 'serial_no' => []];

        $hasStockDetails = false;

        foreach ($salesOrderDetails as $index => $detail) {
            $productId = $detail->product_id;
            $quantity = $detail->quantity;

            if ($detail->stock_details) {
                $stockDetails = json_decode($detail->stock_details, true);

                if (!empty($stockDetails)) {
                    $hasStockDetails = true;

                    if (!isset($deliveryStockDetails['lot_no'][$productId])) {
                        $deliveryStockDetails['lot_no'][$productId] = [];
                    }
                    if (!isset($deliveryStockDetails['lots_quantity'][$productId])) {
                        $deliveryStockDetails['lots_quantity'][$productId] = [];
                    }
                    if (!isset($deliveryStockDetails['serial_no'][$productId])) {
                        $deliveryStockDetails['serial_no'][$productId] = [];
                    }

                    foreach ($stockDetails as $stock) {
                        $batchNo = $stock['batch_no'] ?? '';
                        $stockType = $stock['type'] ?? 'lot';
                        $stockQuantity = $stock['quantity'] ?? 1;

                        if ($stockType === 'lot') {
                            $deliveryStockDetails['lot_no'][$productId][] = $batchNo;
                            $deliveryStockDetails['lots_quantity'][$productId][] = $stockQuantity;
                        } elseif ($stockType === 'serial') {
                            $deliveryStockDetails['serial_no'][$productId][] = $batchNo;
                        }
                    }
                }
            }

            $deliveryDetails['product_id'][] = $productId;
            $deliveryDetails['quantity'][] = $quantity;
            $deliveryDetails['sales_quantity'][] = $quantity;
        }

        if ($hasStockDetails) {
            $deliveryService->update($delivery, $deliveryDetails, $deliveryStockDetails);
        }
    }

    public function saveFromRequisition(SalesRequisition $salesRequisition)
    {
        DB::beginTransaction();
        $data = [
            'customer_id' => $salesRequisition->customer_id,
            'service_id' => $salesRequisition->service_id,
            'additional_phone' => $salesRequisition->additional_phone,
            'invoice_date' => $salesRequisition->invoice_date,
            'total_amount' => $salesRequisition->total_amount,
            'discount' => $salesRequisition->discount,
            'commission' => $salesRequisition->commission ?? 0,
            'total' => $salesRequisition->total,
            'vat' => $salesRequisition->vat ?? 0,
            'net_amount' => $salesRequisition->net_amount,
            'remarks' => $salesRequisition->remarks,
            'status' => 'pending',
            'is_shipment' => $salesRequisition->is_shipment,
            'is_courier' => $salesRequisition->is_courier,
            'delivery_date' => $salesRequisition->delivery_date,
            'sales_type' => 'general_sales',
            'reference_id' => $salesRequisition->reference_id,
            'source_type' => SalesRequisition::class,
            'source_id' => $salesRequisition->id,
        ];

        $salesOrderDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
            'sales_order_detail_id' => [],
        ];

        $existingSalesOrder = SalesOrder::where('source_type', SalesRequisition::class)
            ->where('source_id', $salesRequisition->id)
            ->first();

        if ($existingSalesOrder) {
            $existingDetails = $existingSalesOrder->salesOrderDetails->keyBy('product_id');
        } else {
            $existingDetails = collect();
        }

        foreach ($salesRequisition->salesRequisitionDetails as $key => $salesRequisitionDetail) {
            $salesOrderDetails['product_ids'][$key] = $salesRequisitionDetail->product_id;
            $salesOrderDetails['quantity'][$key] = $salesRequisitionDetail->quantity;
            $salesOrderDetails['price'][$key] = $salesRequisitionDetail->price;
            $salesOrderDetails['unit_discount'][$key] = $salesRequisitionDetail->unit_discount;
            $salesOrderDetails['total_discount'][$key] = $salesRequisitionDetail->total_discount;
            $salesOrderDetails['amount'][$key] = $salesRequisitionDetail->amount;

            $existingDetail = $existingDetails->get($salesRequisitionDetail->product_id);
            $salesOrderDetails['sales_order_detail_id'][$key] = $existingDetail ? $existingDetail->id : null;
        }

        $salesOrderShipments = [];
        if ($salesRequisition->shipment) {
            $shipment = $salesRequisition->shipment;
            $salesOrderShipments = [
                'courier_id' => $shipment->courier_id,
                'area_id' => $shipment->area_id,
                'address' => $shipment->address,
                'contact_person_name' => $shipment->contact_person_name,
                'contact_person_number' => $shipment->contact_person_number,
                'condition' => $shipment->condition,
                'additional_amount' => $shipment->additional_amount,
                'condition_remarks' => $shipment->condition_remarks,
            ];
            $data['is_shipment'] = 1;
        }

        $payments = [];
        if ($salesRequisition->payments->count() > 0) {
            $payments['payments_pay_mode'] = [];
            $payments['payments_bank_id'] = [];
            $payments['payments_branch_id'] = [];
            $payments['payments_transaction_id'] = [];
            $payments['payments_emi_id'] = [];
            $payments['payments_amount'] = [];
            $payments['payments_date'] = [];
            $payments['payments_attachments'] = [];
            $payments['payments_verified'] = [];
            $payments['payments_remark'] = [];

            foreach ($salesRequisition->payments as $key => $payment) {
                $payments['payments_pay_mode'][$key] = $payment->pay_mode;
                $payments['payments_bank_id'][$key] = $payment->bank_id ?? null;
                $payments['payments_branch_id'][$key] = $payment->branch_id ?? null;
                $payments['payments_transaction_id'][$key] = $payment->transaction_id ?? null;
                $payments['payments_emi_id'][$key] = $payment->e_m_i_entries_id ?? null;
                $payments['payments_amount'][$key] = $payment->amount ?? 0;
                $payments['payments_date'][$key] = $payment->date;
                $payments['payments_attachments'][$key] = $payment->attachments ?? null;
                $payments['payments_verified'][$key] = $payment->verified ?? false;
                $payments['payments_remark'][$key] = $payment->remarks ?? null;
            }
        }

        if ($existingSalesOrder) {
            $result = $this->update($existingSalesOrder, $data, $salesOrderDetails, $salesOrderShipments, $payments);
            $salesOrder = $result;
        } else {
            $result = $this->store($data, $salesOrderDetails, $salesOrderShipments, $payments);
            $salesOrder = $result['salesOrder'];
        }

        DB::commit();
        return $salesOrder;
    }

    public function saveFromChallan(BackupChallan $backupChallan)
    {
        DB::beginTransaction();
        $data = [
            'customer_id' => $backupChallan->customer_id,
            'invoice_date' => now()->format('Y-m-d'),
            'total_amount' => $backupChallan->total_amount,
            'discount' => $backupChallan->discount ?? 0,
            'commission' => 0, // Assuming no commission from challan
            'total' => $backupChallan->total_amount - ($backupChallan->discount ?? 0),
            'vat' => $backupChallan->vat ?? 0,
            'net_amount' => $backupChallan->total_amount,
            'remarks' => $backupChallan->remarks ?? 'Created from Challan ' . $backupChallan->challan_id,
            'status' => 'pending',
            'is_shipment' => false, // Assuming no shipment info from challan, can be adjusted
            'is_courier' => false,
            'sales_type' => 'general_sales',
            'source_type' => BackupChallan::class,
            'source_id' => $backupChallan->id,
        ];

        $salesOrderDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
            'sales_order_detail_id' => [],
        ];

        $existingSalesOrder = SalesOrder::where('source_type', BackupChallan::class)
            ->where('source_id', $backupChallan->id)
            ->first();

        if ($existingSalesOrder) {
            $existingDetails = $existingSalesOrder->salesOrderDetails->keyBy('product_id');
        } else {
            $existingDetails = collect();
        }

        foreach ($backupChallan->backupChallanDetails as $key => $challanDetail) {
            // dd($challanDetail);
            $salesOrderDetails['product_ids'][$key] = $challanDetail->product_id;
            $salesOrderDetails['quantity'][$key] = $challanDetail->quantity;
            $salesOrderDetails['price'][$key] = $challanDetail->price;
            $salesOrderDetails['unit_discount'][$key] = 0; // Assuming no unit discount from challan
            $salesOrderDetails['total_discount'][$key] = 0; // Assuming no total discount from challan
            $salesOrderDetails['amount'][$key] = $challanDetail->amount;

            $existingDetail = $existingDetails->get($challanDetail->product_id);
            $salesOrderDetails['sales_order_detail_id'][$key] = $existingDetail ? $existingDetail->id : null;
        }

        // BackupChallan does not seem to have shipment or payment info.
        // If it does, logic can be added here similar to saveFromRequisition.
        $salesOrderShipments = [];
        $payments = [
            'payments_pay_mode' => [],
            'payments_bank_id' => [],
            'payments_branch_id' => [],
            'payments_transaction_id' => [],
            'payments_emi_id' => [],
            'payments_amount' => [],
            'payments_date' => [],
            'payments_attachments' => [],
            'payments_verified' => [],
            'payments_remark' => [],
            'payments_total_amount' => $data['net_amount'],
            'payments_payable_amount' => $data['net_amount'],
        ];

        if ($existingSalesOrder) {
            $salesOrder = $this->update($existingSalesOrder, $data, $salesOrderDetails, $salesOrderShipments, $payments);
        } else {
            $result = $this->store($data, $salesOrderDetails, $salesOrderShipments, $payments);
            $salesOrder = $result['salesOrder'];
        }

        DB::commit();
        return $salesOrder;
    }

    /**
     * @deprecated
     */
    protected function storePaymentDetails($salesPayment, $salesPaymentDetail, $payments, $index, $mode)
    {
        switch ($mode) {
            case 'cash':
                if (isset($payments['cash_payment_amount'][$index])) {
                    foreach ($payments['cash_payment_amount'][$index] as $key => $cashAmount) {
                        SalesPaymentCash::create([
                            'sales_payment_detail_id' => $salesPaymentDetail->id,
                            'cash_payment_date' => $payments['cash_payment_date'][$index][$key],
                            'cash_payment_amount' => $cashAmount,
                            'cash_payment_remarks' => $payments['cash_payment_remarks'][$index][$key] ?? null,
                        ]);
                    }
                }
                break;

            case 'cheque':
                if (isset($payments['cheque_no'][$index])) {
                    foreach ($payments['cheque_no'][$index] as $key => $chequeNo) {
                        SalesPaymentCheque::create([
                            'sales_payment_detail_id' => $salesPaymentDetail->id,
                            'cheque_bank_id' => $payments['cheque_bank_id'][$index][$key],
                            'cheque_branch_id' => $payments['cheque_branch_id'][$index][$key],
                            'cheque_no' => $chequeNo,
                            'cheque_date' => $payments['cheque_date'][$index][$key],
                            'cheque_amount' => $payments['cheque_amount'][$index][$key],
                            'cheque_remarks' => $payments['cheque_remarks'][$index][$key] ?? null,
                        ]);
                    }
                }
                break;

            case 'bkash':
                if (isset($payments['bkash_payment_no'][$index])) {
                    foreach ($payments['bkash_payment_no'][$index] as $key => $bkashNo) {
                        SalesPaymentBkash::create([
                            'sales_payment_detail_id' => $salesPaymentDetail->id,
                            'bkash_collection_point' => $payments['bkash_collection_point'][$index][$key],
                            'bkash_payment_no' => $bkashNo,
                            'bkash_payment_date' => $payments['bkash_payment_date'][$index][$key],
                            'bkash_payment_amount' => $payments['bkash_payment_amount'][$index][$key],
                            'bkash_payment_remarks' => $payments['bkash_payment_remarks'][$index][$key] ?? null,
                        ]);
                    }
                }
                break;

            case 'card_payment':
                if (isset($payments['card_payment_no'][$index])) {
                    foreach ($payments['card_payment_no'][$index] as $key => $cardNo) {
                        SalesPaymentCardPayment::create([
                            'sales_payment_detail_id' => $salesPaymentDetail->id,
                            'card_payment_no' => $cardNo,
                            'card_payment_date' => $payments['card_payment_date'][$index][$key],
                            'card_payment_amount' => $payments['card_payment_amount'][$index][$key],
                            'card_payment_remarks' => $payments['card_payment_remarks'][$index][$key] ?? null,
                        ]);
                    }
                }
                break;

            case 'online_deposit':
                if (isset($payments['online_deposit_no'][$index])) {
                    foreach ($payments['online_deposit_no'][$index] as $key => $depositNo) {
                        SalesPaymentOnlineDeposit::create([
                            'sales_payment_detail_id' => $salesPaymentDetail->id,
                            'online_deposit_bank_id' => $payments['online_deposit_bank_id'][$index][$key],
                            'online_deposit_branch_id' => $payments['online_deposit_branch_id'][$index][$key],
                            'online_deposit_no' => $depositNo,
                            'online_deposit_date' => $payments['online_deposit_date'][$index][$key],
                            'online_deposit_amount' => $payments['online_deposit_amount'][$index][$key],
                            'online_deposit_remarks' => $payments['online_deposit_remarks'][$index][$key] ?? null,
                        ]);
                    }
                }
                break;
        }
    }

    public function update(SalesOrder $salesOrder, array $data, array $salesOrderDetails, array $salesOrderShipments, array $payments)
    {
        $this->applyOfferProduct($data, $salesOrderDetails, $payments);

        $result['salesOrder'] = $salesOrder;
        DB::beginTransaction();
        // Only generate a new sales_order_id if one is not already provided in the data and it's currently empty
        if ((!isset($data['sales_order_id']) || empty($data['sales_order_id'])) && empty($salesOrder->sales_order_id)) {
            $data['sales_order_id'] = $this->getSalesOrderId($data['customer_id']);
        }
        $data['sales_type'] = $data['sales_type'] ?? 'general_sales';
        $data['user_ref_id'] = Customer::find($data['customer_id'])->user_ref_id;
        $result['salesOrder']->update($data);
        $result['salesOrder']->salesOrderDetails()->whereNotIn('id', $salesOrderDetails['sales_order_detail_id'])->delete();
        $result['salesOrderDetails'] = [];
        $cashCollectionAmount = 0; // Initialize cash collection amount for SMS
        $paymentDate  = '';

        foreach ($salesOrderDetails['product_ids'] as $key => $productId) {
            $detailData = [
                'product_id' => $productId,
                'quantity' => $salesOrderDetails['quantity'][$key],
                'price' => $salesOrderDetails['price'][$key],
                'unit_discount' => $salesOrderDetails['unit_discount'][$key],
                'total_discount' => $salesOrderDetails['total_discount'][$key],
                'amount' => $salesOrderDetails['amount'][$key],
            ];

            // Add discount_type if it exists in the salesOrderDetails array
            if (isset($salesOrderDetails['discount_type'][$key])) {
                $detailData['discount_type'] = $salesOrderDetails['discount_type'][$key];
            }

            // Add is_offers_product if it exists in the salesOrderDetails array
            if (isset($salesOrderDetails['is_offers_product'][$key])) {
                $detailData['is_offers_product'] = $salesOrderDetails['is_offers_product'][$key];
            } else {
                $detailData['is_offers_product'] = false; // Default value for non-offer products
            }

            $result['salesOrderDetails'][] = SalesOrderDetails::updateOrCreate([
                'id' => $salesOrderDetails['sales_order_detail_id'][$key] ?? null,
                'sales_order_id' => $salesOrder->id
            ], $detailData);
        }

        if (isset($data['is_shipment']) && $data['is_shipment'] == 1) {
            $salesOrder->shipment()->delete();
            $result['salesOrderShipments'] = $result['salesOrder']->shipment()->create([
                'courier_id' => $salesOrderShipments['courier_id'],
                'area_id' => $salesOrderShipments['area_id'] == 'address' ? null : $salesOrderShipments['area_id'],
                'address' => $salesOrderShipments['address'],
                'contact_person_name' => $salesOrderShipments['contact_person_name'],
                'contact_person_number' => $salesOrderShipments['contact_person_number'],
                'condition' => ($salesOrderShipments['condition'] ?? false) ? true : false,
                'additional_amount' => ($salesOrderShipments['condition'] ?? false) ? $salesOrderShipments['additional_amount'] : null,
                'condition_remarks' => ($salesOrderShipments['condition'] ?? false) ? $salesOrderShipments['condition_remarks'] : null,
            ]);
        } else {
            $salesOrder->shipment()->delete();
        }

        if ($data['status'] == 'approved') {
            $delivery = Delivery::updateOrCreate([
                'source_id' => $salesOrder->id,
                'source_type' => SalesOrder::class,
            ], [
                'delivery_date' => $data['delivery_date'] ?? $data['invoice_date'],
            ]);

            $result['delivery'] = $delivery;
        } elseif ($data['status'] == 'pending') {
            $result['salesOrder']->update(['status' => 'pending']);
            $result['salesOrder']->delivery()->delete();
        }

        if (request()->filled('otp_verifications')) {
            foreach (request()->otp_verifications as $otpJson) {
                $otpData = json_decode($otpJson, true);

                $otpData['sourceable_id'] = $salesOrder->id;
                $otpData['sourceable_type'] = SalesOrder::class;

                OtpVerification::updateOrCreate(
                    ['id' => $otpData['id'] ?? null],
                    $otpData
                );
            }
        }

        $result['salesOrder']->payments()->delete();
        foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
            if ($payMode) {
                $result['payments'][] = $result['salesOrder']->payments()->create([
                    'pay_mode' => $payMode,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'remarks' => $payments['payments_remark'][$key] ?? null,
                ]);


                

                /*count cash collection amount*/ 
                if ($payMode == 'Cash') {
                    $cashCollectionAmount = $payments['payments_amount'][$key] ?? 0;  
                    $paymentDate = $payments['payments_date'][$key] ?? null;
                }
                /*update sales order id in emi*/ 
                if ($payMode == 'EMI') { 
                    EMIEntry::where('id', $payments['payments_emi_id'][$key])
                    ->update([
                        'sales_order_id' => $result['salesOrder']->id,   // link sales id
                    ]);
                  
                }
            }
        }

        if ($salesOrder->status == 'approved') {
            $this->makeDummyTransaction($salesOrder);

            if (!empty($payments['payments_pay_mode'])) {
                $collectionData = [
                    'payments_total_amount' => $payments['payments_total_amount'] ?? 0,
                    'payments_advance_amount' => $payments['payments_advance_amount'] ?? 0,
                    'collection_type' => "customer",
                    'collection_from' => $data['customer_id'] ?? $salesOrder->customer_id,
                    'collection_date' => $data['invoice_date']
                ];
                if (count($result['salesOrder']->payments) > 0) {
                    $this->collectionService->storeForSales($collectionData, $result['salesOrder']->payments, $salesOrder);
                }
            }

            /*Update:: send sms for invoice create */ 
    
            // $serviceName = ServiceName::where('code', 'sales_invoice')->where('status', 1)->first();
            $triggerName = TriggerName::where('code', 'T08')->where('status', 1)->first();
            $sms = SmsTemplate::where('code_name', "TEM012")->first(); 
            $smsTemplate = $sms->template_body;

            $customerInfo = Customer::where('id', $salesOrder->customer_id)->first(); 

            $phone =   $customerInfo->contact_for_sms;
            $customerName = $customerInfo->company_name;
            $invoiceAmount = $salesOrder->net_amount;
            $invoiceLink = $this->generateShareLink($result['salesOrder']->id)['link'];
            
            $smsdata = [
                'customer_name' =>  $customerName,
                'invoice_amount' => $invoiceAmount,
                'invoice_link' => $invoiceLink
            ];    

            foreach ($smsdata as $key => $value) {
                $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
            } 

            $time = Carbon::parse(now()); 
            $newTime = $time->addMinutes($triggerName->after_send_time);
            if (!empty($phone)) {       
                SmsInfo::updateOrCreate(
                    [
                        'sms_reference' => $salesOrder->id,
                        'sms_mem_id' => $salesOrder->customer_id,
                        'sms_status' => 'pending', // condition
                        'trigger_name' => 'T08', 
                    ],
                    [
                        'sms_send_time' => $newTime,
                        'sms_to' => $phone,
                        'sms_text' => $smsTemplate, 
                    ]
                );
            }

                
            /*Update:: send sms for cash collection */
            if ($cashCollectionAmount > 0) {
    
                // $serviceName = ServiceName::where('code', 'cash_collection')->where('status', 1)->first();
                $triggerName = TriggerName::where('code', 'T03')->where('status', 1)->first(); 
                $smsTemplate = $sms->template_body;

                $customerInfo = Customer::where('id', $data['customer_id'])->first(); 

                $phone =   $customerInfo->contact_for_sms; 
                $customerName = $customerInfo->company_name; 
                $customerPreBalance = Customer::find($data['customer_id'])->getAccount()->balance; 
                $collectionAmount = $cashCollectionAmount; 
                $receivedDate = $paymentDate; 
                $customerBalance =  $customerPreBalance +  $result['salesOrder']->net_amount - $collectionAmount;
                
                $data = [
                    'customer_name' => $customerName,
                    'customer_pre_balance ' => $customerPreBalance,
                    'collection_amount' => $collectionAmount,
                    'received_date' => $receivedDate,
                    'customer_current_balance ' => $customerBalance
                ];   

                foreach ($data as $key => $value) {
                    $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
                } 

                $time = Carbon::parse(now()); 
                $newTime = $time->addMinutes($triggerName->after_send_time);
                if (!empty($phone)) {
                    SmsInfo::updateOrCreate(
                        [
                            'sms_reference' => $salesOrder->id,
                            'sms_mem_id' => $salesOrder->customer_id,
                            'sms_status' => 'pending', // condition
                            'trigger_name' => 'T03', 
                        ],
                        [
                            'sms_send_time' => $newTime,
                            'sms_to' => $phone,
                            'sms_text' => $smsTemplate, 
                        ]
                    );
                }
            }
        }

        DB::commit();
        return $salesOrder;
    }

    public function makeDummyTransaction(SalesOrder $salesOrder)
    {
        $salesOrder->transactions()->delete();

        $customerReceivableAccount = $salesOrder->customer->getAccount();
        $vatPayableAccount = Account::where('name', 'Tax Payable')->first();
        $customerSalesDiscountAccount = $salesOrder->customer->getSalesDiscountAccount();

        $totalInvoiceAmount = $salesOrder->net_amount;

        $salesOrder->transactions()->create([
            'account_id' => $customerReceivableAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => $salesOrder->sales_order_id,
            'debit_amount' => $totalInvoiceAmount,
            'credit_amount' => 0,
            'description' => "Invoice for Sales Order #" . $salesOrder->sales_order_id,
            'transaction_date' => $salesOrder->invoice_date
        ]);
        $total_discount = $salesOrder->salesOrderDetails->sum('total_discount');
        if ($total_discount > 0) {
            $salesOrder->transactions()->create([
                'account_id' => $customerSalesDiscountAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $salesOrder->sales_order_id,
                'debit_amount' => $total_discount,
                'credit_amount' => 0,
                'description' => "Invoice for Sales Order #" . $salesOrder->sales_order_id,
                'transaction_date' => $salesOrder->invoice_date

            ]);
        }

        foreach ($salesOrder->salesOrderDetails as $salesOrderDetail) {
            $salesRevenueAccount = $salesOrderDetail->product->getAccount();
            $salesOrder->transactions()->create([
                'account_id' => $salesRevenueAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => $salesOrder->sales_order_id,
                'debit_amount' => 0,
                'credit_amount' => ($salesOrderDetail->price) * $salesOrderDetail->quantity,
                'description' => "Invoice for Sales Order #" . $salesOrder->sales_order_id,
                'transaction_date' => $salesOrder->invoice_date
            ]);
        }
        // dd( $salesOrder->transactions, $totalInvoiceAmount,$salesOrder->vat , $salesOrder->salesOrderDetails);

        if ($salesOrder->vat > 0) {
            $salesOrder->transactions()->create([
                'account_id' => $vatPayableAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => $salesOrder->sales_order_id,
                'debit_amount' => 0,
                'credit_amount' => $salesOrder->vat,
                'description' => "Invoice for Sales Order #" . $salesOrder->sales_order_id,
                'transaction_date' => $salesOrder->invoice_date
            ]);
        }

        $totalDebits = round($salesOrder->transactions()->sum('debit_amount'));
        $totalCredits = round($salesOrder->transactions()->sum('credit_amount'));
        if ($totalDebits != $totalCredits) {
            logger()->error("Journal entries for Sales Order #" . $salesOrder->sales_order_id . " are unbalanced!", ['debits' => $totalDebits, 'credits' => $totalCredits]);
            throw new \Exception("Imbalanced journal entries for sales order. Debits: $totalDebits, Credits: $totalCredits");
        }
    }

    public function makeTransaction(SalesOrder $salesOrder)
    {
        $customerReceivableAccount = $salesOrder->customer->getAccount();
        $salesAccount = Account::where('account_code', '4000')->first();
        $vatAccount = Account::where('account_code', '4100')->first();

        $invoice_no = $salesOrder->sales_order_id;
        $description = 'Sales Order';

        $this->transactionService->storeTransaction(
            SalesOrder::class,
            $salesOrder->id,
            $invoice_no,
            $customerReceivableAccount->id,
            -$salesOrder->net_amount,
            $salesOrder->net_amount,
            0,
            'debit',
            $description
        );

        $this->transactionService->storeTransaction(
            SalesOrder::class,
            $salesOrder->id,
            $invoice_no,
            $salesAccount->id,
            $salesOrder->total_amount,
            0,
            $salesOrder->total_amount,
            'credit',
            $description
        );

        if ($salesOrder->vat > 0) {
            $this->transactionService->storeTransaction(
                SalesOrder::class,
                $salesOrder->id,
                $invoice_no,
                $vatAccount->id,
                $salesOrder->vat,
                0,
                $salesOrder->vat,
                'credit',
                $description
            );
        }

        if ($salesOrder->paid_amount > 0) {
            $paymentDescription = 'Advance Payment for Sales Order';
            foreach ($salesOrder->payments as $payment) {
                $bankAccount = $payment->bank;
                if ($bankAccount) {
                    $paymentAccount = $bankAccount->getAccount();
                    if ($paymentAccount) {
                        $this->transactionService->storeTransaction(
                            SalesOrder::class,
                            $salesOrder->id,
                            $invoice_no,
                            $paymentAccount->id,
                            -$payment->amount,
                            $payment->amount,
                            0,
                            'debit',
                            $paymentDescription
                        );
                    }
                }
            }

            $this->transactionService->storeTransaction(
                SalesOrder::class,
                $salesOrder->id,
                $invoice_no,
                $customerReceivableAccount->id,
                $salesOrder->paid_amount,
                0,
                $salesOrder->paid_amount,
                'credit',
                $paymentDescription
            );
        }
    }

    public function delete(SalesOrder $salesOrder)
    {
        $salesOrder->delete();
    }

    public function show($id)
    {
        return SalesOrder::with([
            'salesOrderDetails',
            'salesOrderDeliveries.salesOrderDeliveryDetails',
            'delivery',
            'customer',
            'createdBy',
            'updatedBy',
            'shipment',
            'payments'
        ])->findOrFail($id);
    }

    public function countSalesOrder()
    {
        return SalesOrder::count();
    }

    public function countSalesOrderCurrentMonth()
    {
        return SalesOrder::query()->whereMonth('created_at', Carbon::now()->month)->count();
    }

    public function countSalesOrderPreviousMonth()
    {
        return SalesOrder::query()->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
    }

    public function countTotalSales()
    {
        return SalesOrder::query()->where('status', 'delivered')->count();
    }

    public function countTotalSalesCurrentMonth()
    {
        return SalesOrder::query()->whereMonth('created_at', Carbon::now()->month)->where('status', 'delivered')->count();
    }

    public function countTotalSalesPreviousMonth()
    {
        return SalesOrder::query()->whereMonth('created_at', Carbon::now()->subMonth()->month)->where('status', 'delivered')->count();
    }

    /**
     * Get an existing free sales invoice by sales order ID.
     *
     * @param int $salesOrderId
     * @return FreeSalesInvoice|null
     */
    public function getFreeSalesInvoiceBySalesOrder(int $salesOrderId): ?FreeSalesInvoice
    {
        return FreeSalesInvoice::with('details.product')->where('sales_order_id', $salesOrderId)->first();
    }

    /**
     * Store or update a free sales invoice and its details.
     *
     * @param int $salesOrderId
     * @param array $data
     * @param int|null $freeSalesInvoiceId
     * @return FreeSalesInvoice
     */
    public function saveFreeSalesInvoice(int $salesOrderId, array $data, ?int $freeSalesInvoiceId = null): FreeSalesInvoice
    {
        DB::beginTransaction();

        try {
            $invoiceId = $freeSalesInvoiceId ? FreeSalesInvoice::findOrFail($freeSalesInvoiceId)->invoice_id : $this->generateFreeSalesInvoiceId();

            $freeSalesInvoice = FreeSalesInvoice::updateOrCreate(
                ['id' => $freeSalesInvoiceId],
                [
                    'sales_order_id' => $salesOrderId,
                    'customer_id' => $data['customer_id'],
                    'invoice_id' => $invoiceId,
                    'invoice_date' => $data['invoice_date'],
                    'remarks' => $data['remarks'],
                    'status' => $data['status'],
                    // 'created_by' and 'updated_by' are handled by AutoCreateUpdateAndHistory trait
                ]
            );

            $salesOrder = SalesOrder::findOrFail($salesOrderId);
            // $freeSalesInvoice->customer_id = $salesOrder->customer_id;


            // Handle details
            $existingDetailIds = $freeSalesInvoice->details->pluck('id')->toArray();
            $newDetailIds = [];

            foreach ($data['product_ids'] as $key => $productId) {
                $detailId = $data['free_sales_invoice_detail_id'][$key] ?? null;

                $detail = $freeSalesInvoice->details()->updateOrCreate(
                    ['id' => $detailId],
                    [
                        'free_sales_invoice_id' => $freeSalesInvoice->id, // Ensure correct foreign key
                        'product_id' => $productId,
                        'quantity' => $data['quantity'][$key],
                    ]
                );
                $newDetailIds[] = $detail->id;
            }

            // Delete details that are no longer present in the request
            $detailsToDelete = array_diff($existingDetailIds, array_filter($newDetailIds));
            if (!empty($detailsToDelete)) {
                $freeSalesInvoice->details()->whereIn('id', $detailsToDelete)->delete();
            }

            // Reload the details relationship to get the updated data
            $freeSalesInvoice->load('details');

            // @dd($freeSalesInvoice->details );
            // Initialize result array
            $result = [];
            $result['salesOrderDetails'] = [];

            // $freeSalesInvoice->details add to sales order details as offers product
            foreach ($freeSalesInvoice->details as $detail) {
                $detailData = [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->product->mrp,
                    'unit_discount' => $detail->product->mrp,
                    'total_discount' => $detail->product->mrp * $detail->quantity,
                    'amount' => 0,
                    'is_offers_product' => 2,
                ];

                // Add is_offers_product if it exists in the salesOrderDetails array
                // if (isset($salesOrderDetails['is_offers_product'][$key])) {
                //     $detailData['is_offers_product'] = ;
                // } else {
                //     $detailData['is_offers_product'] = false; // Default value for non-offer products
                // }

                $result['salesOrderDetails'][] = SalesOrderDetails::updateOrCreate([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $detail->product_id,
                ], $detailData);
            }


            DB::commit();
            return $freeSalesInvoice;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Renamed from storeFreeSalesInvoice to generateFreeSalesInvoiceId and made private
    private function generateFreeSalesInvoiceId(): string
    {
        $prefix = 'FREE-';
        $todayCount = FreeSalesInvoice::whereDate('created_at', Carbon::today())->count();
        $id = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . date('Ymd') . '-' . $id;
    }
}
