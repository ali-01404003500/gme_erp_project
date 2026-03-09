<?php

namespace Modules\CRM\Services\Customer;

use App\Models\GeoLocation;
use App\Traits\S3FileHandler;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerBank;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\CRM\Models\Customer\CustomerSettingBroker;
use Modules\CRM\Models\Customer\Settings\PercentageType;
use Modules\Inventory\Models\Settings\Tag;

class BrokerService
{
    use S3FileHandler;
    protected $commissionTypeMap = [
        'N/A' => 0,
        'Percentage' => 1,
        'Fixed' => 2,
    ];
    
    protected $fixedTypeMap = [
        'Invoice Wise' => 1,
        'Monthly' => 2,
        'Yearly' => 3,
        'Festival-Eid' => 4,
        'Festival-Durga Puja' => 5,
    ];
    
    protected $bankTypeMap = [
        'Bank' => 1,
        'Bkash' => 2,
        'Nagad' => 3,
        'Rocket' => 4,
    ];
    public function getAll(int $limit = 20) {
        return Broker::query()
        ->when(request()->filled('customer_id'), function ($qr) {
            $qr->whereHas('customerAttached', function($query) {
                $query->where('customer_id', request('customer_id'));
            });
        })
        ->searchByFields(['broker_name', 'mobile', 'email'])
        ->paginate($limit);
    }
    
