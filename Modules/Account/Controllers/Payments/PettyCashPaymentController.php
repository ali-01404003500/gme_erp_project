<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Account\Services\Payments\PettyCashPaymentService;

class PettyCashPaymentController extends Controller
{
    private $service;

    public function __construct(PettyCashPaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of approved petty cash for payment
     */
    public function index(Request $request)
    {
        $data['pettyCashList'] = $this->service->getApprovedForPayment($request);
        $data['employees'] = Employee::all();
        
        return view("Account::payments.petty-cash-payments.index", $data);
    }

    /**
     * Show petty cash details for payment
     */
    public function details(Request $request, $id)
    {
        $data['billsAndAllowance'] = $this->service->getDetailsForPayment($id);
        $data['company_info'] = CompanyInfo::first();
        
        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data['billsAndAllowance'],
                'html' => view('Account::payments.petty-cash-payments.details-modal', $data)->render()
            ]);
        }
        
        return view("Account::payments.petty-cash-payments.details-modal", $data);
    }

    /**
     * Process payment for petty cash
     */
    public function processPayment(Request $request, $id)
    {
        $validate = $request->validate([
            'account_heads' => 'nullable|array',
            'account_heads.*' => 'nullable|exists:accounts,id',
            'remarks' => 'nullable|string',
        ]);

            $this->service->processPayment($id, $validate);
            
            return redirect()->route('account.payments.petty-cash-payments.index')
                ->with('success', 'Payment processed successfully.');
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with('error', 'Payment processing failed: ' . $e->getMessage());
        // }
    }

    /**
     * Show payment receipt/voucher
     */
    public function showReceipt(Request $request, $id)
    {
        $data['billsAndAllowance'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();
        
        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Account::payments.petty-cash-payments.receipt-view', $data)->render();
            
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('petty_cash_payment_receipt_' . $data['billsAndAllowance']->id . '.pdf', ['Attachment' => false]);
        }

        return view("Account::payments.petty-cash-payments.receipt", $data);
    }
}