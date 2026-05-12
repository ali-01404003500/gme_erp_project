<?php
namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\Job;
use Modules\HRMS\Models\JobApplication;

class JobApplicationApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position'          => 'required|string',
            'fullName'          => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'phone'             => 'required|string|max:30',
            'present_address'   => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',
            'resume'            => 'required|mimes:pdf,doc,docx|max:2048',
            'message'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Find Job
        $job = Job::where('title', $request->position)->first();

        if (! $job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found',
            ], 404);
        }

        // =============================================
        // duplicate check for same job, email and phone number
        // =============================================
        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->orWhere('mobile', $request->phone);
            })
            ->first();

        if ($existingApplication) {
            $errorMessage = '';

            if ($existingApplication->email === $request->email && $existingApplication->mobile === $request->phone) {
                $errorMessage = 'You have already applied for this position using this email and phone number.';
            } elseif ($existingApplication->email === $request->email) {
                $errorMessage = 'This email address has already been used to apply for this position.';
            } elseif ($existingApplication->mobile === $request->phone) {
                $errorMessage = 'This phone number has already been used to apply for this position.';
            }

            return response()->json([
                'success'         => false,
                'message'         => $errorMessage,
                'already_applied' => true,
            ], 409); // 409 Conflict status code
        }

        // Upload CV
        $cvPath = null;

        if ($request->hasFile('resume')) {
            $cvPath = $request->file('resume')
                ->store('job-cv', 'public');
        }

        // Save Application
        $application = JobApplication::create([
            'job_id'            => $job->id,
            'name'              => $request->fullName,
            'mobile'            => $request->phone,
            'email'             => $request->email,
            'present_address'   => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'cv'                => $cvPath,
            'remark'            => $request->message,
            'status'            => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data'    => $application,
        ]);
    }

    public function downloadCV($filename)
    {
        $path = storage_path('app/public/job-cv/' . $filename);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $filename, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

}
