<?php

namespace Modules\HRMS\Services;

use Modules\HRMS\Models\LeaveApplication;
use App\Traits\S3FileHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Modules\HRMS\Models\ApprovalFlow;
use Modules\HRMS\Models\ApprovalRequest;
use Modules\HRMS\Models\ApproverStep;

class LeaveApplicationService
{
    use S3FileHandler;
 
    public function getAll(int $limit = 20) {
        return LeaveApplication::with([
                'employee',
                'approvals' => function($query) {
                    $query->orderBy('level');
                }
            ])
            ->searchByFields(['employee_id'])
            ->when(Request::filled('from') && Request::filled('to'), function ($query) {
                $fromDate = Carbon::parse(Request::input('from'))->format('Y-m-d');
                $toDate = Carbon::parse(Request::input('to'))->format('Y-m-d');
                
                $query->where(function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('from_date', [$fromDate, $toDate])
                        ->orWhereBetween('to_date', [$fromDate, $toDate])
                        ->orWhere(function($query) use ($fromDate, $toDate) {
                            $query->where('from_date', '<=', $fromDate)
                                    ->where('to_date', '>=', $toDate);
                        });
                });
            })
            ->paginate($limit);
    }
    
    public function store(array $data)
    {
        $file_uploads = [];
        foreach ($data['file_uploads']??[] as $key => $image) {
            $file_uploads[$key] = $this->uploadFile($image, 'Leave Application');
        }
        $data['file_uploads'] = json_encode($file_uploads);

        $result['employee'] = LeaveApplication::create($data);

        /* Leave approval entry leave application wise */
        $workflow = ApprovalFlow::where('name','Leave Approval')->firstOrFail();

        $steps = ApproverStep::where('workflow_id', $workflow->id)
            ->where('employee_id', $data['employee_id'])
            ->orderBy('hierarchy_level')
            ->get();

        foreach ($steps as $step) {

            ApprovalRequest::create([
                'workflow_id'=>$workflow->id,
                'reference_id'=>$result['employee']->id,
                'reference_type'=>LeaveApplication::class,
                'level'=>$step-> hierarchy_level,
                'approver_id'=>$step->approver_id,
                'status'=>'pending'
            ]);

        } 

        return $result;
    }

    public function update(LeaveApplication $leaveApplication, array $data)
    {
        // $file_uploads = [];
        // foreach ($data['file_uploads']??[] as $key => $image) {
        //     $file_uploads[$key] = $this->uploadFile($image, 'Leave Application');
        // }
        $data['file_uploads'] = $data['file_uploads'];
        $leaveApplication->update($data);
        return $leaveApplication;
    }

    public function delete(LeaveApplication $leaveApplication)
    {
        $leaveApplication->delete();
    }

    public function show($id)
    { 
        // Load all leave applications of this employee with related info
        $leaveApplications = LeaveApplication::with([
            'employee', 
            'leaveType',        // leave type info
            'approvals.approver' // approver info
        ])
        ->where('id', $id) 
        ->first();


        return $leaveApplications;
    }
}
