<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\Settings\Tag;
use Modules\Inventory\Models\Settings\Unit;
use App\Traits\AutoCreatedUpdated;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Purchase\Models\RequisitionReceiveSerial;
use Modules\Purchase\Models\RequisitionReceiveBatch;

class ProductCatalog extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'image_uploads' => 'array',
    ];

    public $deletePrevent = ['barcodes', 'product', 'stocks'];

    public function barcodes()
    {
        return $this->hasMany(ProductCatalogBarcode::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'product_tag_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'product_brand_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_type_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'product_catalog_id');
    }

    public function productSetting()
    {
        return $this->hasOne(Product::class, 'product_catalog_id');
    }

    public function productName()
    {
        return $this->name . '. Model: ' . $this->model . '. Brand: ' . optional($this->brand)->name;
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function getIsSerialProductAttribute()
    {
        return $this->is_serial == 'yes' ? true : false;
    }

    public function accounts()
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function createAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 4001)->first() != null) {
            return;
        }
        $this->accounts()->create([
            'name' => 'Sales Revenue – ' . $this->name,
            'account_number' => '4001' . $this->id,
            'account_group_id' => 4,
            'account_control_id' => 4000,
            'account_subsidiary_id' => 4001,
            'opening_balance' => '0.00',
            'remarks' => 'A Sales Income account is created for ' . $this->name,
            'is_deletable' => 0,
        ]);
    }

    public function getAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 4001)->first() == null) {
            $this->createAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 4001)->first();
    }

    public function createInventoryAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1007)->first() != null) {
            return;
        }
        $this->accounts()->create([
            'name' => 'Inventory – ' . $this->name,
            'account_number' => '1007' . $this->id,
            'account_group_id' => 1,
            'account_control_id' => 1000,
            'account_subsidiary_id' => 1007,
            'opening_balance' => '0.00',
            'remarks' => 'An Inventory account is created for ' . $this->name,
            'is_deletable' => 0,
        ]);
    }

    public function getInventoryAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1007)->first() == null) {
            $this->createInventoryAccount();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 1007)->first();
    }

    public function createAccountForSalesReturnsAndAllowances()
    {
        if ($this->accounts->where('account_subsidiary_id', 4003)->first() != null) {
            return;
        }
        $this->accounts()->create([
            'name' => 'Sales Returns & Allowances – ' . $this->name,
            'account_number' => '4003' . $this->id,
            'account_group_id' => 4,
            'account_control_id' => 4000,
            'account_subsidiary_id' => 4003,
            'opening_balance' => '0.00',
            'remarks' => 'A Sales Returns & Allowances account is created for ' . $this->name,
            'is_deletable' => 0,
        ]);
    }

    public function getAccountForSalesReturnsAndAllowances()
    {
        if ($this->accounts->where('account_subsidiary_id', 4003)->first() == null) {
            $this->createAccountForSalesReturnsAndAllowances();
            $this->load('accounts'); // Reload relationship to reflect new creation
        }
        return $this->accounts->where('account_subsidiary_id', 4003)->first();
    }
    protected $withoutModelSuffix = false;

    public function withoutModelSuffix($value = true)
    {
        $this->withoutModelSuffix = $value;
        return $this;
    }


    protected $withoutBrandSuffix = false;

    public function withoutBrandSuffix($value = true)
    {
        $this->withoutBrandSuffix = $value;
        return $this;
    }

    public function getNameAttribute($value)
    {
        $model = $this->attributes['model'] ?? null;
        $brand = optional($this->brand)->name ?? null;

        if ($this->withoutModelSuffix || !$model) {
            return $value;
        }

        if ($this->withoutBrandSuffix || !$brand) {
           return "{$value} Model: {$model}";
        }

        return "{$value} Model: {$model} Brand: {$brand}";
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the price of the product by serial number or lot number
     *
     * @param string|array|null $serialNo The serial number(s) to search fo
     * @return float|null The price of the product
     */
    public function getLandedPrice($serialOrlotNo = null)
    {
        if ($this->is_serial_product ) {
            // For serial products, get price from RequisitionReceiveSerial
            $price = RequisitionReceiveSerial::whereIn('serial_no', is_array($serialOrlotNo) ? $serialOrlotNo : [$serialOrlotNo])
                ->get()
                ->pluck('requisition.requisitionDetails')
                ->flatten()
                ->where('product_id', $this->id)
                ->first()?->price;
        } elseif (!$this->is_serial_product) {
            // For non-serial products (batch products), get price from RequisitionReceiveBatch
            $price = RequisitionReceiveBatch::with('requisition.requisitionDetails')
                ->whereIn('lot_no', is_array($serialOrlotNo) ? $serialOrlotNo : [$serialOrlotNo])
                ->get()
                ->pluck('requisition.requisitionDetails')
                ->flatten()
                ->where('product_id', $this->id)
                ->first()?->price;
        } else {
            // If no appropriate parameters provided, return null
            $price = null;
        }

        return $price;
    }

}
