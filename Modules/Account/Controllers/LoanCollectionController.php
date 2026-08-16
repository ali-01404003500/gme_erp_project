<?php

namespace Modules\Account\Controllers;

use App\Models\Loan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LoanPayment;

use App\Http\Controllers\Controller;


class LoanCollectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Collection Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $query = LoanPayment::with([
            'loan',
            'employee',
        ]);

        // Month filter
        $query->whereYear('due_date', substr($month, 0, 4))
            ->whereMonth('due_date', substr($month, 5, 2));

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query
            ->orderBy('due_date')
            ->orderBy('installment_no')
            ->get();

        $employees = Employee::where('status', '1')->get();

        $pendingAmount = LoanPayment::where('status', 'pending')
            ->whereYear('due_date', substr($month, 0, 4))
            ->whereMonth('due_date', substr($month, 5, 2))
            ->sum('amount');

        $submittedAmount = LoanPayment::where('status', 'submitted')
            ->whereYear('due_date', substr($month, 0, 4))
            ->whereMonth('due_date', substr($month, 5, 2))
            ->sum('paid_amount');

        $checkedAmount = LoanPayment::where('status', 'checked')
            ->whereYear('due_date', substr($month, 0, 4))
            ->whereMonth('due_date', substr($month, 5, 2))
            ->sum('paid_amount');

        $approvedAmount = LoanPayment::where('status', 'approved')
            ->whereYear('due_date', substr($month, 0, 4))
            ->whereMonth('due_date', substr($month, 5, 2))
            ->sum('paid_amount');

        return view(
            'Account::loan-collections.index',
            compact(
                'payments',
                'employees',
                'month',
                'pendingAmount',
                'submittedAmount',
                'checkedAmount',
                'approvedAmount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Collection
    |--------------------------------------------------------------------------
    */

    public function collect(  Request $request, LoanPayment $loanPayment) {

        $request->validate([
            'paid_amount' => [ 'required','numeric','min:0.01','max:' . $loanPayment->amount,],
            'payment_method' => ['required','string',],
            'reference_no' => ['nullable','string','max:255',],
            'payment_date' => ['required','date',],
            'remarks' => ['nullable','string',],
        ]);

        if ($loanPayment->status !== 'pending') {
            return back()->with(
                'error',
                'This installment is already submitted.'
            );

        }


        $loanPayment->update([
            'paid_amount' => $request->paid_amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference_no' => $request->reference_no,
            'remarks' => $request->remarks,
            'status' => 'submitted',
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Loan collection submitted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Checking
    |--------------------------------------------------------------------------
    */

    public function check(  LoanPayment $loanPayment  ) {
        if ($loanPayment->status !== 'submitted') {
            return back()->with(
                'error',
                'Only submitted collection can be checked.'
            );
        }

        $loanPayment->update([
            'status' => 'checked',
            'checked_by' => auth()->id(),
            'checked_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Loan collection checked successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */

    public function approve( LoanPayment $loanPayment  ) {
        if ($loanPayment->status !== 'checked') {
            return back()->with(
                'error',
                'Only checked collection can be approved.'
            );

        }


        DB::transaction(function () use ($loanPayment) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Approval
            |--------------------------------------------------------------------------
            */

            $loanPayment->refresh();
            if ($loanPayment->status !== 'checked') {
                throw new \Exception(
                    'Invalid payment status.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Approve Collection
            |--------------------------------------------------------------------------
            */

            $loanPayment->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_by' => auth()->id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Accounting Transaction
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | Transaction create করার exact fields তোমার existing
            | Transaction structure অনুযায়ী adjust করতে হবে।
            |
            |--------------------------------------------------------------------------
            */

            // $account = $loanPayment->employee->getAccount();

            // $transaction = $account->transactions()->create([
            //
            //     'balance_type' => 'credit',
            //
            //     'amount' => $loanPayment->paid_amount,
            //
            //     'invoice_no' => $loanPayment->id,
            //
            //     'transactionable_type' => LoanPayment::class,
            //
            //     'transactionable_id' => $loanPayment->id,
            //
            // ]);
            //
            // $loanPayment->update([
            //     'transaction_id' => $transaction->id,
            //     'status' => 'paid',
            // ]);
        });


        return back()->with(
            'success',
            'Loan collection approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject( Request $request,LoanPayment $loanPayment  ) {

        if (!in_array(
            $loanPayment->status,
            ['submitted', 'checked']
        )) {

            return back()->with(
                'error',
                'This collection cannot be rejected.'
            );

        }


        $loanPayment->update([
            'status' => 'rejected',
            'remarks' => $request->remarks?? $loanPayment->remarks,
            'updated_by' => auth()->id(),

        ]);


        return back()->with(
            'success',
            'Loan collection rejected.'
        );
    }
}