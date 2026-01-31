<?php


namespace Modules\Account\Services;


use Illuminate\Http\Request;
use Modules\Account\Models\Voucher;

class VoucherReportService
{
    public function getReportData(Request $request)
    {
        $per_page = 30;

        $data['voucherTypes'] = ['Payment', 'Receive', 'Contra', 'Journal'];

        $from_date = $request->from ?? date('Y-m-d');
        $to_date = $request->to ?? date('Y-m-d');

        $query = Voucher::query()
                            ->searchFromRelation('details', 'account_id')
                            ->searchByFields(['company_id', 'voucher_type'])
                            ->where('date', '>=', $from_date)
                            ->where('date', '<=', $to_date);

        if($request->filled('print')) {
            $data['vouchers'] = $query->get();
        }


        if(!$request->filled('print')) {


            $data['vouchers'] = $query->paginate($per_page);
            if($data['vouchers']->currentPage() == $data['vouchers']->lastPage()) {

                $data['grand_total'] = Voucher::query()
                                            ->searchFromRelation('details', 'account_id')
                                            ->searchByFields(['company_id', 'voucher_type'])
                                            ->where('date', '>=', $from_date)
                                            ->where('date', '<=', $to_date)
                                            ->sum('amount');
            }
        }
        return $data;
    }
}
