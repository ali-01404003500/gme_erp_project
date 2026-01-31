<?php

namespace Modules\HRMS\Services;

use App\Models\AccessControl\Branch;
use App\Models\User;
use Modules\HRMS\Models\Employee;
use App\Traits\S3FileHandler;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\EducationDetail;
use Modules\HRMS\Models\EmployementDetail;
use Modules\HRMS\Models\Settings\Designation;
use Modules\HRMS\Models\Settings\Department;

class EmployeeService
{
    use S3FileHandler;

    public function getAll(int $limit = 20)
    {
        return Employee::query()
            ->with('employementDetail.department', 'employementDetail.designation', 'employementDetail.supervisorName')
            ->searchByFields(['full_name', 'personal_mobile'])
            ->likeSearch('present_address')
            ->paginate($limit);
    }

    public function create(array $data, $educationsDetails, $employementDetails, $user)
    {

        $result = [];
        DB::beginTransaction();

        $user = User::create([
            'name' => $data['full_name'],
            'email' => $user['system_username'],
            'password' => bcrypt($user['system_password']),
            'branch_id' => $user['user_branch_id'],
        ]);
        $result['user'] = $user;

        $data['user_id'] = $user->id;
        $result['employee'] = Employee::create($data);

        foreach ($educationsDetails['degree_title'] as $key => $degree_title) {
            $result['employee']->educationDetails()->create([
                'degree_title' => $degree_title,
                'institute_name' => $educationsDetails['institute_name'][$key],
                'group' => $educationsDetails['group'][$key],
                'duration' => $educationsDetails['duration'][$key],
                'passing_year' => $educationsDetails['passing_year'][$key],
                'result' => $educationsDetails['result'][$key],
                'certificate_upload' => request()->input('certificate_upload_' . $key) ?? null,
            ]);
        }

        $result['employee']->employementDetails()->create([
            'employee_id' => $result['employee']->id,
            'card_no' => $employementDetails['card_no'],
            'date_of_joining' => $employementDetails['date_of_joining'],
            'employment_type_id' => $employementDetails['employment_type_id'],
            'department_id' => $employementDetails['department_id'],
            'designation_id' => $employementDetails['designation_id'],
            'branch_id' => $employementDetails['user_branch_id'],
            'supervisor' => $employementDetails['supervisor'],
        ]);

        $result['employee']->createBankAccount();
        // dd($result);
        DB::commit();
        return $result;
    }

    public function update(Employee $employee, array $data, array $educationsDetails, array $employementDetails)
    {
                // dd( $employee);

        $employee->update($data);

        // Reset and save education details
        $employee->educationDetails()->delete();
        // dd($educationsDetails);
        if (!empty($educationsDetails['degree_title'])) {
            foreach ($educationsDetails['degree_title'] as $key => $degree_title) {
                if (
                    empty($degree_title) &&
                    empty($educationsDetails['institute_name'][$key]) &&
                    empty($educationsDetails['group'][$key]) &&
                    empty($educationsDetails['duration'][$key]) &&
                    empty($educationsDetails['passing_year'][$key]) &&
                    empty($educationsDetails['result'][$key]) &&
                    empty(request()->input('certificate_upload_' . $key))
                ) {
                    continue; // skip blank rows
                }

                EducationDetail::create([
                    'employee_id' => $employee->id,
                    'degree_title' => $degree_title,
                    'institute_name' => $educationsDetails['institute_name'][$key],
                    'group' => $educationsDetails['group'][$key],
                    'duration' => $educationsDetails['duration'][$key],
                    'passing_year' => $educationsDetails['passing_year'][$key],
                    'result' => $educationsDetails['result'][$key],
                    'certificate_upload' => request()->input('certificate_upload_' . $key) ?? null,
                ]);
            }
        }

        // Reset and save employment detailsBill
        $employee->employementDetails()->delete();
        EmployementDetail::create([
            'employee_id' => $employee->id,
            'card_no' => $employementDetails['card_no'],
            'date_of_joining' => $employementDetails['date_of_joining'],
            'employment_type_id' => $employementDetails['employment_type_id'],
            'department_id' => $employementDetails['department_id'],
            'designation_id' => $employementDetails['designation_id'],
            'supervisor' => $employementDetails['supervisor'],
            'branch_id' => $employementDetails['user_branch_id'],
        ]);

        // Update the user's branch to match the employee's branch
        if ($employee->user) {
            $employee->user->update([
                'branch_id' => $employementDetails['user_branch_id'],
            ]);
        }

        return $employee;
    }


    public function delete(Employee $employee)
    {
        User::where('id', $employee->user_id)->delete();
        $employee->delete();
    }

    public function show($id)
    {
        return Employee::with(['educationDetails', 'employementDetails.branch', 'employementDetails.supervisorName'])->findOrFail($id);
    }

    public function getEmployees()
    {
        return Employee::query()
            ->select('id', 'full_name as name')
            ->get();
    }

    // public function insertFromCSV($filename)
    // {
    //     $path = storage_path('app/public/' . $filename);
    //     $file = fopen($path, 'r');
    //     $header = fgetcsv($file);

