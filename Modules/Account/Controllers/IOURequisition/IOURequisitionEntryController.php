<?php

namespace Modules\Account\Controllers\IOURequisition;

use App\Http\Controllers\Controller;
use Modules\Account\Models\IOURequisition\IOURequisitionEntry;
use Modules\Account\Services\IOURequisition\IOURequisitionEntryService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;
use Modules\Account\Models\AccountSetup\BankAccount;

class IOURequisitionEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var IOURequisitionEntryService
     */
    private $service;
    private $smsService;

    function __construct(IOURequisitionEntryService $service, SmsService $smsService)
    {
        $this->service = $service;
        $this->smsService = $smsService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['iOURequisitionEntrys'] = $this->service->getAll();
        $data['bankAccounts'] = BankAccount::with('bankBranch', 'bank')->where("payment_mode", "Cash")->get();

        return view("Account::i-o-u-requisition.i-o-u-requisition-entries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // @dd(auth()->user());
        // $data['employee'] =auth()->user()->employee;
        $data['employees'] = Employee::select('id', 'full_name as name')->get();
        return view('Account::i-o-u-requisition.i-o-u-requisition-entries.create',  $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            //validate rules
            'date' => 'required|date',
            'type' => 'required|in:Expense,Advance',
            'employee_id' => 'required|exists:employees,id',
            'request_amount' => 'required|numeric|min:1',
            'verify_amount' => 'nullable|numeric|min:0',
            'approved_amount' => 'nullable|numeric|min:0',
            'remarks' => 'required|string|max:255',
            'status' => 'required|string', 
        ]);
        $this->service->store($validate);
        return redirect()->route('account.i-o-u-requisition.i-o-u-requisition-entries.index')->with('success', 'IOURequisitionEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['iOURequisitionEntry'] = $this->service->show($id);

        return view("Account::i-o-u-requisition.i-o-u-requisition-entries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IOURequisitionEntry $iOURequisitionEntry)
    {
        $data['iOURequisitionEntry'] = $iOURequisitionEntry;
        //
        return view("Account::i-o-u-requisition.i-o-u-requisition-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IOURequisitionEntry $iOURequisitionEntry)
    {
        // dd($request->all());
        $validate = $request->validate([
            //validate rules
            'date' => 'required|date',
            'type' => 'required|in:Expense,Advance',
            // 'employee_id' => 'required|exists:employees,id',
            'request_amount' => 'required|numeric|min:1',
            'verify_amount' => 'nullable|numeric|min:0',
            'approved_amount' => 'nullable|numeric|min:0',
            'remarks' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        $result = $this->service->update($iOURequisitionEntry, $validate);
        // dd($result);

        // Determine success message based on status
        $status = $validate['status'];
        if ($status === 'verified') {
            $message = 'Payment Requisition Verified Successfully (Verified).';
        } elseif ($status === 'approved') {
            $message = 'Payment Requisition Approved Successfully (Final).';
        } elseif ($status === 'denied') {
            $message = 'Payment Requisition Denied.';
        } else {
            $message = 'IOURequisitionEntry updated successfully.';
        }

        return redirect()->route('account.i-o-u-requisition.i-o-u-requisition-entries.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IOURequisitionEntry $iOURequisitionEntry)
    {
        $this->service->delete($iOURequisitionEntry);
        return redirect()->route('account.i-o-u-requisition.i-o-u-requisition-entries.index')->with('success', 'IOURequisitionEntry deleted successfully.');
    }

    /**
     * Send OTP to employee for payment verification
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:i_o_u_requisition_entries,id'
        ]);

        $entry = IOURequisitionEntry::findOrFail($request->entry_id);

        // Check if entry is approved
        if ($entry->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Payment can only be initiated for approved entries'
            ]);
        }

        // Check if employee has contact number
        if (!$entry->employee->personal_mobile) {
            return response()->json([
                'success' => false,
                'message' => 'Employee personal mobile number not available'
            ]);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session with expiration
        session()->put('payment_otp_' . $entry->id, [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
            'entry_id' => $entry->id
        ]);

        // Prepare employee contact number
        $employeeContact = $entry->employee->personal_mobile;

        if (substr($employeeContact, 0, 2) === '01') {
            $employeeContact = '880' . substr($employeeContact, 1);
        }

        // Send OTP via SMS
        $message = "Your OTP for payment verification is: {$otp}. This OTP will expire in 5 minutes.";

        try {
            $sent = $this->smsService->send($employeeContact, $message);

            if ($sent) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to employee successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP'
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error sending OTP for I.O.U. Requisition Entry ID {$entry->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending OTP'
            ]);
        }
    }

    /**
     * Verify OTP for payment
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:i_o_u_requisition_entries,id',
            'otp' => 'required|string|size:6'
        ]);

        $entry = IOURequisitionEntry::findOrFail($request->entry_id);

        // Check OTP
        $otpData = session()->get('payment_otp_' . $entry->id);

        if (!$otpData || now()->isAfter($otpData['expires_at'])) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ]);
        }

        if ($otpData['otp'] !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }

        // OTP verified, mark as verified in session
        session()->put('payment_verified_' . $entry->id, [
            'verified' => true,
            'verified_at' => now(),
            'otp' => $request->otp
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    /**
     * Confirm payment and update status to paid
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:i_o_u_requisition_entries,id',
            'otp' => 'required|string|size:6'
        ]);

        $entry = IOURequisitionEntry::findOrFail($request->entry_id);

        // Verify payment is authorized
        $verifiedData = session()->get('payment_verified_' . $entry->id);

        if (!$verifiedData || !$verifiedData['verified'] || $verifiedData['otp'] !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);
        }

        // Check if entry is still approved
        if ($entry->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Entry status has changed'
            ]);
        }

        // Update entry status to paid and increment received_amount
        $this->service->markAsPaid($entry);

        // Clean up session data
        session()->forget('payment_otp_' . $entry->id);
        session()->forget('payment_verified_' . $entry->id);

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully'
        ]);
    }

    /**
     * Process IOU bill return
     */
    public function returnBill(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:i_o_u_requisition_entries,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $entry = IOURequisitionEntry::findOrFail($request->entry_id);

        // Check if entry is in paid status
        if ($entry->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Only paid entries can be returned'
            ]);
        }

        // Process the return
        $this->service->processReturn($entry, $request->bank_account_id, $request->remarks);

        return response()->json([
            'success' => true,
            'message' => 'IOU return processed successfully'
        ]);
    }
}
