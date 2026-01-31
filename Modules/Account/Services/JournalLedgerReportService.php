<?php

namespace Modules\Account\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Models\Transaction;

class JournalLedgerReportService
{
    public function getLedger(Request $request)
    {
        $transactions = Transaction::query()
            ->with('transactionable')
            ->when($request->filled('account_id'), function ($q) use ($request) {
                $q->where('account_id', $request->account_id);
            })
            ->where('date', '>=', $request->from ?? '4520-01-01')
            ->where('date', '<=', fdate($request->to ?? today()));

        $debit = clone $transactions;
        $credit = clone $transactions;

        $data['totalDebit'] = $debit->where('amount', '<', 0)->sum('amount');
        $data['totalCredit'] = $credit->where('amount', '>', 0)->sum('amount');

        $data['transactions'] = $request->print
            ? $transactions->get()
            : $transactions->paginate(100);

        return $data;
    }

    public function getJournalReport(Request $request)
    {
        $transactions = Transaction::query()
            ->when(!request()->filled('from') && !request()->filled('to'), function ($qr) {
                $qr->whereDate('transaction_date', Carbon::today());
            })
            ->when(request()->filled('from'), function ($qr) {
                $qr->whereDate('transaction_date', '>=', request('from'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->whereDate('transaction_date', '<=', request('to'));
            })
            ->orderBy('transaction_date', 'asc')
            ->orderBy('transactionable_type', 'asc')
            ->orderBy('transactionable_id', 'asc')
            // ISSUE #1 FIX: DR entries come first, then CR entries
            ->orderByRaw('CASE WHEN debit_amount > 0 THEN 0 ELSE 1 END');

        $data['transactions'] = $request->print
            ? $transactions->get()
            : $transactions->paginate(100);

        return $data;
    }
}