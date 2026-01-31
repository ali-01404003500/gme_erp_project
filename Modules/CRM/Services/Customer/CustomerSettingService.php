<?php

namespace Modules\CRM\Services\Customer;


use App\Traits\S3FileHandler;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerOwner;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\CustomerSettingBroker;
use Modules\CRM\Models\Customer\CustomerSettingDiscount;
use Modules\CRM\Models\Customer\CustomerSettingFixedDiscount;
use Modules\CRM\Models\Customer\CustomerSettingSelfCommission;
use Modules\CRM\Models\Customer\CustomerShippingNew;

class CustomerSettingService
{
    use S3FileHandler;

    public function getAll(int $limit = 20)
    {
        return Customer::query()->paginate($limit);
    }

    public function customerSettingStore($request)
    {
        $customerSetting = CustomerSetting::where("customer_id", $request->customer_id)->first();
        if ($customerSetting) {
            $deleted = $customerSetting->customerSettingBrokers->whereNotIn('id', $request->customer_setting_broker_id);
            BrokerCustomerAttached::whereIn('broker_id', $deleted->pluck('broker_id'))
                ->where('customer_id', $request->customer_id)
                ->delete();
        }
    
        if ($customerSetting) {
            // Delete existing related records
            CustomerSettingDiscount::where('customer_setting_id', $customerSetting->id)->delete();
            CustomerSettingFixedDiscount::where('customer_setting_id', $customerSetting->id)->delete();
            CustomerSettingSelfCommission::where('customer_setting_id', $customerSetting->id)->delete();
            CustomerSettingBroker::where('customer_setting_id', $customerSetting->id)->delete();
        }
    
        CustomerSetting::where("customer_id", $request->customer_id)->delete();
    
        $discount_type = $request->combined_discount_type && $request->discount_type != 0 
                         ? $request->combined_discount_type 
                         : $request->discount_type;
    
        // Create new customer setting
        $customerSettings = CustomerSetting::create([
            'customer_id' => $request->customer_id,
            'customer_rating' => $request->customer_rating,
            'customer_status' => $request->customer_status,
            'credit_limit' => $request->credit_limit,
            'additional_credit_limit' => $request->additional_credit_limit,
            'opening_balance' => $request->opening_balance,
            'is_condition_bill' => $request->is_condition_bill,
            'minimum_condition_bill' => $request->minimum_condition_bill,
            'vat_status' => $request->vat_status,
            'is_document_return' => $request->is_document_return,
            'service_applicable' => $request->service_applicable,
            'discount_type' => $discount_type ?? 0,
        ]);
    
        if ($discount_type != 0) {
            foreach ($request->percentage_type ?? [] as $key => $value) {
                if (!is_null($value)) {
                    CustomerSettingDiscount::create([
                        'customer_setting_id' => $customerSettings->id,
                        'percentage_type' => $value,
                        'percentage' => $request->percentage[$key] ?? null,
                    ]);
                }
            }
    
            foreach ($request->self_commission_percentage_type ?? [] as $key => $value) {
                if (!is_null($value)) {
                    CustomerSettingSelfCommission::create([
                        'customer_setting_id' => $customerSettings->id,
                        'percentage_type' => $value,
                        'commission_deduct' => $request->commission_deduct ?? null,
                        'self_commission_percentage' => $request->self_commission_percentage[$key] ?? null,
                    ]);
                }
            }
        }
    
        foreach ($request->product_ids ?? [] as $key => $value) {
            if (!is_null($value)) {
                CustomerSettingFixedDiscount::create([
                    'customer_setting_id' => $customerSettings->id,
                    'product_id' => $value,
                    'sales_amounts' => $request->sales_amounts[$key],
                ]);
            }
        }
    
      

       
        foreach ($request->broker_id ?? [] as $key => $value) {
            if (!is_null($value)) {
                CustomerSettingBroker::create([
                    'customer_setting_id' => $customerSettings->id,
                    'broker_id' => $value,
                    'broker_status' => $request->broker_status[$key],
                ]);
    
                $brokerCustomerAttached = BrokerCustomerAttached::where('broker_id', $value)
                    ->where('customer_id', $request->customer_id)
                    ->first();
    
                if (is_null($brokerCustomerAttached)) {
                    BrokerCustomerAttached::create([
                        'broker_id' => $value,
                        'customer_id' => $request->customer_id,
                        'status' => $request->broker_status[$key],
                    ]);
                }
            }
        }
    
        return ['customerSettings' => $customerSettings];
    }
    

    public function update(Customer $customer, array $data, array $customerShipping, array $customerOwner)
    {
        if (isset($data['profile_picture'])) {
            $data['profile_picture'] = $this->uploadFile($data['profile_picture']);
        }
        if (isset($data['logo'])) {
            $data['logo'] = $this->uploadFile($data['logo']);
        }
        if (isset($data['front_image'])) {
            $data['front_image'] = $this->uploadFile($data['front_image']);
        }
        if (isset($data['back_image'])) {
            $data['back_image'] = $this->uploadFile($data['back_image']);
        }
        if (isset($data['visiting_card_front'])) {
            $data['visiting_card_front'] = $this->uploadFile($data['visiting_card_front']);
        }
        if (isset($data['visiting_card_back'])) {
            $data['visiting_card_back'] = $this->uploadFile($data['visiting_card_back']);
        }
        if (isset($data['trade_license'])) {
            $data['trade_license'] = $this->uploadFile($data['trade_license']);
        }
        if (isset($data['signature'])) {
            $data['signature'] = $this->uploadFile($data['signature']);
        }

        $customer->update($data);

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
                if (isset($customer->customerShipping[$key])) {
                    $customer->customerShipping[$key]->update($shippingData);
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
        return $customer;
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
    }

    public function show($id)
    {
        return Customer::findOrFail($id);
    }
}
