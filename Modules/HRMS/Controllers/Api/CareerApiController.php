<?php
namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\HRMS\Models\Job;

class CareerApiController extends Controller
{

    /**
     * All Active Jobs
     */
    public function jobs()
    {

        $jobs = Job::with([
            'department:id,name',
            'designation:id,name',
            'branch:id,name',
        ])
            ->where('status', 1)
            ->latest()
            ->get();

        $formattedJobs = $jobs->map(function ($job) {

            return [
                'id'          => $job->id,
                'slug'        => $job->slug
                    ? $job->slug
                    : Str::slug($job->title) . '-' . $job->id,
                'position'    => $job->title,
                'department'  => optional($job->department)->name,
                'location'    => $job->location ?? optional($job->branch)->name,
                'deadline'    => optional($job->deadline_at)
                    ? date('d M Y', strtotime($job->deadline_at))
                    : null,
                'type'        => $job->job_type,
                'experience'  => $job->experience,
                'description' => $job->description,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $formattedJobs,
        ]);
    }

    /**
     * Single Job Details
     */
    public function jobDetails($slug)
    {
        $job = Job::with([
            'department:id,name',
            'designation:id,name',
            'branch:id,name',
        ])
            ->where(function ($query) use ($slug) {

                if (is_numeric($slug)) {
                    $query->where('id', $slug);

                } else {
                    $id = last(explode('-', $slug));
                    $query->where('id', $id);
                }
            })
            ->where('status', 1)
            ->first();

        if (! $job) {

            return response()->json([
                'success' => false,
                'message' => 'Job not found',
            ], 404);
        }

        return response()->json([
            'success' => true,

            'data'    => [

                'id'                      => $job->id,
                'slug'                    => Str::slug($job->title) . '-' . $job->id,
                'position'                => $job->title,
                'department'              => optional($job->department)->name,
                'designation'             => optional($job->designation)->name,
                'branch'                  => optional($job->branch)->name,
                'location'                => $job->location,
                'deadline'                => $job->deadline_at,
                'start_at'                => $job->start_at,
                'type'                    => $job->job_type,
                'gender'                  => $job->gender,
                'salary'                  => $job->salary,
                'experience'              => $job->experience,
                'office_hours'            => $job->office_hours,
                'weekend'                 => $job->weekend,
                'description'             => $job->description,
                'company_overview'        => $job->company_overview,
                'employee_centric_policy' => $job->employee_centric_policy,
                'educational_requirement' => $job->educational_requirement,
                'responsibility'          => $job->responsibility,
            ],
        ]);
    }

    /**
     * Department List
     */
    public function departments()
    {

        $departments = Job::with('department:id,name')
            ->where('status', 1)
            ->get()
            ->pluck('department.name')
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data'    => $departments,
        ]);
    }

    /**
     * Location List
     */
    public function locations()
    {

        $locations = Job::where('status', 1)
            ->whereNotNull('location')
            ->pluck('location')
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data'    => $locations,
        ]);
    }
}
