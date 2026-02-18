<?php

namespace Modules\Account\Services\Payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\Account\Models\Transaction;
use Modules\Account\Models\Account;
use Modules\HRMS\Models\Employee;

class PettyCashPaymentService
{
    /**
     * Create Step 1 Journal Entry when bill is finally approved
     * Dr. Petty Cash Payable, Cr. Employee Cash
     */
    public function createStep1JournalEntry($billId)
    {
        DB::beginTransaction();

        try {
            $bill = BillsAndAllowance::with(['employee', 'transportExpenses', 'generalExpenses'])
                ->findOrFail($billId);

            $employee = $bill->employee;

            // Get or create necessary accounts using Employee model methods
            $employeeCashAccount = $employee->getAccount();
            $pettyCashPayableAccount = $employee->getPettyCashPayableAccount();

            // Calculate total approved amount
            $totalAmount = $bill->transportExpenses->sum('final_approved_amount') +
                $bill->generalExpenses->sum('final_approved_amount');

            // Generate invoice number
            $invoiceNo = $this->generateInvoiceNumber();

            // Create Step 1 journal entry
            $this->createJournalEntry(
                transactionableType: BillsAndAllowance::class,
                transactionableId: $bill->id,
                invoiceNo: $invoiceNo,
                description: "Petty Cash Payable - {$employee->full_name} (Bill #{$bill->id}) - Final Approved",
                entries: [
                    [
                        'account_id' => $pettyCashPayableAccount->id,
                        'balance_type' => 'debit',
                        'debit_amount' => $totalAmount,
                        'credit_amount' => 0,
                        'description' => 'Petty cash payable recognized upon final approval'
                    ],
                    [
                        'account_id' => $employeeCashAccount->id,
                        'balance_type' => 'credit',
                        'debit_amount' => 0,
                        'credit_amount' => $totalAmount,
                        'description' => 'Employee cash credited for approved petty cash'
                    ]
                ]
            );

            DB::commit();

            return [
                'success' => true,
                'invoice_no' => $invoiceNo,
                'total_amount' => $totalAmount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all approved bills waiting for payment
     */
    public function getApprovedForPayment(Request $request)
    {   
 
        return BillsAndAllowance::query()
            ->with([
                'employee',
                'transportExpenses.transportType',
                'generalExpenses.expenseType',
                'finalApprovedBy',
                'createdBy'
            ])
            ->where('status', 'approved')
            ->when($request->filled('employee_id'), function ($query) use ($request) {
                $query->where('employee_id', $request->employee_id);
            })
            ->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
                $query->whereBetween('date_of_bill_claim', [$request->from, $request->to]);
            })
            ->orderBy('final_approved_date', 'desc')
            ->get()
            ->groupBy('employee_id');
    }

    /**
     * Get details for payment processing
     */
    public function getDetailsForPayment($ids)
    {
        
        return BillsAndAllowance::with([
            'employee',
            'transportExpenses.transportType',
            'generalExpenses.expenseType',
            'createdBy',
            'checkedByTeamLeader',
            'checkedByAccounts',
            'finalApprovedBy'
        ])
        ->whereIn('id', $ids)
        ->get();
 
    }

    /**
     * Show payment receipt
     */
    public function show($id)
    {
        return BillsAndAllowance::with([
            'employee',
            'transportExpenses.transportType',
            'generalExpenses.expenseType',
            'createdBy',
            'checkedByTeamLeader',
            'checkedByAccounts',
            'finalApprovedBy',
            'paymentBy'
        ])->findOrFail($id);
    }

    /**
     * Process petty cash payment with journal entries
     * 
     * Step 2A: Dr. Expense Accounts, Cr. Petty Cash Payable
     * Step 2B: Dr. Employee Cash, Cr. Login Employee Cash
     * 
     * Note: Step 1 is already created during final approval
     */

    public function processPayment($ids, array $data)
    { 
        DB::beginTransaction();

        try {
                $bills = BillsAndAllowance::with(['employee','transportExpenses','generalExpenses'])
                    ->whereIn('id', $ids)
                    ->get();

                foreach($bills as $bill) { 

                    // Validate bill status
                    if ($bill->status !== 'approved') {
                        throw new \Exception('Only approved bills can be paid');
                    }

                    $employee = $bill->employee;
                    $loginUser = Auth::user();

                    // Get or create necessary accounts using Employee model methods
                    $employeeCashAccount = $employee->getAccount();
                    $pettyCashPayableAccount = $employee->getPettyCashPayableAccount();
                

                    // Get payment account from data OR use login user account as fallback
                    $loginUserCashAccount = null;
                    $paymentAccountId = $data['payment_account_id'] ?? null;
                    
                    if ($paymentAccountId) {
                        // Use specified payment account from JSON
                        $loginUserCashAccount = Account::findOrFail($paymentAccountId);
                        $paymentSource = " from {$loginUserCashAccount->name}";
                    } else {
                         
                        // Fallback to login user account
                        $loginUser = Employee::where('user_id', $loginUser->id)->first();
                        
                        if ($loginUser) {
                            $loginUserCashAccount = $loginUser->getAccount();
                            $paymentSource = " by {$loginUser->full_name}";
                        } else {
                            throw new \Exception("Neither payment account specified nor login user has employee account");
                        }
                    }
        
                    // Calculate total approved amount
                    $totalAmount = $bill->transportExpenses->sum('final_approved_amount') +
                    $bill->generalExpenses->sum('final_approved_amount');

                    // Generate invoice numbers for Step 2A and 2B
                    // FIXED: Generate base invoice number once, then increment for Step 2B
                    $invoiceNo2A = $this->generateInvoiceNumber();
                    $invoiceNo2B = $this->incrementInvoiceNumber($invoiceNo2A);

                    // STEP 2A: Recognize expenses and clear payable
                    $expenseEntries = [];

                    // Process transport expenses
                    foreach ($bill->transportExpenses as $expense) {
                        $accountHeadId = $data['account_heads']['transport_' . $expense->id] ?? null;

                        if (!$accountHeadId) {
                            throw new \Exception("Account head not selected for transport expense #{$expense->id}");
                        }

                        $expenseEntries[] = [
                            'account_id' => $accountHeadId,
                            'balance_type' => 'debit',
                            'debit_amount' => $expense->final_approved_amount,
                            'credit_amount' => 0,
                            'description' => "Transport: {$expense->expense_description}"
                        ];

                        // Store account head for future reference
                        $expense->update(['account_head_id' => $accountHeadId]);
                    }

                    // Process general expenses
                    foreach ($bill->generalExpenses as $expense) {
                        $accountHeadId = $data['account_heads']['general_' . $expense->id] ?? null;

                        if (!$accountHeadId) {
                            throw new \Exception("Account head not selected for general expense #{$expense->id}");
                        }

                        $expenseEntries[] = [
                            'account_id' => $accountHeadId,
                            'balance_type' => 'debit',
                            'debit_amount' => $expense->final_approved_amount,
                            'credit_amount' => 0,
                            'description' => "General: {$expense->expense_description}"
                        ];

                        // Store account head for future reference
                        $expense->update(['account_head_id' => $accountHeadId]);
                    }

                    // Add credit to petty cash payable
                    $expenseEntries[] = [
                        'account_id' => $pettyCashPayableAccount->id,
                        'balance_type' => 'credit',
                        'debit_amount' => 0,
                        'credit_amount' => $totalAmount,
                        'description' => 'Petty cash payable cleared'
                    ];

                    // Create Step 2A journal entry
                    $this->createJournalEntry(
                        transactionableType: BillsAndAllowance::class,
                        transactionableId: $bill->id,
                        invoiceNo: $invoiceNo2A,
                        description: "Expense Recognition - {$employee->full_name} (Bill #{$bill->id})",
                        entries: $expenseEntries
                    );

                    // STEP 2B: Payment from login user to employee
                    $this->createJournalEntry(
                        transactionableType: BillsAndAllowance::class,
                        transactionableId: $bill->id,
                        invoiceNo: $invoiceNo2B,
                        description: "Petty Cash Payment to {$employee->full_name} (Bill #{$bill->id})",
                        entries: [
                            [
                                'account_id' => $employeeCashAccount->id,
                                'balance_type' => 'debit',
                                'debit_amount' => $totalAmount,
                                'credit_amount' => 0,
                                'description' => 'Cash received by employee'
                            ],
                            [
                                'account_id' => $loginUserCashAccount->id,
                                'balance_type' => 'credit',
                                'debit_amount' => 0,
                                'credit_amount' => $totalAmount,
                                'description' => "Cash paid {$paymentSource}"
                            ]
                        ]
                    );


                    // Update bill status
                    $bill->update([
                        'status' => 'paid',
                        'payment_by' => auth()->user()->id,
                        'payment_date' => now(),
                    ]);
                    
                    DB::commit();

                    return [
                        'success' => true,
                        'bill' => $bill,
                        'total_amount' => $totalAmount,
                        'invoice_no_step2a' => $invoiceNo2A,
                        'invoice_no_step2b' => $invoiceNo2B
                    ];
                } 
            } 
            catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
    }
    public function processPaymentForImport($id, array $data)
    {
        DB::beginTransaction();

        try {
            $bill = BillsAndAllowance::with(['employee', 'transportExpenses', 'generalExpenses'])
                ->findOrFail($id);

            // Validate bill status
            if ($bill->status !== 'approved') {
                throw new \Exception('Only approved bills can be paid');
            }

            $employee = $bill->employee;
            $loginUser = Auth::user();

            // Get or create necessary accounts
            $employeeCashAccount = $employee->getAccount();
            $pettyCashPayableAccount = $employee->getPettyCashPayableAccount();
            
            // Get payment account from data OR use login user account as fallback
            $paymentAccount = null;
            $paymentAccountId = $data['payment_account_id'] ?? null;
            
            if ($paymentAccountId) {
                // Use specified payment account from JSON
                $paymentAccount = Account::findOrFail($paymentAccountId);
                $paymentSource = "from {$paymentAccount->name}";
            } else {
                // Fallback to login user account
                $loginEmployee = Employee::where('user_id', $loginUser->id)->first();
                
                if ($loginEmployee) {
                    $paymentAccount = $loginEmployee->getAccount();
                    $paymentSource = "by {$loginEmployee->full_name}";
                } else {
                    throw new \Exception("Neither payment account specified nor login user has employee account");
                }
            }

            // Calculate total approved amount
            $totalAmount = $bill->transportExpenses->sum('final_approved_amount') +
                $bill->generalExpenses->sum('final_approved_amount');

            // Generate invoice numbers for Step 2A and 2B
            $invoiceNo2A = $this->generateInvoiceNumber();
            $invoiceNo2B = $this->incrementInvoiceNumber($invoiceNo2A);

            // STEP 2A: Recognize expenses and clear payable
            $expenseEntries = [];

            // Process transport expenses
            foreach ($bill->transportExpenses as $expense) {
                $accountHeadId = $data['account_heads']['transport_' . $expense->id] ?? null;

                if (!$accountHeadId) {
                    throw new \Exception("Account head not selected for transport expense #{$expense->id}");
                }

                $expenseEntries[] = [
                    'account_id' => $accountHeadId,
                    'balance_type' => 'debit',
                    'debit_amount' => $expense->final_approved_amount,
                    'credit_amount' => 0,
                    'description' => "Transport: {$expense->expense_description}"
                ];

                // Store account head for future reference
                $expense->update(['account_head_id' => $accountHeadId]);
            }

            // Process general expenses
            foreach ($bill->generalExpenses as $expense) {
                $accountHeadId = $data['account_heads']['general_' . $expense->id] ?? null;

                if (!$accountHeadId) {
                    throw new \Exception("Account head not selected for general expense #{$expense->id}");
                }

                $expenseEntries[] = [
                    'account_id' => $accountHeadId,
                    'balance_type' => 'debit',
                    'debit_amount' => $expense->final_approved_amount,
                    'credit_amount' => 0,
                    'description' => "General: {$expense->expense_description}"
                ];

                // Store account head for future reference
                $expense->update(['account_head_id' => $accountHeadId]);
            }

            // Add credit to petty cash payable
            $expenseEntries[] = [
                'account_id' => $pettyCashPayableAccount->id,
                'balance_type' => 'credit',
                'debit_amount' => 0,
                'credit_amount' => $totalAmount,
                'description' => 'Petty cash payable cleared'
            ];

            // Create Step 2A journal entry
            $this->createJournalEntry(
                transactionableType: BillsAndAllowance::class,
                transactionableId: $bill->id,
                invoiceNo: $invoiceNo2A,
                description: "Expense Recognition - {$employee->full_name} (Bill #{$bill->id})",
                entries: $expenseEntries
            );

            // STEP 2B: Payment from specified account or login user to employee
            $this->createJournalEntry(
                transactionableType: BillsAndAllowance::class,
                transactionableId: $bill->id,
                invoiceNo: $invoiceNo2B,
                description: "Petty Cash Payment to {$employee->full_name} (Bill #{$bill->id})",
                entries: [
                    [
                        'account_id' => $employeeCashAccount->id,
                        'balance_type' => 'debit',
                        'debit_amount' => $totalAmount,
                        'credit_amount' => 0,
                        'description' => 'Cash received by employee'
                    ],
                    [
                        'account_id' => $paymentAccount->id,
                        'balance_type' => 'credit',
                        'debit_amount' => 0,
                        'credit_amount' => $totalAmount,
                        'description' => "Cash paid {$paymentSource}"
                    ]
                ]
            );

            // Update bill status
            $bill->update([
                'status' => 'paid',
                'payment_by' => $loginUser->id ?? null,
                'payment_date' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'bill' => $bill,
                'total_amount' => $totalAmount,
                'invoice_no_step2a' => $invoiceNo2A,
                'invoice_no_step2b' => $invoiceNo2B
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Increment invoice number
     */
    private function incrementInvoiceNumber(string $invoiceNo): string
    {
        // Extract the numeric part from the end (last 4 digits)
        $lastNumber = intval(substr($invoiceNo, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Replace the last 4 digits with the incremented number
        return substr($invoiceNo, 0, -4) . $newNumber;
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'PCB'; // Petty Cash Bill
        $date = date('Ymd');
        $lastTransaction = Transaction::where('invoice_no', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_no', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->invoice_no, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    /**
     * Create journal entry with multiple transaction lines
     */
    private function createJournalEntry(
        string $transactionableType,
        int $transactionableId,
        string $invoiceNo,
        string $description,
        array $entries
    ) {
        foreach ($entries as $entry) {
            Transaction::create([
                'transactionable_type' => $transactionableType,
                'transactionable_id' => $transactionableId,
                'account_id' => $entry['account_id'],
                'balance_type' => $entry['balance_type'],
                'invoice_no' => $invoiceNo,
                'debit_amount' => $entry['debit_amount'],
                'credit_amount' => $entry['credit_amount'],
                'description' => $entry['description'] ?? $description
            ]);
        }

        return $invoiceNo;
    }

    /**
     * Generate unique invoice number
     */
    // private function generateInvoiceNumber(): string
    // {
    //     $prefix = 'PCB'; // Petty Cash Bill
    //     $date = date('Ymd');
    //     $lastTransaction = Transaction::where('invoice_no', 'like', "{$prefix}{$date}%")
    //         ->orderBy('invoice_no', 'desc')
    //         ->first();

    //     if ($lastTransaction) {
    //         $lastNumber = intval(substr($lastTransaction->invoice_no, -4));
    //         $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    //     } else {
    //         $newNumber = '0001';
    //     }

    //     return "{$prefix}{$date}{$newNumber}";
    // }
}