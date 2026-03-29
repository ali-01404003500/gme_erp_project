<?php
namespace Modules\HRMS\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\SalaryApprovalDetail;
use Modules\HRMS\Models\SalaryApprovalRequest;
use Modules\HRMS\Models\SalarySignatory;

class SalaryApprovalService
{
    /**
     * Create salary approval request with all levels
     */
    public function createApprovalRequest($salaryGenerate, $createdBy, $remarks = null)
    {
        DB::beginTransaction();
        try {
            $signatories = SalarySignatory::getActiveSignatories();

            if ($signatories->isEmpty()) {
                throw new \Exception('No active signatories found. Please setup salary signatories first.');
            }

            // Create approval request
            $approvalRequest = SalaryApprovalRequest::create([
                'salary_generate_id' => $salaryGenerate->id,
                'created_by'         => $createdBy,
                'status'             => 'pending',
                'current_level'      => $signatories->first()->level,
                'remarks'            => $remarks,
            ]);

            // Create details for each level
            foreach ($signatories as $signatory) {
                SalaryApprovalDetail::create([
                    'salary_approval_request_id' => $approvalRequest->id,
                    'salary_signatory_id'        => $signatory->id,
                    'level'                      => $signatory->level,
                    'status'                     => 'pending',
                ]);
            }

            DB::commit();
            return $approvalRequest;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create salary approval error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approve salary request
     */
    public function approve(SalaryApprovalRequest $approvalRequest, $userId, $remarks = null)
    {
        DB::beginTransaction();
        try {
            $pendingDetail = $approvalRequest->details()
                ->whereHas('signatory', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('status', 'pending')
                ->first();

            if (! $pendingDetail) {
                throw new \Exception('You are not authorized to approve this request');
            }
            if ($pendingDetail->current_level !== $approvalRequest->current_level) {
                throw new \Exception('Cannot approve at this level.Previous level must be approved first.');
            }

            $pendingDetail->approve($remarks);

            if ($approvalRequest->isFullyApproved()) {
                $approvalRequest->update([
                    'status'      => 'approved',
                    'approved_at' => now(),
                ]);
                $isFinal = true;
                $message = 'Salary request has been fully approved';
            } else {
                $nextSignatory = SalarySignatory::getNextLevelSignatory($pendingDetail->level);
                if ($nextSignatory) {
                    $approvalRequest->update([
                        'current_level' => $nextSignatory->level,
                    ]);
                }
                $isFinal = false;
                $message = 'Level ' . $pendingDetail->level . ' approved successfully';
            }

            DB::commit();

            return [
                'success'  => true,
                'message'  => $message,
                'is_final' => $isFinal,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Deny salary request
     */
    public function deny(SalaryApprovalRequest $approvalRequest, $userId, $remarks = null)
    {
        DB::beginTransaction();
        try {
            $pendingDetail = $approvalRequest->details()
                ->whereHas('signatory', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('status', 'pending')
                ->first();

            if (! $pendingDetail) {
                throw new \Exception('You are not authorized to deny this request');
            }

            $pendingDetail->deny($remarks);
            $approvalRequest->update(['status' => 'denied']);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Salary request denied successfully',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get pending requests for current user
     */
    public function getPendingRequestsForUser($userId)
    {
        // ১. User থেকে Employee খুঁজে বের করা
        $employee = Employee::where('user_id', $userId)
            ->first();

        if (! $employee) {
            return collect();
        }

        // ২. Employee থেকে Active Signatory খুঁজে বের করা
        $signatory = SalarySignatory::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->first();

        if (! $signatory) {
            return collect();
        }

        return SalaryApprovalRequest::where('status', 'pending')

            ->where('current_level', $signatory->level)
            ->whereHas('details', function ($query) use ($signatory) {
                $query->where('salary_signatory_id', $signatory->id)
                    ->where('status', 'pending');
            })
            ->with(['salaryGenerate', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get approval history
     */
    public function getApprovalHistory(SalaryApprovalRequest $approvalRequest)
    {
        return $approvalRequest->details()
            ->with(['signatory.user'])
            ->orderBy('level', 'asc')
            ->get()
            ->map(function ($detail) {
                return [
                    'level'          => $detail->level,
                    'signatory_name' => $detail->signatory->user->name,
                    'signatory_tag'  => $detail->signatory->signatory_tag,
                    'status'         => $detail->status,
                    'remarks'        => $detail->remarks,
                    'actioned_at'    => $detail->actioned_at,
                ];
            });
    }
    public function getAllEmployees()
    {
        return Employee::select('id', 'full_name as display_name')
            ->orderBy('full_name', 'asc')
            ->get();
    }
}