    public function create($request)
{
    // if(isset($request->photograph))
    // $request->photograph = $this->uploadFile($request->photograph, 'brokers/photograph');
    // if(isset($request->front_image))
    // $request->front_image = $this->uploadFile($request->front_image, 'brokers/front_image');
    // if(isset($request->back_image))
    // $request->back_image = $this->uploadFile($request->back_image, 'brokers/back_image');


   $commissionType = in_array(1, $request->commission_type ?? []) || in_array(2, $request->commission_type ?? []) ? 1 : 0;

    $broker = Broker::create([
        'broker_id' => $request->broker_id,
        'broker_name' => $request->broker_name,
        'mobile' => $request->mobile,
        'alternative_phone' => $request->alternative_phone,
        'email' => $request->email,
        'nid' => $request->nid,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'photograph' => $request->photograph ?? null,
        'front_image' => $request->front_image ?? null,
        'back_image' => $request->back_image ?? null,
        'present_address' => $request->present_address,
        'permanent_address' => $request->permanent_address,
        'commission_type' => $commissionType,
        'division_id' => $request->division_id,
        'district_id' => $request->district_id,
        'thana_id' => $request->thana_id,
        'status' =>  1,
    ]);
    // Attach customers to the broker
    if ($request->has('customer_id')) {
        foreach ($request->customer_id as $key => $customerId) {
            if ($customerId != null) {
                BrokerCustomerAttached::create([
                    'customer_id' => $customerId,
                    'broker_id' => $broker->id,
                    'status' => $request->status[$key] ?? null,
                ]);
    
                $customerSetting = CustomerSetting::where('customer_id', $customerId)->first();
                if ($customerSetting) {
                    $customerSettingBroker = CustomerSettingBroker::where('broker_id', $broker->id)
                        ->where('customer_setting_id', $customerSetting->id)
                        ->first();
    
                    if (is_null($customerSettingBroker)) {
                        CustomerSettingBroker::create([
                            'broker_id' => $broker->id,
                            'customer_setting_id' => $customerSetting->id,
                            'broker_status' => $request->status[$key] ?? null,
                        ]);  
                    }
                }
            }
        }
    }
    

    // Add bank details for the broker
    if ($request->has('bank_type')) {
        foreach ($request->bank_type as $key => $bankType) {
            if ($bankType != null) {
                BrokerBank::create([
                    'bank_type' => $bankType,
                    'bank_name' => $request->bank_name[$key] ?? null,
                    'branch_name' => $request->branch_name[$key] ?? null,
                    'account_nos' => $request->account_nos[$key] ?? null,
                    'e_tin_no' => $request->e_tin_no[$key] ?? null,
                    'routing_name' => $request->routing_name[$key] ?? null,
                    'broker_id' => $broker->id,
                ]);
            }
        }
    }

 


    // Add commission details for the broker
    if (in_array(1, $request->commission_type ?? []) && $request->has('percentage_type')){
        foreach ($request->percentage_type as $key => $percentageType) {
            if ($percentageType != null) {
                BrokerCommission::create([
                    'commission_type' => 1,
                    'broker_id' => $broker->id,
                    'percentage_type' => $percentageType,
                    'percentage' => $request->percentage[$key] ?? null,
                ]);
            }
        }
    }
    if (in_array(2, $request->commission_type ?? []) && $request->has('fixed_type')) {

        foreach ($request->fixed_type as $key => $fixedType) {
            if ($fixedType != null) {
                BrokerCommission::create([
                    'commission_type' => 2,
                    'broker_id' => $broker->id,
                    'fixed_type' => $fixedType,
                    'fixed' => $request->fixed[$key] ?? null,
                ]);
            }
        } 
    }

    return $broker;
}



public function update($request, $brokerId)
{
    // dd($request->all());
    // Retrieve the broker record to update
    $broker = Broker::findOrFail($brokerId);
 
    // if(isset($request->photograph))
    // $request->photograph = $this->uploadFile($request->photograph, 'brokers/photograph');
    // if(isset($request->front_image))
    // $request->front_image = $this->uploadFile($request->front_image, 'brokers/front_image/front_image');
    // if(isset($request->back_image))
    // $request->back_image = $this->uploadFile($request->back_image, 'brokers/front_image/back_image');

    $commissionType = in_array(1, $request->commission_type ?? []) || in_array(2, $request->commission_type ?? []) ? 1 : 0;

    $broker->update([
        'broker_id' => $request->broker_id,
        'broker_name' => $request->broker_name,
        'mobile' => $request->mobile,
        'alternative_phone' => $request->alternative_phone,
        'email' => $request->email,
        'nid' => $request->nid,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'photograph' => $request->photograph ?? null,
        'front_image' => $request->front_image ?? null,
        'back_image' => $request->back_image ?? null,
        'present_address' => $request->present_address,
        'permanent_address' => $request->permanent_address,
        'commission_type' => $commissionType,
        'division_id' => $request->division_id,
        'district_id' => $request->district_id,
        'thana_id' => $request->thana_id,
    ]);


    if ($request->has('customer_id')) {
        $customerAttached = BrokerCustomerAttached::where('broker_id', $broker->id)->get();
    
        $deleted = $customerAttached->whereNotIn('id', $request->customer_attached_id);
    
        foreach ($deleted as $deletedAttachment) {
            CustomerSettingBroker::where('broker_id', $deletedAttachment->broker_id)
                ->whereIn('customer_setting_id', CustomerSetting::where('customer_id', $deletedAttachment->customer_id)
                ->first()
                ->customerSettingBrokers->pluck('customer_setting_id'))
                ->delete();
        }
        $broker->customerAttached()->delete();
    
        foreach ($request->customer_id as $key => $customerId) {
            if ($customerId != null) {
                $brokerCustomerAttached = BrokerCustomerAttached::create([
                    'customer_id' => $customerId,
                    'broker_id' => $broker->id,
                    'status' => $request->status[$key] ?? null,
                ]);
    
                $customerSetting = CustomerSetting::where('customer_id', $customerId)->first();
    
                if ($customerSetting) {
                    $customerSettingBroker = CustomerSettingBroker::where('broker_id', $broker->id)
                        ->where('customer_setting_id', $customerSetting->id)
                        ->first();
    
                    if (is_null($customerSettingBroker)) {
                        CustomerSettingBroker::create([
                            'broker_id' => $broker->id,
                            'customer_setting_id' => $customerSetting->id,
                            'broker_status' => $request->status[$key] ?? null,
                        ]);
                    }
                }
            }
        }
          
    }
    

    // Update or add bank details for the broker
    if ($request->has('bank_type')) {
        $broker->brokerBank()->delete();

        foreach ($request->bank_type as $key => $bankType) {
            if ($bankType != null) {
                BrokerBank::create([
                    'bank_type' => $request->bank_type[$key],
                    'bank_name' => $request->bank_name[$key] ?? null,
                    'branch_name' => $request->branch_name[$key] ?? null,
                    'account_nos' => $request->account_nos[$key] ?? null,
                    'e_tin_no' => $request->e_tin_no[$key] ?? null,
                    'routing_name' => $request->routing_name[$key] ?? null,
                    'broker_id' => $broker->id,
                ]);
            }
        }
    }
 
    // Update or add commission details for the broker
    if (in_array(1, $request->commission_type ?? []) && $request->has('percentage_type')) {  
        $broker->brokerCommission()->where('commission_type', 1)->delete();

        foreach ($request->percentage_type as $key => $percentageType) {
            if ($percentageType != null) {
                BrokerCommission::create([
                    'commission_type' => '1',
                    'broker_id' => $broker->id,
                    'percentage_type' => $request->percentage_type[$key],
                    'percentage' => $request->percentage[$key] ?? 0,
                ]);
            }
        }
    } 
    if (in_array(2, $request->commission_type ?? []) ) {  
        $broker->brokerCommission()->where('commission_type', 2)->delete();

        foreach ($request->fixed_type as $key => $fixedType) { 

            if ($key == 0) {
                if ($fixedType != null && $request->fixed[$key] != 0) { 
                    BrokerCommission::create([
                        'commission_type' => '3',
                        'broker_id' => $broker->id,
                        'fixed_type' => $request->fixed_type[$key],
                        'fixed' => $request->fixed[$key] ?? 0,
                    ]);
                }
            }
            else
            {
                if ($fixedType != null && $request->fixed[$key] != 0) { 
                BrokerCommission::create([
                    'commission_type' => '2',
                    'broker_id' => $broker->id,
                    'fixed_type' => $request->fixed_type[$key],
                    'fixed' => $request->fixed[$key] ?? 0,
                ]);
            }
            }
            
        }
 
    }

    return $broker;
}


