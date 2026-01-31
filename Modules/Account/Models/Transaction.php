<?php

namespace Modules\Account\Models;

use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Models\Payments\BrokerPayment;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\VendorBill\GeneratedVendorBill;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Payroll;
use Modules\Purchase\Models\OfficePurchase;
use Modules\Purchase\Models\PurchaseReturn;
use Modules\Purchase\Models\Requisition;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\SalesCommission;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesReturn;

class Transaction extends Model
{
    use AutoCreateUpdateAndHistory, SoftDeletes;

    protected $fillable = ['account_id', 'debit_amount', 'credit_amount', 'amount', 'balance_type', 'description', 'transaction_date', 'invoice_no', 'transactionable_type', 'transactionable_id', 'company_id', 'created_by', 'updated_by'];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $account = $transaction->account ?? $transaction->account()->first();
            if (!$account) {
                throw new \Exception("Account not found for transaction");
            }

            $accountType = $account->accountGroup->name;
            $amount = $transaction->debit_amount > 0 ? $transaction->debit_amount : $transaction->credit_amount;

            $transaction->amount = self::applySignConvention(
                $accountType,
                strtolower($transaction->balance_type),
                $amount,
            );
        });
    }

    public static function applySignConvention($accountType, $balanceType, $amount)
    {
        return match (true) {
            in_array($accountType, ['Asset', 'Assets', 'Expense', 'Expenses']) => $balanceType == 'debit' ? +$amount : -$amount,
            in_array($accountType, ['Liability', 'Liabilities', 'Equity', 'Revenue', 'Income']) => $balanceType == 'debit' ? -$amount : +$amount,
            default => throw new \Exception("Unknown account type: $accountType"),
        };
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo('transactionable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }

    /**
     * Get the transaction's date.
     *
     * @param  string|null  $value
     * @return \Illuminate\Support\Carbon
     */
    public function getTransactionDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : \Carbon\Carbon::today();
    }

    /**
     * Set the transaction's date.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setTransactionDateAttribute($value)
    {
        if ($value) {
            // Check if the original value contains time information by using regex
            // This is more reliable than comparing Carbon objects since "2023-01-01 00:00:00" would match the date-only version
            $hasTime = preg_match('/\d{4}-\d{2}-\d{2}[T\s]\d{2}:\d{2}/', $value) ||  // YYYY-MM-DD HH:MM or YYYY-MM-DDTHH:MM
                       preg_match('/\d{2}\/\d{2}\/\d{4}[\s]\d{1,2}:\d{2}/', $value) ||  // MM/DD/YYYY HH:MM
                       preg_match('/\d{2}-\d{2}-\d{4}[\s]\d{1,2}:\d{2}/', $value) ||   // MM-DD-YYYY HH:MM
                       preg_match('/\d{4}-\d{2}-\d{2}[\s]\d{1,2}:\d{2}:\d{2}/', $value) || // YYYY-MM-DD HH:MM:SS
                       preg_match('/\d{2}\/\d{2}\/\d{4}[\s]\d{1,2}:\d{2}:\d{2}/', $value);  // MM/DD/YYYY HH:MM:SS

            $parsedDate = \Carbon\Carbon::parse($value);

            // If the value contains only date (no time), add current time while preserving the date
            if (!$hasTime) {
                $currentDateTime = \Carbon\Carbon::now();
                $parsedDate->setTime($currentDateTime->hour, $currentDateTime->minute, $currentDateTime->second);
            }

            $this->attributes['transaction_date'] = $parsedDate;
        } else {
            $this->attributes['transaction_date'] = \Carbon\Carbon::today();
        }
    }

    /**
     * Get description with ID for Collection, Delivery, etc.
     */
    public function getDescription()
    {
        if ($this->transactionable_type === 'Voucher Detail') {
            return optional($this->transactionable)->particular;
        }

        if ($this->description === 'Collection Payment') {
            $collectionId = optional($this->transactionable)->collection_id ?? optional($this->transactionable)->id;
            return $this->description . ' #' . $collectionId;
        }

        if (in_array($this->transactionable_type, ['Delivery']) || stripos($this->description, 'delivery') !== false) {
            $salesOrderId = optional($this->transactionable)->sales_order_id ?? optional($this->transactionable)->order_id;
            if ($salesOrderId) {
                return 'Sales Order #' . $salesOrderId;
            }
        }

        return $this->description ?? optional($this->transactionable)->description;
    }

    public function getDescriptionWithLink()
{
    $description = $this->description ?? optional($this->transactionable)->description;
    $type = $this->transactionable_type;
    $model = $this->transactionable;

    if (!$model) {
        return e($description);
    }

    // --- Determine route name based on model type ---
    $route = match ($type) {
        Collection::class, 'InvoiceWiseCollection' => 'account.collections.collections.show',
        SalesOrder::class, 'Sale' => 'sales.sales-orders.show',
        Requisition::class, 'Purchase' => 'purchase.requisitions.show',
        'SalesReturn' => 'sales.returns.show',
        PurchaseReturn::class => 'purchase.returns.print',
        OfficePurchase::class => 'purchase.offices.show',
        MakePayment::class, 'Payment' => 'account.payments.make-payments.show',
        InvoiceWisePayment::class => 'account.payments.invoice-wise-payments.show',
        InvoiceWiseCollection::class => 'account.collections.invoice-wise-collections.show',
        Delivery::class => 'sales.deliveries.show',
        GeneratedVendorBill::class => 'account.vendor-bills.generated-vendor-bills.show',
        BillsAndAllowance::class => 'hrm.bills.show',
        SalesReturn::class => 'sales.sales-returns.show',
        default => null,
    };

    // --- Use correct id or related id ---
    $routeId = $type === 'Delivery'
        ? ($model->sales_order_id ?? $model->order_id)
        : $model->id;

    // --- Description formatting logic ---
    if ($type === 'Voucher Detail') {
        $description = optional($model)->particular;
    } elseif ($this->description === 'Collection Payment') {
        $collectionId = optional($model)->collection_id ?? optional($model)->id;
        $description = 'Collection Payment #' . $collectionId;
    } elseif (in_array($type, ['Delivery']) || stripos($this->description, 'delivery') !== false) {
        $salesOrderId = optional($model)->sales_order_id ?? optional($model)->order_id;
        if ($salesOrderId) {
            $description = 'Sales Order #' . $salesOrderId;
        }
    }

    // --- Return clickable link if route exists ---
    if ($route && $routeId) {
        $url = route($route, $routeId);
        return sprintf(
            '<a href="%s" target="_blank" class="text-primary">%s</a>',
            $url,
            e($description)
        );
    }

    return e($description);
}

    

    /**
     * Returns voucher number as clickable link
     */
    public function getClickableVoucherNo()
    {
        if (!$this->invoice_no || !$this->transactionable) {
            return $this->invoice_no ?? '';
        }

        $type = $this->transactionable_type;
        $model = $this->transactionable;
        $id = $model->id;

        $route = match ($type) {
            Collection::class, 'InvoiceWiseCollection' => 'account.collections.collections.show',
            SalesOrder::class, 'Sale' => 'sales.sales-orders.show',
            Requisition::class, 'Purchase' => 'purchase.requisitions.show',
            OfficePurchase::class => 'purchase.offices.show',
            'SalesReturn' => 'sales.returns.show',
            PurchaseReturn::class => 'purchase.returns.print',
            MakePayment::class, 'Payment' => 'account.payments.make-payments.show',
            InvoiceWisePayment::class => 'account.payments.invoice-wise-payments.show',
            InvoiceWiseCollection::class => 'account.collections.invoice-wise-collections.show',
            Delivery::class =>  'sales.deliveries.show' ,
            GeneratedVendorBill::class => 'account.vendor-bills.generated-vendor-bills.show',
            SalesReturn::class => 'sales.sales-returns.show',


            default => null,
        };

        if (!$route) {
            return $this->invoice_no;
        }

        $routeId = $type === 'Delivery'
            ? ($model->sales_order_id ?? $model->order_id)
            : $id;

        if (!$routeId) {
            return $this->invoice_no;
        }

        $url = route($route, $routeId);

        return sprintf(
            '<a href="%s" target="_blank" class="text-primary">%s</a>',
            $url,
            e($this->invoice_no)
        );
    }
}

// Register morphMap (place this in AppServiceProvider@boot or a dedicated provider)
// Relation::morphMap([
//    'collection'         => Collection::class,
//     'voucher_detail'     => VoucherDetail::class,
//     'sales_order'        => SalesOrder::class,
//     'sales_commission'   => SalesCommission::class,
//     'broker_payment'     => BrokerPayment::class,
//     'requisition'        => Requisition::class,
//     'cheque_verification'=> ChequeVerification::class,
//     'payroll'            => Payroll::class,
//     'office_purchase'    => OfficePurchase::class,
//     'delivery'           => Delivery::class,
//     'make_payment'       => MakePayment::class,
//     'invoice_payment'    => InvoiceWisePayment::class,
// ]);