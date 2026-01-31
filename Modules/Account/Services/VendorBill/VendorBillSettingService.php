<?php

namespace Modules\Account\Services\VendorBill;

use Modules\Account\Models\VendorBill\VendorBillSetting;

class VendorBillSettingService
{
    
    public function getAll(int $limit = 20) {
        return VendorBillSetting::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return VendorBillSetting::create($data);
    }

    public function update(VendorBillSetting $vendorBillSetting, array $data)
    {
        $vendorBillSetting->update($data);
        return $vendorBillSetting;
    }

    public function delete(VendorBillSetting $vendorBillSetting)
    {
        $vendorBillSetting->delete();
    }

    public function show($id)
    {
        return VendorBillSetting::with('billFor')->findOrFail($id);
    }
}
