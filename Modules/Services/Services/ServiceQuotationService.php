<?php

namespace Modules\Services\Services;

use Carbon\Carbon;
use Modules\Services\Models\ServiceQuotation;
use Modules\Services\Models\ServiceQuotationDetail;

class ServiceQuotationService
{
    
    public function getAll(int $limit = 20) {
        return ServiceQuotation::query()
        ->likeSearch('quotation_no')
        ->when(request()->filled('from'), function ($qr) {
            $qr->where('date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
        })
        ->when(request()->filled('to'), function ($qr) {
            $qr->where('date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
        })
        ->searchByFields(['customer_id'])
        ->paginate($limit);
    }
    
    public function store(array $data, array $quotationDetails)
    {
        $result['quotation'] = ServiceQuotation::create($data);

        $result['quotationDetails'] = [];
        foreach($quotationDetails['product_ids'] as $key => $productId) {
            $result['quotationDetails'][] = $result['quotation']->quotationDetails()->create([
                'product_id' => $productId,
                'quantity'=> $quotationDetails['quantity'][$key],
                'price'=> $quotationDetails['price'][$key],
                'unit_discount'=> $quotationDetails['unit_discount'][$key],
                'total_discount'=> $quotationDetails['total_discount'][$key],
                'amount'=> $quotationDetails['amount'][$key],
            ]);
        }
        return $result;
    }

    public function update(ServiceQuotation $quotation, array $data, array $quotationDetails)
    {
         $result['quotation'] = $quotation->update($data);
        $result['quotationDetails'] = [];

        ServiceQuotationDetail::where('service_quotation_id', $quotation->id)->delete();
        
        foreach($quotationDetails['product_ids'] as $key => $productId) {
            $result['quotationDetails'][] = $quotation->quotationDetails()->create([
                'product_id' => $productId,
                'quantity'=> $quotationDetails['quantity'][$key],
                'price'=> $quotationDetails['price'][$key],
                'unit_discount'=> $quotationDetails['unit_discount'][$key],
                'total_discount'=> $quotationDetails['total_discount'][$key],
                'amount'=> $quotationDetails['amount'][$key],
            ]);
        }
        return $result;
    }

    public function delete(ServiceQuotation $quotation)
    {
        $quotation->delete();
    }

    public function show($id)
    {
      $quotation = ServiceQuotation::with(['quotationDetails.product.brand.supplier','approvedBy.employee.employementDetail'])->find($id);
      return $quotation;
    }
}