    public function delete(Broker $broker)
    {
        $broker->delete();
    }

    public function show($id)
    {
        return Broker::findOrFail($id);
    }
    public function insertFromCSV($filename) {
        $path = storage_path('app/public/' . $filename);
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
    
        $brokers = [];
    
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
    
            // Get or Create Broker
            if (!isset($brokers[$data['broker_name']])) {
                $division = GeoLocation::where('type', 'Division')->where('name', $data['division'])->first();
                $district = GeoLocation::where('type', 'District')->where('name', $data['district'])->first();
                $thana = GeoLocation::where('type', 'Thana')->where('name', $data['thana'])->first();
                $broker = Broker::create([
                    'broker_id' => $data['broker_id'],
                    'broker_name' => $data['broker_name'],
                    'mobile' => $data['mobile'],
                    'alternative_phone' => $data['alternative_phone'] ?? null,
                    'email' => $data['email'],
                    'nid' => $data['nid'],
                    'dob' => $data['dob'],
                    'gender' => $data['gender'],
                    'present_address' => $data['present_address'],
                    'permanent_address' => $data['permanent_address'],
                    'commission_type' => $this->commissionTypeMap[$data['commission_type']],
                    'division_id' => $division ? $division->id : null,
                    'district_id' => $district ? $district->id : null,
                    'thana_id' => $thana ? $thana->id : null,
                    'status' => $data['status']?? 2, // Default status to 2 if not provided
                ]);
    
                $brokers[$data['broker_name']] = $broker;
            } else {
                $broker = $brokers[$data['broker_name']];
            }
    
            // Attach Customer to Broker
            $customer = Customer::where('company_name', $data['customer_name'])->first();
            if ($customer) {
                BrokerCustomerAttached::updateOrCreate(
                    ['customer_id' => $customer->id, 'broker_id' => $broker->id],
                    ['status' => $data['customer_status'] ?? 2]
                );
    
                $customerSetting = CustomerSetting::where('customer_id', $customer->id)->first();
                if ($customerSetting) {
                    CustomerSettingBroker::updateOrCreate(
                        ['broker_id' => $broker->id, 'customer_setting_id' => $customerSetting->id],
                        ['broker_status' => $data['customer_status'] ?? 2]
                    );
                }
            }
    
            // Add Bank Details
            if (!empty($data['bank_type'])) {
                $bankTypeId = $this->bankTypeMap[$data['bank_type']] ?? null;
    
                BrokerBank::updateOrCreate(
                    ['bank_type' => $bankTypeId, 'broker_id' => $broker->id],
                    [
                        'bank_name' => $data['bank_name'] ?? null,
                        'branch_name' => $data['branch_name'] ?? null,
                        'account_nos' => $data['account_nos'] ?? null,
                        'e_tin_no' => $data['e_tin_no'] ?? null,
                        'routing_name' => $data['routing_name'] ?? null,
                    ]
                );
            }
    // dd($data,$broker);
            // Process multiple commission types
            $commissionTypeId = $this->commissionTypeMap[$data['commission_type']] ?? null;
            $commissionCount = 1;  // Start with 1 to handle the first commission type
            
            // Loop through potential commission columns dynamically
            while (isset($data["percentage_type_{$commissionCount}"]) || isset($data["fixed_type_{$commissionCount}"])) {
                // Percentage Commission
                if (!empty($data["percentage_type_{$commissionCount}"])) {
                $percentageType = Tag::where('name', $data["percentage_type_{$commissionCount}"])->first();

                if ($percentageType) {
                    BrokerCommission::updateOrCreate(
                        [
                            'broker_id' => $broker->id,
                            'commission_type' => $commissionTypeId,
                            'percentage_type' => $percentageType->id,
                        ],
                        ['percentage' => $data["percentage_{$commissionCount}"]]
                    );
                } 
            }

    
                // Fixed Commission
                if (!empty($data["fixed_type_{$commissionCount}"]) && !empty($data["fixed_{$commissionCount}"])) {
                    $fixedTypeId = $this->fixedTypeMap[$data["fixed_type_{$commissionCount}"]] ?? null;
                    BrokerCommission::updateOrCreate(
                        ['broker_id' => $broker->id, 'commission_type' => $commissionTypeId, 'fixed_type' => $fixedTypeId],
                        ['fixed' => $data["fixed_{$commissionCount}"]]
                    );
                }
    
                // Move to next commission set
                $commissionCount++;
            }
    
        }
    