    //     while ($row = fgetcsv($file)) {
    //         $data = array_combine($header, $row);
    //         Employee::create([
    //             'full_name' => $data['full_name'],
    //             'father_name' => $data['father_name'],
    //             'mother_name' => $data['mother_name'],
    //             'gender' => $data['gender'],
    //             'office_phone' => $data['office_phone'],
    //             'personal_mobile' => $data['personal_mobile'],
    //             'alternate_phone' => $data['alternate_phone'] ?? null,
    //             'email_address' => $data['email_address'],
    //             'country' => $data['country'],
    //             'city' => $data['city'],
    //             'present_address' => $data['present_address'],
    //             'permanent_address' => $data['permanent_address'],
    //             'blood_group' => $data['blood_group'],
    //             'religion' => $data['religion'],
    //             'marital_status' => $data['marital_status'],
    //             'national_id' => $data['national_id'],
    //             'bank_name' => $data['bank_name'] ?? null,
    //             'account_holder_name' => $data['account_holder_name'] ?? null,
    //             'account_number' => $data['account_number'] ?? null,
    //             'bank_branch' => $data['bank_branch'] ?? null,
    //             'routing_number' => $data['routing_number'] ?? null,
    //             'etin_number' => $data['etin_number'] ?? null,
    //             'epf_number' => $data['epf_number'] ?? null,
    //             'email_accounts' => $data['email_accounts'] ?? null,
    //             'software_access' => $data['software_access'] ?? null,
    //             'additional_notes' => $data['additional_notes'] ?? null,
    //         ]);
    //     }

    //     fclose($file);
    // }
    public function insertFromCSV(string $filename): void
    {
        $path = storage_path('app/public/' . $filename);
        if (!file_exists($path) || !is_readable($path)) {
            throw new \Exception("CSV file not found or not readable: {$path}");
        }

        $branchCache = [];
        $departmentCache = [];
        $designationCache = [];

        if (($file = fopen($path, 'r')) === false) {
            throw new \Exception("Unable to open CSV: {$path}");
        }

        // Read header row
        $header = fgetcsv($file);
        if (!is_array($header)) {
            throw new \Exception("CSV file {$path} has no header row.");
        }

        while (($row = fgetcsv($file)) !== false) {
            $d = array_combine($header, $row);
            // if (Employee::where('office_phone', $d['office_phone'])
            //             ->orWhere('email_address', $d['email_address'])
            //             ->exists())
            // {
            //     // Skip this row entirely
            //     continue;
            // }
            // lookup/caching for branch
            $branchKey = trim($d['branch']);
            if (!isset($branchCache[$branchKey])) {
                $branchCache[$branchKey] = Branch::where('name', $branchKey)
                    ->value('id');
            }
            $branchId = $branchCache[$branchKey];

            // lookup/caching for department
            $deptKey = trim($d['department']);
            if (!isset($departmentCache[$deptKey])) {
                $departmentCache[$deptKey] = Department::where('name', $deptKey)
                    ->value('id');
            }
            $departmentId = $departmentCache[$deptKey];

            // lookup/caching for designation
            $desigKey = trim($d['designation']);
            if (!isset($designationCache[$desigKey])) {
                $designationCache[$desigKey] = Designation::where('name', $desigKey)
                    ->value('id');
            }
            $designationId = $designationCache[$desigKey];

            $supervisorKey = trim($d['supervisor']);
            if (!isset($branchCache[$supervisorKey])) {
                $branchCache[$supervisorKey] = Employee::where('full_name', $supervisorKey)
                    ->value('id');
            }
            $supervisorId = $branchCache[$supervisorKey];

            // Build main Employee data
            $employeeData = [
                'full_name' => $d['full_name'],
                'father_name' => $d['father_name'],
                'mother_name' => $d['mother_name'],
                'gender' => $d['gender'],
                'date_of_birth' => $d['date_of_birth'],
                'office_phone' => $d['office_phone'],
                'personal_mobile' => $d['personal_mobile'],
                'alternate_phone' => $d['alternate_phone'] ?? null,
                'email_address' => $d['email_address'],
                'country' => $d['country'],
                'city' => $d['city'],
                'present_address' => $d['present_address'],
                'permanent_address' => $d['permanent_address'],
                'blood_group' => $d['blood_group'],
                'religion' => $d['religion'],
                'marital_status' => $d['marital_status'],
                'national_id' => $d['national_id'],
                'bank_name' => $d['bank_name'] ?? null,
                'account_holder_name' => $d['account_holder_name'] ?? null,
                'account_number' => $d['account_number'] ?? null,
                'bank_branch' => $d['bank_branch'] ?? null,
                'routing_number' => $d['routing_number'] ?? null,
                'etin_number' => $d['etin_number'] ?? null,
                'epf_number' => $d['epf_number'] ?? null,
                'email_accounts' => $d['email_accounts'] ?? null,
                'software_access' => $d['software_access'] ?? null,
                'additional_notes' => $d['additional_notes'] ?? null,
            ];

            // Build User data
            $userData = [
                'system_username' => $d['system_username'],
                'system_password' => $d['system_password'],
                'user_branch_id' => $branchId,
            ];

            // Build EmploymentDetails
            $employmentDetails = [
                'card_no' => $d['card_no'],
                'date_of_joining' => $d['date_of_joining'],
                'employment_type_id' => $d['employment_type'],
                'department_id' => $departmentId,
                'designation_id' => $designationId,
                'user_branch_id' => $branchId,
                'supervisor' => $supervisorId,
            ];

            // Wrap education fields in arrays
            $educationDetails = [
                'degree_title' => [$d['degree_title']],
                'institute_name' => [$d['institute_name']],
                'group' => [$d['group']],
                'duration' => [$d['duration']],
                'passing_year' => [$d['passing_year']],
                'result' => [$d['result']],
            ];

            // Call the creator (transaction inside)
            $this->create($employeeData, $educationDetails, $employmentDetails, $userData);
        }

        fclose($file);
    }


    public function forceUserCreate(Employee $employee)
    {
        if ($employee->user == null) {
            $user = User::create([
                'name' => $employee->full_name,
                'email' => $employee->email_address,
                'password' => bcrypt('12345678'),
                'user_branch_id' => 1,
            ]);
            $employee->update(['user_id' => $user->id]);
        }
    }
}
