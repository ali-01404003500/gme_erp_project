<?php

namespace Modules\Legal\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Legal\Models\LegalBillEntry;

class LegalBillEntryService
{
    
    public function getAll(int $limit = 20) {
        return LegalBillEntry::query()
         ->when(request()->filled('from'), function ($qr) {
            $from = Carbon::parse(request('from'))->format('Y-m-d');
            $qr->whereDate("date", '>=', $from);
        })
        ->when(request()->filled('to'), function ($qr) {
            $to = Carbon::parse(request('to'))->format('Y-m-d');
            $qr->whereDate("date", '<=', $to);
        })
        ->paginate($limit);
    }
    
    public function store(array $data)
    {
        $data['bill_no'] = $this->getBillNo();

        return LegalBillEntry::create($data);
    }
    public function getBillNo()
    { 
        $today = date('Y-m-d');

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        // Count today's purchase orders created by this user
        $todayOrders = LegalBillEntry::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate PO number in required format
        $poNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-FP-%05d',
            $authUserBranch,        // Branch ID (2 digits, padded)
            $authUserBranchType,    // Branch Type (2 digits, padded)
            date('Ymd'),            // YYYYMMDD
            $authUser,              // User ID (6 digits, padded)
            $todayOrders + 1        // Count of today’s entries (5 digits, padded)
        );

        return $poNumber;
    }
    public function update(LegalBillEntry $legalBillEntry, array $data)
    {
        $legalBillEntry->update($data);
        return $legalBillEntry;
    }

    public function delete(LegalBillEntry $legalBillEntry)
    {
        $legalBillEntry->delete();
    }

    public function show($id)
    {
        return LegalBillEntry::findOrFail($id);
    }
}
