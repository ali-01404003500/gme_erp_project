<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Services\EMIEntryService;
use Illuminate\Http\Request;
use Modules\Account\Models\EMIEntryDetail;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Services\ExportService;

class EMIEntryController extends Controller
{
    /**
     * Service variable
     *
     * @var EMIEntryService
     */
    private $service;
    function __construct(EMIEntryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['eMIEntrys'] = $this->service->getAll();

        return view('Account::emi-entries.index', $data);
    }
    public function getInvoices(Request $request)
    {
        $data = $this->service->getInvoices($request->customer_id);
        return response()->json($data);
    }

    public function storeAjax(Request $request)
    {
        $validate = $request->validate([
            'customer_id' => 'required',
            'sales_order_id' => 'nullable',
            'start_date' => 'required',
            'tenure_type' => 'required',
            'tenure_no' => 'required',
            'interest_rate' => 'nullable',
            'amount' => 'required',
            'description' => 'nullable',
        ]);
        $emiDetails = $request->validate([
            'emi_date.*' => 'required',
            'emi_amount.*' => 'required',
            'interest_amount.*' => 'required',
            'principal_amount.*' => 'required',
        ]);
        $result = $this->service->store($validate, $emiDetails);

        // Soft-delete the newly created EMI entry and its details
        if (isset($result['eMIEntry'])) {
            $result['eMIEntry']->delete(); // This will soft delete the EMIEntry
        }

        return response()->json(['success' => true, 'message' => 'EMIEntry created successfully as a draft.', 'data' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::activeCustomers()->get();
        return view('Account::emi-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer_id' => 'required',
            'sales_order_id' => 'required',
            'start_date' => 'required',
            'tenure_type' => 'required',
            'tenure_no' => 'required',
            'interest_rate' => 'nullable',
            'amount' => 'required',
            'description' => 'nullable',
        ]);
        $emiDetails = $request->validate([
            'emi_date.*' => 'required',
            'emi_amount.*' => 'required',
            'interest_amount.*' => 'required',
            'principal_amount.*' => 'required',
        ]);
        $this->service->store($validate, $emiDetails);
        return redirect()->route('account.emi-entries.index')->with('success', 'EMIEntry created successfully.');
    }

    public function rescheduleStore(Request $request)
    {
        $validate = $request->validate([
            'emi_id' => 'required|exists:e_m_i_entries,id',
            'interest_rate' => 'required|numeric|min:0',
            'schedule_date' => 'required|date',
            'tenure_type' => 'required|in:Monthly,Weekly,Quarterly,Half Yearly,Yearly',
            'tenure_no' => 'required|integer|min:1',
            'remaining_principal' => 'required|numeric|min:0',
            'remaining_interest' => 'required|numeric|min:0',
            'settlement_amount' => 'required|numeric|min:0',
        ]);

        $scheduleDetails = $request->validate([
            'schedule.*.tenure_no' => 'required|integer',
            'schedule.*.repayment_date' => 'required|date',
            'schedule.*.interest_amount' => 'required|numeric|min:0',
            'schedule.*.principal_amount' => 'required|numeric|min:0',
            'schedule.*.emi_amount' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->service->reschedule($validate, $scheduleDetails['schedule']);

            return response()->json([
                'success' => true,
                'message' => 'EMI rescheduled successfully.',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to reschedule EMI: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function collectionStore(Request $request)
    {
        $validate = $request->validate([
            // EMI Details
            'emi_detail_id' => 'required|exists:e_m_i_entry_details,id',
            'emi_amount' => 'required|numeric|min:0',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_payable_amount' => 'required|numeric|min:0',
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
        // dd($validate);

        $data = $this->service->collectionStore($validate);

        return response()->json(['success' => true, 'message' => 'EMI collected successfully.', 'data' => $data]);
    }

    public function settlementCollectionStore(Request $request)
    {
        // dd($request->all());

        $validate = $request->validate([
            'emi_id' => 'required|integer|exists:e_m_i_entries,id',
            'tenure' => 'required|integer',
            'paid_tenure' => 'required|integer',
            'due_tenure' => 'required|integer',
            'principle' => 'required|numeric|min:0',
            'remaining_principle' => 'required|numeric|min:0',
            'remaining_interest' => 'required|numeric|min:0',
            'settlement_amount' => 'required|numeric|min:0',
            'interest' => 'required|numeric|min:0',
            'interest_rate' => 'required|integer',
            'total' => 'required|numeric|min:0',
            'paid_principle' => 'required|numeric|min:0',
            'paid_interest' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'settlement_payments_total_amount' => 'required|numeric|min:0',
            'settlement_payments_payable_amount' => 'required|numeric|min:0',
            'settlement_payments_due_amount' => 'required|numeric|min:0',
            'settlement_payments_advance_amount' => 'required|numeric|min:0',
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

        $data = $this->service->settlementCollectionStore($validate);

        return response()->json(['success' => true, 'message' => 'Settlement collected successfully.', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['eMIEntry'] = $this->service->show($id);

        return view('eMIEntrys.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['emiEntry'] = EMIEntry::with('emiDetails')->findOrFail($id);
        $data['customers'] = Customer::activeCustomers()->get();

        return view('Account::emi-entries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validate = $request->validate([
            'customer_id' => 'required',
            'sales_order_id' => 'required',
            'start_date' => 'required',
            'tenure_type' => 'required',
            'tenure_no' => 'required',
            'interest_rate' => 'nullable',
            'amount' => 'required',
            'description' => 'nullable',
        ]);

        $emiDetails = $request->validate([
            'emi_date.*' => 'required|date',
            'emi_amount.*' => 'required|numeric',
            'interest_amount.*' => 'required|numeric',
            'principal_amount.*' => 'required|numeric',
        ]);

        $this->service->update($id, $validate, $emiDetails);

        return redirect()->route('account.emi-entries.index')->with('success', 'EMIEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $eMIEntry = EMIEntry::findOrFail($id);
        $this->service->delete($eMIEntry);
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'EMIEntry deleted successfully.']);
        }

        return redirect()->route('account.emi-entries.index')->with('success', 'EMIEntry deleted successfully.');
    }

    public function ajaxDestroy($id)
    {
        $eMIEntry = EMIEntry::findOrFail($id);
        $this->service->delete($eMIEntry);
        return response()->json(['success' => true, 'message' => 'EMIEntry deleted successfully.']);
    }

    public function emiCollection()
    {
        $customers = Customer::select('id','company_name', 'address', 'phone')->get();
        return view('Account::emi-entries.collection', compact('customers'));
    }

    // Customer + EMI list
    public function getCustomerEmis(Request $request)
    {
        $emis = EmiEntry::where('customer_id', $request->customer_id)->where('status', '!=', 'rescheduled')->get();
        return response()->json(['emis' => $emis]);
    }

    public function getEmiDetails(Request $request)
    {
        $emiEntry = EmiEntry::with('emiDetails', 'customer', 'salesOrder')->findOrFail($request->emi_id);

        $combined_info_html = view('Account::emi-entries.partials.combined-info', compact('emiEntry'))->render();
        $emi_schedule_html = view('Account::emi-entries.partials.emi-schedule', compact('emiEntry'))->render();

        return response()->json([
            'combined_info_html' => $combined_info_html,
            'emi_schedule_html' => $emi_schedule_html,
        ]);
    }

    public function getEarlySettlementDetails(Request $request)
    {
        $emiId = $request->emi_id;
        $emiEntry = EMIEntry::with(['customer', 'salesOrder', 'createdBy', 'emiDetails'])->findOrFail($emiId);

        $principalTotal = $emiEntry->emiDetails->sum('principal_amount');
        $interestTotal = $emiEntry->emiDetails->sum('interest_amount');
        $total = $principalTotal + $interestTotal;

        $paidDetails = $emiEntry->emiDetails->where('status', 'paid');
        $dueDetails = $emiEntry->emiDetails->where('status', 'due');

        $paidTenure = $paidDetails->count();
        $dueTenure = $dueDetails->count();
        $tenure = $emiEntry->tenure_no;

        $paidPrincipal = $paidDetails->sum('principal_amount');
        $paidInterest = $paidDetails->sum('interest_amount');

        $remainingPrincipal = $dueDetails->sum('principal_amount');
        $remainingInterest = $dueDetails->sum('interest_amount');
        $remainingAmount = $remainingPrincipal + $remainingInterest;

        $adjustInterest = $remainingInterest;
        $adjustAmount = 0;
        $settlementAmount = $remainingPrincipal + $adjustInterest - $adjustAmount;

        return response()->json([
            'tenure' => $tenure,
            'paid_tenure' => $paidTenure,
            'due_tenure' => $dueTenure,
            'principle' => number_format($principalTotal),
            'remaining_principle' => number_format($remainingPrincipal),
            'remaining_interest' => number_format($remainingInterest),
            'settlement_amount' => number_format($settlementAmount, 0),
            'interest' => number_format($interestTotal),
            'interest_rate' => $emiEntry->interest_rate,
            'adjust_interest' => number_format($adjustInterest),
            'adjust_amount' => number_format($adjustAmount, 0),
            'total' => number_format($total),
            'paid_principle' => number_format($paidPrincipal),
            'paid_interest' => number_format($paidInterest),
            'remaining_amount' => number_format($remainingAmount),
        ]);
    }

    public function getRescheduleDetails(Request $request)
    {
        $emiId = $request->emi_id;
        $emiEntry = EMIEntry::with(['customer', 'salesOrder', 'createdBy', 'emiDetails'])->findOrFail($emiId);

        $principalTotal = $emiEntry->emiDetails->sum('principal_amount');
        $interestTotal = $emiEntry->emiDetails->sum('interest_amount');
        $total = $principalTotal + $interestTotal;

        $paidDetails = $emiEntry->emiDetails->where('status', 'paid');
        $dueDetails = $emiEntry->emiDetails->where('status', 'due');

        $paidTenure = $paidDetails->count();
        $dueTenure = $dueDetails->count();
        $tenure = $emiEntry->tenure_no;

        $paidPrincipal = $paidDetails->sum('principal_amount');
        $paidInterest = $paidDetails->sum('interest_amount');

        $remainingPrincipal = $dueDetails->sum('principal_amount');
        $remainingInterest = $dueDetails->sum('interest_amount');
        $remainingAmount = $remainingPrincipal + $remainingInterest;

        // For rescheduling, we typically use the remaining principal and interest as-is
        $settlementAmount = $remainingPrincipal + $remainingInterest;

        return response()->json([
            'tenure' => $tenure,
            'paid_tenure' => $paidTenure,
            'due_tenure' => $dueTenure,
            'principle' => number_format($principalTotal),
            'remaining_principle' => number_format($remainingPrincipal),
            'remaining_interest' => number_format($remainingInterest),
            'settlement_amount' => number_format($settlementAmount),
            'interest' => number_format($interestTotal),
            'interest_rate' => $emiEntry->interest_rate,
            'total' => number_format($total),
            'paid_principle' => number_format($paidPrincipal),
            'paid_interest' => number_format($paidInterest),
            'remaining_amount' => number_format($remainingAmount),
            'original_tenure_type' => $emiEntry->tenure_type,
            'original_start_date' => $emiEntry->start_date,
        ]);
    }

    public function rollback(Request $request)
    {
        $validated = $request->validate([
            'emi_detail_id' => 'required|integer|exists:e_m_i_entry_details,id',
        ]);

        try {
            $data = $this->service->rollback($validated['emi_detail_id']);
            return response()->json([
                'success' => true,
                'message' => 'Rollback successful. EMI detail reverted to due status.',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
    }

    public function showMoneyReceipt(Request $request, $id)
    {
        $data['company_info'] = CompanyInfo::first();

        // If emi_entry_id is passed (early settlement case → entry-level receipt)
        if ($request->has('emientry_id')) {
            $data['emiEntry'] = EMIEntry::with(['emiDetails.payments', 'customer'])->findOrFail($request->emientry_id);
            $data['emiEntryDetail'] = null;
        }
        // If emi_entry_detail_id is passed (installment-level receipt)
        if ($request->has('emientrydetail_id')) {
            $data['emiEntryDetail'] = EMIEntryDetail::with(['payments', 'emiEntry.customer'])->findOrFail($request->emientrydetail_id);
            $data['emiEntry'] = null;
        }

        // PDF Export
        if ($request->export == 'pdf') {
            set_time_limit(1000);
            $html = view('Account::emi-entries.collection-view', $data)->render();

            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $fileName = 'emi-entries_' . ($data['emiEntry']->customer->company_name ?? 'receipt') . '.pdf';
            return $dompdf->stream($fileName, ['Attachment' => false]);
        }

        return view('Account::emi-entries.collection-show', $data);
    }
    /**
     * Show EMI Installment Report View
     */
    public function emiInstallmentReport()
    {
        return view('Account::emi-entries.reports.installment-reports', [
            'reportData' => collect([]),
        ]);
    }

    /**
     * Fetch EMI Report Data for AJAX
     */
    public function emiReportData(Request $request)
    {
        $month = $request->query('month');
        $filterDate = $request->query('filter_date');

        // Validate month input
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        try {
            // Parse month
            $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            // Fetch EMI details within month with distinct dates
            $emiDetails = EMIEntryDetail::with(['emiEntry.customer', 'emiEntry.salesOrder', 'payments', 'advanceChequeEntryDetail.chcqueVerification.transactions.account'])
                ->whereBetween('emi_date', [$start, $end])
                ->whereHas('emiEntry', function ($query) {
                    $query->whereNull('deleted_at'); // Exclude soft-deleted EMI entries
                })

                ->orderBy('emi_date', 'asc')
                ->get();

            // Get distinct dates with installments
            $dates = $emiDetails
                ->pluck('emi_date')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            // Structure report data
            $reportData = [];
            foreach ($emiDetails as $detail) {
                $customer = $detail->emiEntry->customer;

                // Calculate remaining balance (due installments only)
                $balance = $detail->emiEntry->emiDetails()->where('status', 'due')->sum('emi_amount');

                // Get payment details
                $chequeNo = 'N/A';
                $payDate = null;
                $payAmount = 0;
                $payStatus = $detail->status === 'paid' || $detail->status === 'early_settlement_paid' ? 'Paid' : 'Due';

                // Check for cheque payment
                if ($detail->advanceChequeEntryDetail && $detail->advanceChequeEntryDetail->chcqueVerification) {
                    $verification = $detail->advanceChequeEntryDetail->chcqueVerification;
                    if (in_array($verification->status, ['cash', 'honor-verified'])) {
                        $chequeNo = $verification->cheque_no ?? 'N/A';
                        $payDate = $verification->updated_at->format('Y-m-d');
                        $payAmount = $verification->amount;
                    }
                }

                // Check for direct payment
                if ($detail->payments && $detail->payments->count() > 0) {
                    $firstPayment = $detail->payments->first();
                    if ($firstPayment) {
                        $payDate = $firstPayment->date ?? $payDate;
                        $payAmount = $detail->payments->sum('amount') ?? $payAmount;
                    }
                }

                $emiDate = Carbon::parse($detail->emi_date)->format('Y-m-d');

                $reportData[] = [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->company_name,
                    'address' => $customer->address,
                    'phone' => $customer->phone,
                    'balance' => $balance,
                    'installment_amount' => $detail->emi_amount,
                    'cheque_no' => $chequeNo,
                    'pay_status' => $payStatus,
                    'pay_date' => $payDate,
                    'pay_amount' => $payAmount,
                    'emi_date' => $emiDate,
                ];
            }

            // If export is PDF
            if ($request->query('export') === 'pdf') {
                return $this->exportPdf($reportData, $month, $filterDate);
            }

            // Return JSON for AJAX
            return response()->json([
                'success' => true,
                'dates' => $dates,
                'data' => $reportData,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error processing report: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Export report to PDF
     */
    private function exportPdf($reportData, $month, $filterDate = null)
    {
        try {
            $companyInfo = CompanyInfo::first();

            // Format month for display
            $monthForDisplay = Carbon::createFromFormat('Y-m', $month)->format('F Y');

            // Filter data by date if provided
            $filteredData = $reportData;
            if ($filterDate) {
                $filteredData = array_filter($reportData, function ($item) use ($filterDate) {
                    return $item['emi_date'] === $filterDate;
                });
                // Re-index array
                $filteredData = array_values($filteredData);
            }

            $data = [
                'company_info' => $companyInfo,
                'report_data' => $filteredData,
                'month' => $monthForDisplay,
                'filter_date' => $filterDate,
            ];

            // Render blade view to HTML
            $html = view('Account::emi-entries.reports.installment-reports-pdf', $data)->render();

            // Configure Dompdf
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            $options->setChroot(public_path());

            // Create PDF
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Generate filename
            $dateStr = $filterDate ? '_' . str_replace('-', '', $filterDate) : '';
            $fileName = 'emi-installment-report_' . str_replace('-', '', $month) . $dateStr . '.pdf';

            return $dompdf->stream($fileName, ['Attachment' => false]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error generating PDF: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function emiCustomerWiseReport(Request $request)
{
    $customerId = $request->input('customer_id');
    $month = $request->input('month');
    
    $reportData = collect([]);
    $selectedCustomer = null;
    $totalInstallmentAmount = 0;
    $totalPaymentAmount = 0;
    
    // Fetch all active customers for dropdown
    $customers = Customer::activeCustomers()
        ->get();
    
    // Get company info
    $companyInfo = CompanyInfo::first();
    
    // If customer and month are selected, fetch report data
    if ($customerId && $month) {
        // Validate inputs
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'month' => 'required|date_format:Y-m',
        ]);
        
        try {
            // Parse month
            $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();
            
            // Get selected customer
            $selectedCustomer = Customer::findOrFail($customerId);
            
            // Fetch EMI details for the customer within the month
            $emiDetails = EMIEntryDetail::with([
                'emiEntry.customer',
                'emiEntry.salesOrder',
                'payments',
                'advanceChequeEntryDetail.chcqueVerification.transactions.account'
            ])
            ->whereBetween('emi_date', [$start, $end])
            ->whereHas('emiEntry', function ($query) use ($customerId) {
                $query->where('customer_id', $customerId)
                      ->whereNull('deleted_at');
            })
            ->orderBy('emi_date', 'asc')
            ->get();
            
            // Structure report data
            foreach ($emiDetails as $index => $detail) {
                $customer = $detail->emiEntry->customer;
                
                // Get payment details
                $payDate = null;
                $payAmount = 0;
                $payStatus = 'Due';
                
                // Check for cheque payment
                if ($detail->advanceChequeEntryDetail && $detail->advanceChequeEntryDetail->chcqueVerification) {
                    $verification = $detail->advanceChequeEntryDetail->chcqueVerification;
                    if (in_array($verification->status, ['cash', 'honor-verified'])) {
                        $payDate = $verification->updated_at->format('Y-m-d');
                        $payAmount = $verification->amount;
                        $payStatus = 'Paid';
                    }
                }
                
                // Check for direct payment
                if ($detail->payments && $detail->payments->count() > 0) {
                    if (!$payDate) {
                        $firstPayment = $detail->payments->first();
                        if ($firstPayment) {
                            $payDate = $firstPayment->date;
                            $payAmount = $detail->payments->sum('amount');
                            $payStatus = 'Paid';
                        }
                    }
                }
                
                // Check if payment was made
                if ($detail->status === 'paid' || $detail->status === 'early_settlement_paid') {
                    $payStatus = 'Paid';
                }
                
                $emiDate = Carbon::parse($detail->emi_date);
                $currentDate = Carbon::now();
                
                // Determine status color logic
                $statusColor = 'white'; // Default
                $rowColor = 'white';
                
                if ($payStatus === 'Paid') {
                    $statusColor = 'green';
                    $rowColor = 'white';
                } elseif ($emiDate > $currentDate) {
                    // Upcoming installment (not yet due)
                    $statusColor = 'red';
                    $rowColor = 'orange';
                } else {
                    // Overdue installment
                    $statusColor = 'red';
                    $rowColor = 'white';
                }
                
                $reportData->push([
                    'sl' => $index + 1,
                    'emi_no' => $detail->emiEntry->emi_number ?? 'N/A',
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->company_name,
                    'customer_address' => $customer->address,
                    'phone' => $customer->phone,
                    'emi_date' => $emiDate->format('Y-m-d'),
                    'installment_amount' => $detail->emi_amount,
                    'pay_date' => $payDate,
                    'pay_amount' => $payAmount,
                    'pay_status' => $payStatus,
                    'status_color' => $statusColor,
                    'row_color' => $rowColor,
                ]);
                
                $totalInstallmentAmount += $detail->emi_amount;
                $totalPaymentAmount += $payAmount;
            }
            
        } catch (\Exception $e) {
            return back()->withErrors('Error processing report: ' . $e->getMessage());
        }
    }
    
    // Handle Export (PDF/Excel)
    if ($request->filled('export_type')) {
        if (!$reportData->isEmpty()) {
            $data = [
                'company_info' => $companyInfo,
                'report_data' => $reportData->toArray(),
                'selected_customer' => $selectedCustomer,
                'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
                'total_installment_amount' => $totalInstallmentAmount,
                'total_payment_amount' => $totalPaymentAmount,
            ];
            
            $filename = 'EMI_CustomerWise_Report_' . $selectedCustomer->company_name . '_' . str_replace('-', '', $month);
            
            if ($request->input('export_type') === 'pdf') {
                return $this->exportCustomerWisePdf($data, $filename);
            } elseif ($request->input('export_type') === 'excel') {
                return $this->exportCustomerWiseExcel($data, $filename);
            }
        }
    }
    
    return view('Account::emi-entries.reports.customer-wise-report', [
        'customers' => $customers,
        'reportData' => $reportData,
        'selectedCustomer' => $selectedCustomer,
        'customerId' => $customerId,
        'month' => $month,
        'totalInstallmentAmount' => $totalInstallmentAmount,
        'totalPaymentAmount' => $totalPaymentAmount,
        'companyInfo' => $companyInfo,
    ]);
}

private function exportCustomerWisePdf($data, $filename)
{
    try {
        // Render blade view to HTML
        $html = view('Account::emi-entries.reports.customer-wise-report-pdf', $data)->render();
        
        // Configure Dompdf
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->setChroot(public_path());
        
        // Create PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream($filename . '.pdf', ['Attachment' => false]);
    } catch (\Exception $e) {
        return back()->withErrors('Error generating PDF: ' . $e->getMessage());
    }
}

// private function exportCustomerWiseExcel($data, $filename)
// {
//     try {
//         return (new ExportService())->exportCustomerWiseEmi($data, $filename);
//     } catch (\Exception $e) {
//         return back()->withErrors('Error generating Excel: ' . $e->getMessage());
//     }
// }
}
