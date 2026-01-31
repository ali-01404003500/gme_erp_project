<?php


namespace Modules\Account\Services;


use Modules\Account\Models\CustomerLedger;
use Modules\Account\Models\SupplierLedger;

class LedgerService
{
    public function storeCustomerLedger($invoice_no, $amount, $date, $balance_type, $sale_id)
    {
        CustomerLedger::updateOrCreate([
            'invoice_no'    => $invoice_no,
            'balance_type'  => $balance_type,
            'sale_id'       => $sale_id,
            'customer_id'   => request('customer_id'),
        ], [
            'amount'        => $amount,
            'date'          => $date,
        ]);
    }

    public function storeSupplierLedger($invoice_no, $amount, $date, $balance_type, $purchase_id)
    {
        SupplierLedger::updateOrCreate([
            'invoice_no'    => $invoice_no,
            'balance_type'  => $balance_type,
            'purchase_id'       => $purchase_id,
            'supplier_id'   => request('supplier_id'),
        ], [
            'amount'        => $amount,
            'date'          => $date,
        ]);
    }
}
