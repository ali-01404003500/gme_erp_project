<?php

namespace Modules\Sales\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Models\FakeInvoice;
use Modules\Sales\Models\FakeInvoiceDetail;

class FakeInvoiceService
{
    
    public function getAll(int $limit = 20) {
        return FakeInvoice::query()
        ->searchByFields(['customer_id'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('invoice_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('invoice_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })->paginate($limit);
    }
    
    public function store(array $data, array $fakeInvoiceDetails)
    {
        $data['invoice_number'] = $this->getFakeInvoiceId($data['customer_id']);
        $result['fakeInvoice'] = FakeInvoice::create($data);
        $result['fakeInvoiceDetails'] = [];

        // Store sales order details
        foreach ($fakeInvoiceDetails['product_ids'] as $key => $productId) {
            $result['fakeInvoiceDetails'][] = $result['fakeInvoice']->details()->create([
                'product_id' => $productId,
                'quantity' => $fakeInvoiceDetails['quantity'][$key],
                'price' => $fakeInvoiceDetails['price'][$key],
                'unit_discount' => $fakeInvoiceDetails['unit_discount'][$key],
                'total_discount' => $fakeInvoiceDetails['total_discount'][$key],
                'amount' => $fakeInvoiceDetails['amount'][$key],
            ]);
        }
        return $result;

    }

     public function getFakeInvoiceId($customer_id)
    {
        $today = date('Y-m-d');

        $customer_count = FakeInvoice::whereDate(DB::raw('DATE(created_at)'), $today)->count();

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $SalesOrderToday = FakeInvoice::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate Sales Order number with the appropriate format
        $SalesOrderNumber = sprintf(
            'FSCT-%02d-SC-%02d-%s-USR-%06d-SL-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $SalesOrderToday + 1
        );

        return $SalesOrderNumber;
    }

    public function update($fakeInvoice, array $data, array $fakeInvoiceDetails)
    {
       $result['fakeInvoice'] = $fakeInvoice;
        DB::beginTransaction();

        $result['fakeInvoice']->update($data);
        $result['fakeInvoice']->details()->delete();
        $result['fakeInvoiceDetails'] = [];
        foreach ($fakeInvoiceDetails['product_ids'] as $key => $productId) {
            $result['fakeInvoiceDetails'][] = FakeInvoiceDetail::Create([
                'fake_invoice_id' => $fakeInvoice->id,
                'product_id' => $productId,
                'quantity' => $fakeInvoiceDetails['quantity'][$key],
                'price' => $fakeInvoiceDetails['price'][$key],
                'unit_discount' => $fakeInvoiceDetails['unit_discount'][$key],
                'total_discount' => $fakeInvoiceDetails['total_discount'][$key],
                'amount' => $fakeInvoiceDetails['amount'][$key],
            ]);
        }
        DB::commit();
        return $fakeInvoice;
    }

    public function delete(FakeInvoice $fakeInvoice)
    {
        $fakeInvoice->delete();
    }

    public function show($id)
    {
        return FakeInvoice::findOrFail($id);
    }
}
