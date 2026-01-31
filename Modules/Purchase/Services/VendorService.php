<?php

namespace Modules\Purchase\Services;

use App\Traits\S3FileHandler;
use Modules\CRM\Models\Customer\Customer;
use Modules\Purchase\Models\Vendor;

class VendorService
{
    use S3FileHandler;

    
    public function getAll(int $limit = 20) {
        return Vendor::query()
        ->likeSearch('phone')
        ->likeSearch('address')
        ->likeSearch('company_name')
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        // $files = [
        //     'front_image',
        //     'back_image',
        //     'visiting_card_front',
        //     'trade_license',
        //     'signature',
        // ];
        // foreach ($files as $file) {
        //     if (isset($data[$file])) {
        //         $data[$file] = $this->uploadFile($data[$file]);
        //     }
        // }
        return Vendor::create($data);
    }

    public function update(Vendor $vendor, array $data)
    {
        // $files = [
        //     'front_image',
        //     'back_image',
        //     'visiting_card_front',
        //     'trade_license',
        //     'signature',
        // ];

        // foreach ($files as $file) {
        //     if (isset($data[$file])) {
        //         $data[$file] = $this->uploadFile($data[$file]);
        //     }
        // }

        $vendor->update($data);
        return $vendor;
    }

    public function delete(Vendor $vendor)
    {
        $vendor->delete();
    }

    public function show($id)
    {
        return Vendor::findOrFail($id);
    }

    public function insertFromCSV($filename) {
        $path = storage_path('app/public/' . $filename);
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
    
        $accountHeadMap = [
            'Cash' => 1,
            'Bank' => 2,
            'Purchase' => 3,
        ];
    
        $companyTypeMap = [
            'Private Limited' => 1,
            'Proprietorship' => 2,
            'Public Limited' => 3,
            'Government Organisation' => 4,
            'None' => 5,
        ];
    
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
    
            Vendor::create([
                'company_name' => $data['company_name'],
                'account_head_id' => $accountHeadMap[$data['account_head']] ?? null,
                'company_type_id' => $companyTypeMap[$data['company_type']] ?? null,
                'phone' => $data['phone'],
                'opening_balance' => $data['opening_balance'] ?? 0,
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'owner_name' => $data['owner_name'] ?? null,
                'owner_designation' => $data['owner_designation'] ?? null,
                'owner_mobile' => $data['owner_mobile'] ?? null,
                'owner_email' => $data['owner_email'] ?? null,
                'owner_dob' => $data['owner_dob'] ?? null,
                'owner_address' => $data['owner_address'] ?? null,
                'nid' => $data['nid']
            ]);
        }
        fclose($file);
    }
}