        fclose($file);
    }
    
    // public function insertFromCSV($filename) {
    //     $path = storage_path('app/public/' . $filename);
    //     $file = fopen($path, 'r');
    //     $header = fgetcsv($file);
    
    //     $brokers = [];
    
    //     while ($row = fgetcsv($file)) {
    //         $data = array_combine($header, $row);
    
    //         // Get or Create Broker
    //         if (!isset($brokers[$data['broker_name']])) {
    //             $division = GeoLocation::where('type', 'Division')->where('name', $data['division'])->first();
    //             $district = GeoLocation::where('type', 'District')->where('name', $data['district'])->first();
    //             $thana = GeoLocation::where('type', 'Thana')->where('name', $data['thana'])->first();
    
    //             $broker = Broker::create([
    //                 'broker_name' => $data['broker_name'],
    //                 'mobile' => $data['mobile'],
    //                 'alternative_phone' => $data['alternative_phone'] ?? null,
    //                 'email' => $data['email'],
    //                 'nid' => $data['nid'],
    //                 'dob' => $data['dob'],
    //                 'gender' => $data['gender'],
    //                 'present_address' => $data['present_address'],
    //                 'permanent_address' => $data['permanent_address'],
    //                 'commission_type' => $this->commissionTypeMap[$data['commission_type']],
    //                 'division_id' => $division ? $division->id : null,
    //                 'district_id' => $district ? $district->id : null,
    //                 'thana_id' => $thana ? $thana->id : null,
    //             ]);
    
    //             $brokers[$data['broker_name']] = $broker;
    //         } else {
    //             $broker = $brokers[$data['broker_name']];
    //         }
    
    //         // Attach Customer to Broker
    //         $customer = Customer::where('company_name', $data['customer_name'])->first();
    //         if ($customer) {
    //             BrokerCustomerAttached::updateOrCreate(
    //                 ['customer_id' => $customer->id, 'broker_id' => $broker->id],
    //                 ['status' => $data['customer_status'] ?? null]
    //             );
    
    //             $customerSetting = CustomerSetting::where('customer_id', $customer->id)->first();
    //             if ($customerSetting) {
    //                 CustomerSettingBroker::updateOrCreate(
    //                     ['broker_id' => $broker->id, 'customer_setting_id' => $customerSetting->id],
    //                     ['broker_status' => $data['customer_status'] ?? null]
    //                 );
    //             }
    //         }
    
    //         // Add Bank Details
    //         if (!empty($data['bank_type'])) {
    //             $bankTypeId = $this->bankTypeMap[$data['bank_type']] ?? null;
    
    //             BrokerBank::updateOrCreate(
    //                 ['bank_type' => $bankTypeId, 'broker_id' => $broker->id],
    //                 [
    //                     'bank_name' => $data['bank_name'] ?? null,
    //                     'branch_name' => $data['branch_name'] ?? null,
    //                     'account_nos' => $data['account_nos'] ?? null,
    //                     'e_tin_no' => $data['e_tin_no'] ?? null,
    //                     'routing_name' => $data['routing_name'] ?? null,
    //                 ]
    //             );
    //         }
    
    //         // Add Commission Details
    //         $commissionTypeId = $this->commissionTypeMap[$data['commission_type']] ?? null;
    //         if ($commissionTypeId == 1 && !empty($data['percentage_type'])) {
    //             $percentageType = Tag::where('name', $data['percentage_type'])->first();
    //             BrokerCommission::updateOrCreate(
    //                 ['broker_id' => $broker->id, 'commission_type' => $commissionTypeId, 'percentage_type' => $percentageType->id],
    //                 ['percentage' => $data['percentage']]
    //             );
    //         } elseif ($commissionTypeId == 2 && !empty($data['fixed_type']) && !empty($data['fixed'])) {
    //             $fixedTypeId = $this->fixedTypeMap[$data['fixed_type']] ?? null;
    //             BrokerCommission::updateOrCreate(
    //                 ['broker_id' => $broker->id, 'commission_type' => $commissionTypeId, 'fixed_type' => $fixedTypeId],
    //                 ['fixed' => $data['fixed']]
    //             );
    //         }
    //     }
    
    //     fclose($file);
    // }
}
