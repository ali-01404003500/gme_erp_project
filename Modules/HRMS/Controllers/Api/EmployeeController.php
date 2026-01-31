<?php

namespace Modules\HRMS\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;
use App\Models\AccessControl\Branch;
use Modules\HRMS\Services\EmployeeService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class EmployeeController extends Controller
{

    /**
     * Service variable
     *
     * @var EmployeeService
     */
    private $service;

    /**
     * User service
     *  @var UserService
     */
     private $userService;

    function __construct(EmployeeService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
        // Add API specific middleware if needed, e.g., 'auth:api'
        // $this->middleware('auth:api');
        $this->middleware('permited');
    }

    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        try {
            $data['employees'] = $this->service->getAll();
            $data['company_info'] = CompanyInfo::first();

            return response()->json([
                'data' => $data,
                'status' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'full_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,other',
                'date_of_birth' => 'nullable|date',
                'office_phone' =>  ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/','unique:employees,office_phone,NULL,id,deleted_at,NULL'],
                'personal_mobile' => 'required|string|max:255',
                'alternate_phone' => 'nullable|string|max:255',
                'email_address' => 'required|email|max:255|unique:employees,email_address,NULL,id,deleted_at,NULL',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'present_address' => 'required|string|max:255',
                'permanent_address' => 'required|string|max:255',
                'blood_group' => 'required|string|max:255',
                'religion' => 'required|string|max:255',
                'marital_status' => 'required|string|max:255',
                'photograph' => 'nullable', // Consider validation for file uploads if needed
                'resume' => 'nullable', // Consider validation for file uploads if needed
                'national_id' => 'required|string|max:255',
                'front_image' => 'nullable', // Consider validation for file uploads if needed
                'back_image' => 'nullable', // Consider validation for file uploads if needed
                'signature' => 'nullable', // Consider validation for file uploads if needed
                'address_proof' => 'nullable', // Consider validation for file uploads if needed
                'other_documents' => 'nullable', // Consider validation for file uploads if needed
                'bank_name' => 'required|string|max:255',
                'bank_branch' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'routing_number' => 'required|string|max:255',
                'etin_number' => 'required|string|max:255',
                'epf_number' => 'nullable|string|max:255',

                'email_accounts' => 'nullable|string|max:255',
                'software_access' => 'nullable|string|max:255',
                'additional_notes' => 'nullable|string|max:255',
            ]);

            $educationsDetails = $request->validate([
                'degree_title.*' => 'nullable|string|max:255',
                'institute_name.*' => 'nullable|string|max:255',
                'group.*' => 'nullable|string|max:255',
                'duration.*' => 'nullable|string|max:255',
                'passing_year.*' => 'nullable|string|max:255',
                'result.*' => 'nullable|string|max:255',
                'certificate_upload_*' => 'nullable', // Consider validation for file uploads if needed
            ]);

            $employementDetails = $request->validate([
                'card_no' => 'required|string|max:255',
                'date_of_joining' => 'required|date',
                'employment_type_id' => 'nullable',
                'department_id' => 'required|exists:departments,id',
                'designation_id' => 'required|exists:designations,id',
                'user_branch_id' => 'required|exists:branches,id',
                'supervisor' => 'nullable|exists:employees,id',

            ]);

            $user = $request->validate([
                'system_username' => 'required|string|max:255',
                'system_password' => 'nullable|string|max:255',
                'user_branch_id' => 'nullable|exists:branches,id',
            ]);

            $result = $this->service->create($validate,  $educationsDetails, $employementDetails, $user);

            return response()->json([
                'data' => $result,
                'status' => true,
                'message' => 'Employee created successfully'
            ], 201); // Use 201 for created resource

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422); // Use 422 for validation errors
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data['employee'] = $this->service->show($id);

            if (!$data['employee']) {
                 return response()->json([
                    'data' => [],
                    'status' => false,
                    'message' => 'Employee not found'
                ], 404); // Use 404 for not found
            }

            return response()->json([
                'data' => $data,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            $validate = $request->validate([
                'full_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,other',
                'date_of_birth' => 'nullable|date',
                'office_phone' =>  ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/'], // Consider unique validation if needed, excluding current employee
                'personal_mobile' => 'required|string|max:255',
                'alternate_phone' => 'nullable|string|max:255',
                'email_address' => 'required|email|max:255', // Consider unique validation if needed, excluding current employee
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'present_address' => 'required|string|max:255',
                'permanent_address' => 'required|string|max:255',
                'blood_group' => 'required|string|max:255',
                'religion' => 'required|string|max:255',
                'marital_status' => 'required|string|max:255',
                'photograph' => 'nullable', // Consider validation for file uploads if needed
                'resume' => 'nullable', // Consider validation for file uploads if needed
                'national_id' => 'required|string|max:255', // Consider unique validation if needed, excluding current employee
                'front_image' => 'nullable', // Consider validation for file uploads if needed
                'back_image' => 'nullable', // Consider validation for file uploads if needed
                'signature' => 'nullable', // Consider validation for file uploads if needed
                'address_proof' => 'nullable', // Consider validation for file uploads if needed
                'other_documents' => 'nullable', // Consider validation for file uploads if needed
                'bank_name' => 'required|string|max:255',
                'bank_branch' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'routing_number' => 'required|string|max:255',
                'etin_number' => 'required|string|max:255', // Consider unique validation if needed, excluding current employee
                'epf_number' => 'nullable|string|max:255',
                'email_accounts' => 'nullable|string|max:255',
                'software_access' => 'nullable|string|max:255',
                'additional_notes' => 'nullable|string|max:255',
            ]);

            $educationsDetails = $request->validate([
                'degree_title.*' => 'nullable|string|max:255',
                'institute_name.*' => 'nullable|string|max:255',
                'group.*' => 'nullable|string|max:255',
                'duration.*' => 'nullable|string|max:255',
                'passing_year.*' => 'nullable|string|max:255',
                'result.*' => 'nullable|string|max:255',
            ]);

            $employementDetails = $request->validate([
                'card_no' => 'nullable|string|max:255', // Consider unique validation if needed, excluding current employee
                'date_of_joining' => 'nullable|date',
                'employment_type_id' => 'nullable',
                'department_id' => 'required|exists:departments,id',
                'designation_id' => 'nullable|exists:designations,id',
                'user_branch_id' => 'nullable|exists:branches,id',
                'supervisor' => 'nullable|exists:employees,id',

            ]);
            $result = $this->service->update($employee, $validate, $educationsDetails, $employementDetails);

            return response()->json([
                'data' => $result,
                'status' => true,
                'message' => 'Employee updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422); // Use 422 for validation errors
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $this->service->delete($employee);

            return response()->json([
                'data' => [],
                'status' => true,
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'status' => false,
                'error' => 'There was an error occurred',
                'exception' => $th->getMessage() // optional: for debugging
            ], 500);
        }
    }
}