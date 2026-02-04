<?php

namespace Modules\CRM\Services\Customer;

use App\Traits\S3FileHandler;
use Carbon\Carbon;
use Modules\Account\Models\Account;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerOwner;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\CustomerSettingBroker;
use Modules\CRM\Models\Customer\CustomerSettingDiscount;
use Modules\CRM\Models\Customer\CustomerSettingFixedDiscount;
use Modules\CRM\Models\Customer\CustomerShippingNew;
use Modules\CRM\Models\Customer\Settings\CustomerType;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Tag;
use Modules\LocationManager\Models\Area;

class CustomerService
{
    use S3FileHandler;

    public function getAll(int $limit = 20)
    {
        return Customer::query()
            ->searchByFields(['company_name', 'email', 'user_ref_id'])
            ->likeSearch('phone')
            ->paginate($limit);
    }

    public function create(array $data, array $customerShipping, array $customerOwner)
    {
        // dd($data, $customerShipping, $customerBilling);
        // if (isset($data['profile_picture'])) {
        //     $data['profile_picture'] = $this->uploadFile($data['profile_picture']);
        // }
        // if (isset($data['logo'])) {
        //     $data['logo'] = $this->uploadFile($data['logo']);
        // }
        // if (isset($data['front_image'])) {
        //     $data['front_image'] = $this->uploadFile($data['front_image']);
        // }
        // if (isset($data['back_image'])) {
        //     $data['back_image'] = $this->uploadFile($data['back_image']);
        // }
        // if (isset($data['visiting_card_front'])) {
        //     $data['visiting_card_front'] = $this->uploadFile($data['visiting_card_front']);
        // }
        // if (isset($data['visiting_card_back'])) {
        //     $data['visiting_card_back'] = $this->uploadFile($data['visiting_card_back']);
        // }
        // if (isset($data['trade_license'])) {
        //     $data['trade_license'] = $this->uploadFile($data['trade_license']);
        // }
        // if (isset($data['signature'])) {
        //     $data['signature'] = $this->uploadFile($data['signature']);
        // }

        $result['customers'] = Customer::create($data);

        $result['customerShipping'] = [];
        $result['customerOwner'] = [];
        if (isset($customerShipping['ship_to']) && count($customerShipping['ship_to']) > 0) {
            foreach ($customerShipping['ship_to'] ?? [] as $key => $value) {
                if ($value != null) {
                    $result['customerShipping'][] = CustomerShippingNew::create([
                        'customer_id' => $result['customers']->id,
                        'ship_to' => $customerShipping['ship_to'][$key],
                        'shipping_address' => $customerShipping['shipping_address'][$key],
                        'shipping_phone' => $customerShipping['shipping_phone'][$key] ?? null,
                    ]);
                }
            }
        }
        if (isset($customerOwner['owner_name']) && count($customerOwner['owner_name']) > 0) {
            foreach ($customerOwner['owner_name'] ?? [] as $key => $value) {
                if ($value != null) {
                    $result['customerOwner'][] = CustomerOwner::create([
                        'customer_id' => $result['customers']->id,
                        'owner_name' => $customerOwner['owner_name'][$key],
                        'owner_designation' => $customerOwner['owner_designation'][$key],
                        'owner_mobile' => $customerOwner['owner_mobile'][$key],
                        'owner_email' => $customerOwner['owner_email'][$key],
                        'owner_dob' => $customerOwner['owner_dob'][$key],
                    ]);
                }
            }
        }

        $result['customerSettings'] = CustomerSetting::create([
            'customer_id' => $result['customers']->id,
            'customer_rating' => 1,
            'customer_status' => 1,
            'credit_limit' => 0,
            'additional_credit_limit' => 0,
            'opening_balance' => 0,
            'is_condition_bill' => 0,
            'minimum_condition_bill' => 1,
            'vat_status' => 0,
            'is_document_return' => 0,
            'service_applicable' => 0,
            'discount_type' => 0,
        ]);

        return $result;
    }

