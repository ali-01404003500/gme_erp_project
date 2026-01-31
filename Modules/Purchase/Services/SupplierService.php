<?php

namespace Modules\Purchase\Services;

use App\Traits\S3FileHandler;
use Modules\CRM\Models\Customer\Customer;
use Modules\Purchase\Models\Supplier;

class SupplierService
{

    use S3FileHandler;

    public function getAll(int $limit = 20) {
        return Supplier::query()
        ->likeSearch('phone')
        ->likeSearch('address')
        ->likeSearch('company_name')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        // if (isset($data['profile_picture']))
        //     $data['profile_picture'] = $this->uploadFile($data['profile_picture']);
        // if (isset($data['front_image']))
        //     $data['front_image'] = $this->uploadFile($data['front_image']);
        // if (isset($data['back_image']))
        //     $data['back_image'] = $this->uploadFile($data['back_image']);
        // if (isset($data['visiting_card_front']))
        //     $data['visiting_card_front'] = $this->uploadFile($data['visiting_card_front']);
        // if (isset($data['visiting_card_back']))
        //     $data['visiting_card_back'] = $this->uploadFile($data['visiting_card_back']);
        // if (isset($data['trade_license']))
        //     $data['trade_license'] = $this->uploadFile($data['trade_license']);
        // if (isset($data['signature']))
        //     $data['signature'] = $this->uploadFile($data['signature']);
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data)
    {
        // if (isset($data['profile_picture']))
        //     $data['profile_picture'] = $this->uploadFile($data['profile_picture']);
        // if (isset($data['front_image']))
        //     $data['front_image'] = $this->uploadFile($data['front_image']);
        // if (isset($data['back_image']))
        //     $data['back_image'] = $this->uploadFile($data['back_image']);
        // if (isset($data['visiting_card_front']))
        //     $data['visiting_card_front'] = $this->uploadFile($data['visiting_card_front']);
        // if (isset($data['visiting_card_back']))
        //     $data['visiting_card_back'] = $this->uploadFile($data['visiting_card_back']);
        // if (isset($data['trade_license']))
        //     $data['trade_license'] = $this->uploadFile($data['trade_license']);
        // if (isset($data['signature']))
        //     $data['signature'] = $this->uploadFile($data['signature']);
        $supplier->update($data);
        return $supplier;
    }

    public function delete(Supplier $supplier)
    {
        $supplier->delete();
    }

    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    public function insertFromCSV($filename) {
        $path = storage_path('app/public/' . $filename);
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
    
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            
            $customer = Customer::where('company_name', $data['customer_id'])->first();
           

            Supplier::create([
                'company_name' => $data['company_name'],
                'company_place' => $data['company_place'],
                'country_code' => $data['country_code'],
                'phone' => $data['phone'],
                'tnt_number' => $data['tnt_number'],
                'opening_balance' => $data['opening_balance'],
                'email' => $data['email'],
                'contact_for_sms' => $data['contact_for_sms'],
                'customer_id' => $customer ? $customer->id : null,
                'country' => $data['country'],
                'address' => $data['address'],
                'profile_picture' => $data['profile_picture'] ?? null,
                'owner_name' => $data['owner_name'] ?? null,
                'owner_designation' => $data['owner_designation'] ?? null,
                'owner_mobile' => $data['owner_mobile'] ?? null,
                'owner_email' => $data['owner_email'] ?? null,
                'owner_dob' => $data['owner_dob'] ?? null,
                'owner_address' => $data['owner_address'] ?? null,
                'nid' => $data['nid'] ?? null,
                'front_image' => $data['front_image'] ?? null,
                'back_image' => $data['back_image'] ?? null,
                'visiting_card_front' => $data['visiting_card_front'] ?? null,
                'visiting_card_back' => $data['visiting_card_back'] ?? null,
                'trade_license' => $data['trade_license'] ?? null,
                'signature' => $data['signature'] ?? null,
                'remarks' => $data['remarks'] ?? null,
            ]);
        }
        fclose($file);
    }
}
