<?php

namespace Modules\Account\Models\VendorBill;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Purchase\Models\Vendor;

class VendorBillSetting extends BaseModel
{
    use HasFactory, AutoCreateUpdateAndHistory, SoftDeletes;

    protected $guarded = [];

    // Polymorphic Relationship: Bill For
    public function billFor()
    {
        return $this->morphTo('bill_for');
    }

    // Optional: Accessor for display name
    public function getRelatedNameAttribute()
    {
        return $this->billFor?->name ?? $this->billFor?->title ?? '—';
    }

    // Method to get related name (for forms)
    public function getRelatedName()
    {
        // Try to access the relationship directly, and if not loaded, try to load it
        $billFor = $this->billFor;
        if (!$billFor && $this->bill_for_id && $this->bill_for_type) {
            // If relationship is not loaded, try to get it manually
            $billFor = $this->billFor()->first();
        }

        return $billFor?->company_name ?? $billFor?->name ?? $billFor?->title ?? 'Unknown';
    }


}