    public function update(Customer $customer, array $data, array $customerShipping, array $customerOwner)
    {
        // if (isset($data['profile_picture'])) {
        //     $data['profile_picture'] = $this->uploadFile($data['profile_picture']);
        // }
        // if (isset($data['logo'])) {
        //     $data['logo'] = $this->uploadFile($data['logo']);
        // }
        // if (isset($data['front_image'])) {
        //     $data['front_image'] = $this->uploadFile($data['front_image']);
        // }
        // if (isset($data['back_image'])) {
        //     $data['back_image'] = $this->uploadFile($data['back_image']);
        // }
        // if (isset($data['visiting_card_front'])) {
        //     $data['visiting_card_front'] = $this->uploadFile($data['visiting_card_front']);
        // }
        // if (isset($data['visiting_card_back'])) {
        //     $data['visiting_card_back'] = $this->uploadFile($data['visiting_card_back']);
        // }
        // if (isset($data['trade_license'])) {
        //     $data['trade_license'] = $this->uploadFile($data['trade_license']);
        // }
        // if (isset($data['signature'])) {
        //     $data['signature'] = $this->uploadFile($data['signature']);
        // }

        $result['customers'] = $customer->update($data);
        // dd($customer);

        CustomerShippingNew::where('customer_id', $customer->id)->delete();
        if (isset($customerShipping['ship_to']) && count($customerShipping['ship_to']) > 0) {
            foreach ($customerShipping['ship_to'] as $key => $value) {
                if ($value != null) {

                    $shippingData = [
                        'customer_id' => $customer->id,
                        'ship_to' => $customerShipping['ship_to'][$key],
                        'shipping_address' => $customerShipping['shipping_address'][$key],
                        'shipping_phone' => $customerShipping['shipping_phone'][$key],

                    ];

                    if (isset($customer->customerShippingAddress[$key])) {
                        $customer->customerShippingAddress[$key]->update($shippingData);
                    } else {
                        CustomerShippingNew::create($shippingData);
                    }
                }
            }
        }

        CustomerOwner::where('customer_id', $customer->id)->delete();
        if (isset($customerOwner['owner_name']) && count($customerOwner['owner_name']) > 0) {
            foreach ($customerOwner['owner_name'] as $key => $value) {
                if ($value != null) {
                    $ownerData = [
                        'customer_id' => $customer->id,
                        'owner_name' => $customerOwner['owner_name'][$key],
                        'owner_designation' => $customerOwner['owner_designation'][$key],
                        'owner_mobile' => $customerOwner['owner_mobile'][$key],
                        'owner_email' => $customerOwner['owner_email'][$key],
                        'owner_dob' => $customerOwner['owner_dob'][$key],
                    ];
                    if (isset($customer->customerOwner[$key])) {
                        $customer->customerOwner[$key]->update($ownerData);
                    } else {
                        CustomerOwner::create($ownerData);
                    }
                }
            }
        }
        return $result;
    }

    public function delete(Customer $customer)
    {
        $customer->customerShippingAddress()->delete();
        $customer->customerSetting()->delete();
        $customer->customerOwner()->delete();
        $customer->delete();
    }

    public function show($id)
    {
        return Customer::with('customerShippingAddress', 'customerOwner', 'customerType', 'area', 'customerSetting')
            ->findOrFail($id);
    }

    public function getCustomers()
    {
        return Customer::query()
            ->select('id', 'company_name as name')
            ->get();
    }

    public function countCustomer()
    {
        return Customer::count();
    }

    public function countCustomerCurrentMonth()
    {
        return Customer::query()->whereMonth('created_at', Carbon::now()->month)->count();
    }


    public function countCustomerPreviousMonth()
    {
        return Customer::query()->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
    }

