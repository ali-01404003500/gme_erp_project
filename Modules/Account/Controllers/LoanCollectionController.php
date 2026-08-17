<?php

namespace Modules\Account\Controllers;

use App\Models\Loan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\LoanPayment;

use App\Http\Controllers\Controller;
use Modules\Account\Services\Collections\CollectionService;

class LoanCollectionController extends Controller
{

    /**
     * Undocumented variable
     *
     * @var  CollectionService
     */
    private $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

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

        // Employee filter
        if ($request->filled('employee_id')) {

            // Employee selected হলে শুধু employee filter
            $query->where('employee_id', $request->employee_id);

        } else {

            // Employee selected না থাকলে month filter
            $query->whereYear('due_date', substr($month, 0, 4))
                ->whereMonth('due_date', substr($month, 5, 2));
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
 

    public function collect( Request $request, LoanPayment $loanPayment) 
    {
         


        $validated = $request->validate([
            // Loan Details
            'loan_payment_id' => 'required|exists:loan_payments,id',
            'due_amount' => 'required|numeric|min:0',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_payable_amount' => 'required|numeric',
            'payments_due_amount' => 'required|numeric|min:0',
            'payments_advance_amount' => 'required|numeric|min:0',

            // Payments (nested array validation)
            'payments' => 'required|array|min:1',
            'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.date' => 'required|date',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.attachments' => 'nullable|string',
            'payments.*.remark' => 'required|string',
        ]);



        DB::beginTransaction();

        try {    
            $receipt_no = $this->getReceiptNo();
          
          
            $loanPayment = LoanPayment::findOrFail($validated['loan_payment_id']);

      

            if ($loanPayment->status !== 'pending') {
                return back()->with(
                    'error',
                    'This installment is already submitted.'
                );

            }
 

            /*
            |--------------------------------------------------------------------------
            | Receipt No
            |--------------------------------------------------------------------------
            */

            $existingReceipts = $loanPayment->receipt_no ?? [];

            if (!is_array($existingReceipts)) {
                $existingReceipts = json_decode($existingReceipts, true) ?? [];
            }

            $existingReceipts[] = $receipt_no;

            $existingReceipts = array_values(
                array_unique($existingReceipts)
            );

            
            /*
            |--------------------------------------------------------------------------
            | Add New Payments
            |--------------------------------------------------------------------------
            */

           
            $currentPayments = new \Illuminate\Database\Eloquent\Collection();;
            $paymentAmount = 0;
            foreach ($validated['payments'] as $payment) {

                $paymentAmount += $payment['amount'];

                $newPayment = $loanPayment->payments()->create([
                    'pay_mode' => $payment['pay_mode'],
                    'bank_id' => $payment['bank_id'] ?? null,
                    'branch_id' => $payment['branch_id'] ?? null,
                    'transaction_id' => $payment['transaction_id'] ?? null,
                    'date' => $payment['date'],
                    'amount' => $payment['amount'],
                    'attachments' => $payment['attachments'] ?? null,
                    'remarks' => $payment['remark'],
                ]);

                $currentPayments->push($newPayment);
            }

            /*
            |--------------------------------------------------------------------------
            | Update Total Paid Amount
            |--------------------------------------------------------------------------
            */


            $loanPayment->update([
                'paid_amount' => $loanPayment->paid_amount + $paymentAmount, 
                'status' => 'submitted',
                'receipt_no' => $existingReceipts, 
                'updated_by' => auth()->id(),
            ]);
          
            

            /*
            |--------------------------------------------------------------------------
            | Collection
            |--------------------------------------------------------------------------
            */
            

            $collectionData = [
                'payments_total_amount' => $validated['payments_total_amount'],
                'payments_advance_amount' => $validated['payments_advance_amount'],
                'collection_type' => 'employee',
                'collection_from' => $loanPayment->employee_id,
            ];

            $this->collectionService->storeForSales(
                $collectionData,
                $currentPayments,
                $loanPayment
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan collection submitted successfully.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function getSequence(string $prefix, string $today)
    {
        $count = LoanPayment::whereDate('created_at', $today)->count();
        return $count + 1;
    }

    public function getReceiptNo()
    {
        $authUser = auth()->user()->id;
        $today = date('Ymd');
        $prefix = 'PR-';
        $sequence = $this->getSequence($prefix, $today);
        return sprintf('%s%s-USR-%05d-%06d', $prefix, $today, $sequence, $authUser, $sequence);
    }
    
    // public function collect(  Request $request, LoanPayment $loanPayment) {

    //     $request->validate([
    //         'paid_amount' => [ 'required','numeric','min:0.01','max:' . $loanPayment->amount,],
    //         'payment_method' => ['required','string',],
    //         'reference_no' => ['nullable','string','max:255',],
    //         'payment_date' => ['required','date',],
    //         'remarks' => ['nullable','string',],
    //     ]);

    //     if ($loanPayment->status !== 'pending') {
    //         return back()->with(
    //             'error',
    //             'This installment is already submitted.'
    //         );

    //     }


    //     $loanPayment->update([
    //         'paid_amount' => $request->paid_amount,
    //         'payment_date' => $request->payment_date,
    //         'payment_method' => $request->payment_method,
    //         'reference_no' => $request->reference_no,
    //         'remarks' => $request->remarks,
    //         'status' => 'submitted',
    //         'updated_by' => auth()->id(),
    //     ]);

    //     return back()->with(
    //         'success',
    //         'Loan collection submitted successfully.'
    //     );
    // }


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