<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\SalaryApprovalRequest;
use Modules\HRMS\Services\SalaryApprovalService;

class SalaryApprovalController extends Controller
{
    protected $approvalService;

    public function __construct(SalaryApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function verificationPage()
    {
        $pendingRequests = $this->approvalService->getPendingRequestsForUser(Auth::id());

        // FIX: view path correct
        return view('HRMS::salary-approvals.verification', compact('pendingRequests'));
    }

    public function show(SalaryApprovalRequest $salaryApprovalRequest)
    {
        $history = $this->approvalService->getApprovalHistory($salaryApprovalRequest);

        // FIX: view path correct
        return view('HRMS::salary-approvals.show', compact('salaryApprovalRequest', 'history'));
    }

    public function approve(Request $request, SalaryApprovalRequest $salaryApprovalRequest)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->approvalService->approve(
                $salaryApprovalRequest,
                Auth::id(),
                $request->input('remarks')
            );

            // FIX: route name correct
            return redirect()->route('hrm.salary-approvals.verification')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function deny(Request $request, SalaryApprovalRequest $salaryApprovalRequest)
    {
        $request->validate([
            'remarks' => 'required|string|max:500',
        ]);

        try {
            $result = $this->approvalService->deny(
                $salaryApprovalRequest,
                Auth::id(),
                $request->input('remarks')
            );

            // FIX: route name correct
            return redirect()->route('hrm.salary-approvals.verification')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $histories = SalaryApprovalRequest::where('created_by', Auth::id())
            ->orWhereHas('details', function ($query) {
                $query->whereHas('signatory', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            })
            ->with(['salaryGenerate', 'details.signatory.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // FIX: view path correct
        return view('HRMS::salary-approvals.history', compact('histories'));
    }
}