    public function dummyTransactionForOpeningBalance(Customer $customer)
    {
        $openingBalance = $customer->setting?->opening_balance ?? 0;

        if ($openingBalance != 0) {
            //debit
            $customerReceivable = $customer->getAccount();
            $openingBalanceAdjestmentAccount = Account::where('account_subsidiary_id', 3004)->where('name', 'Opening Balance Adjustment')->first();
            if (!$customerReceivable || !$openingBalanceAdjestmentAccount) {
                throw new \Exception('Account not found for transaction.');
            }

            //create transaction
            $customer->transactions()->create([
                'account_id' => $customerReceivable->id,
                'balance_type' => 'debit',
                'invoice_no' => $customer->company_name,
                'debit_amount' => $openingBalance,
                'credit_amount' => 0,
                'description' => "Opening Balance Adjustment #" . $customer->company_name,
                'transaction_date' => date('05-10-2021')
            ]);

            //credit
            $customer->transactions()->create([
                'account_id' => $openingBalanceAdjestmentAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => $customer->company_name,
                'debit_amount' => 0,
                'credit_amount' => $openingBalance,
                'description' => "Opening Balance Adjustment #" . $customer->company_name,
                'transaction_date' => date('05-10-2021')
            ]);
        }
    }

    public function insertFromCSV($filename)
    {
        $path = storage_path('app/public/' . $filename);
        if (!file_exists($path)) {
            throw new \Exception('File not found.');
        }

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle); // Read CSV header row

            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);

                // Lookup Area for company_place (by name)
                if (isset($data['company_place'])) {
                    $area = Area::where('area', $data['company_place'])->first();
                    $data['company_place_id'] = $area ? $area->id : null;
                }

                // Lookup Customer for customer_ref (by company_name)
                if (isset($data['customer_ref'])) {
                    $customerRef = Customer::where('company_name', $data['customer_ref'])->first();
                    $data['customer_ref_id'] = $customerRef ? $customerRef->id : null;
                }

                // Lookup CustomerType for customer_type (by name)
                if (isset($data['customer_type'])) {
                    $customerType = CustomerType::where('name', $data['customer_type'])->first();
                    if ($customerType) {
                        $data['customer_type_id'] = $customerType->id;
                    } else {
                        $code = str_pad((CustomerType::count() + 1), 4, '0', STR_PAD_LEFT);
                        $newCustomerType = CustomerType::create([
                            'name' => $data['customer_type'],
                            'code' => $code,
                            'status' => 1,
                        ]);
                        $data['customer_type_id'] = $newCustomerType->id;
                    }
                }

                // Lookup Employee for user_ref (by full_name)
                if (isset($data['user_ref'])) {
                    $employee = Employee::where('full_name', $data['user_ref'])->first();
                    $data['user_ref_id'] = $employee ? $employee->id : null;
                }

                // Prepare data for creating a Customer record.
                $customerData = [
                    'customer_id' => $data['customer_id'] ?? null,
                    'company_name' => $data['company_name'] ?? null,
                    'company_place_id' => $data['company_place_id'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                    'contact_for_sms' => $data['contact_for_sms'] ?? null,
                    'user_ref_id' => $data['user_ref_id'] ?? null,
                    'customer_ref_id' => $data['customer_ref_id'] ?? null,
                    'customer_type' => $data['customer_type_id'] ?? null,
                    'address' => $data['address'] ?? null,
                    'nid' => $data['nid'] ?? null,
                    'remarks' => $data['remarks'] ?? null,
                    'status' => 2,
                ];

                // Create the Customer record.
                $customer = Customer::create($customerData);

                // Insert shipping information if provided.
                if (!empty($data['ship_to']) && !empty($data['shipping_address'])) {
                    CustomerShippingNew::create([
                        'customer_id' => $customer->id,
                        'ship_to' => $data['ship_to'],
                        'shipping_address' => $data['shipping_address'],
                        'shipping_phone' => $data['shipping_phone'] ?? null,
                    ]);
                }

                // Insert owner information if provided.
                if (!empty($data['owner_name'])) {
                    $designations = [
                        1 => 'Director',
                        2 => 'Managing Director',
                        3 => 'Deputy Managing Director',
                    ];

                    $ownerDesignationId = array_search($data['owner_designation'] ?? '', $designations);
                    CustomerOwner::create([
                        'customer_id' => $customer->id,
                        'owner_name' => $data['owner_name'],
                        'owner_designation' => $ownerDesignationId,
                        'owner_mobile' => $data['owner_mobile'] ?? null,
                        'owner_email' => $data['owner_email'] ?? null,
                        'owner_dob' => $data['owner_dob'] ?? null,
                    ]);
                }

                // Create the Customer settings record.
                $customerSettings = CustomerSetting::create([
                    'customer_id' => $customer->id,
                    'customer_rating' => $data['customer_rating'] ?? 1,
                    'customer_status' => $data['customer_status'] ?? 1,
                    'credit_limit' => $data['credit_limit'] ?? 0,
                    'additional_credit_limit' => $data['additional_credit_limit'] ?? 0,
                    'opening_balance' => $data['opening_balance'] ?? 0,
                    'is_condition_bill' => $data['is_condition_bill'] ?? 0,
                    'minimum_condition_bill' => $data['minimum_condition_bill'] ?? 1,
                    'vat_status' => $data['vat_status'] ?? 0,
                    'is_document_return' => $data['is_document_return'] ?? 0,
                    'service_applicable' => $data['service_applicable'] ?? 0,
                    'discount_type' => $data['discount_type'] ?? 0,
                ]);

                // Handle multiple discounts (percentage-based)
                if (!empty($data['percentage_type_names']) && !empty($data['percentages'])) {
                    $percentageTypeNames = explode('|', $data['percentage_type_names']);
                    $percentages = explode('|', $data['percentages']);

                    foreach ($percentageTypeNames as $key => $typeName) {
                        if (!empty($typeName) && isset($percentages[$key]) && !empty($percentages[$key])) {
                            // Lookup Tag by name
                            $tag = Tag::where('name', $typeName)->first();
                            if ($tag) {
                                CustomerSettingDiscount::create([
                                    'customer_setting_id' => $customerSettings->id,
                                    'percentage_type' => $tag->id,
                                    'percentage' => $percentages[$key],
                                ]);
                            }
                        }
                    }
                }

                // Handle multiple fixed discounts
                if (!empty($data['product_names']) && !empty($data['sales_amounts'])) {
                    $productNames = explode('|', $data['product_names']);
                    $salesAmounts = explode('|', $data['sales_amounts']);

                    foreach ($productNames as $key => $productName) {
                        if (!empty($productName) && isset($salesAmounts[$key]) && !empty($salesAmounts[$key])) {
                            // Lookup ProductCatalog by name
                            $product = ProductCatalog::where('name', $productName)->first();
                            if ($product) {
                                CustomerSettingFixedDiscount::create([
                                    'customer_setting_id' => $customerSettings->id,
                                    'product_id' => $product->id,
                                    'sales_amounts' => $salesAmounts[$key],
                                ]);
                            }
                        }
                    }
                }

                // Handle multiple brokers
                if (!empty($data['broker_names']) && !empty($data['broker_statuses'])) {
                    $brokerNames = explode('|', $data['broker_names']);
                    $brokerStatuses = explode('|', $data['broker_statuses']);
                    foreach ($brokerNames as $key => $brokerName) {
                        if (!empty($brokerName)) {
                            // Lookup Broker by name
                            $broker = Broker::where('broker_name', $brokerName)->first();
                            if ($broker) {
                                CustomerSettingBroker::create([
                                    'customer_setting_id' => $customerSettings->id,
                                    'broker_id' => $broker->id,
                                    'broker_status' => $brokerStatuses[$key] ?? 1,
                                ]);

                                $brokerCustomerAttached = BrokerCustomerAttached::where('broker_id', $broker->id)
                                    ->where('customer_id', $customer->id)
                                    ->first();

                                if (is_null($brokerCustomerAttached)) {
                                    BrokerCustomerAttached::create([
                                        'broker_id' => $broker->id,
                                        'customer_id' => $customer->id,
                                        'status' => $brokerStatuses[$key] ?? 1,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }
    }
}
