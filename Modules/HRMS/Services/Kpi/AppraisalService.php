<?php

namespace Modules\HRMS\Services\Kpi;

use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\Kpi\Appraisal;
use Modules\HRMS\Models\Settings\SalarySetup;

class AppraisalService
{
    public function getAll(int $limit = 20)
    {
        return Appraisal::query()
            ->searchByFields([
                'employee_id' => 'employee_id',
            ])
            ->paginate($limit);
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            // Create Appraisal
            $appraisal = Appraisal::create($data);

            DB::commit();
            return $appraisal;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function calculateSalaryComponents($gross, $setup)
    {
        $basicPercentage = $setup->basic;
        $houseRentPercentage = $setup->house_rent;
        $conveyancePercentage = $setup->conveyance;
        $medicalPercentage = $setup->medical;
        $othersPercentage = $setup->others;

        $isConveyanceFixed = $setup->is_conveyance_fixed;
        $isMedicalFixed = $setup->is_medical_fixed;
        $isOthersFixed = $setup->is_others_fixed;

        // Defaults
        $conveyance = $isConveyanceFixed ? $conveyancePercentage : 0;
        $medical = $isMedicalFixed ? $medicalPercentage : 0;
        $others = $isOthersFixed ? $othersPercentage : 0;

        $availableGross = $gross - $conveyance - $medical - $others;

        // Variable components
        if (!$isConveyanceFixed) {
            $conveyance = $availableGross * ($conveyancePercentage / 100);
        }
        if (!$isMedicalFixed) {
            $medical = $availableGross * ($medicalPercentage / 100);
        }
        if (!$isOthersFixed) {
            $others = $availableGross * ($othersPercentage / 100);
        }

        $basic = $availableGross * ($basicPercentage / 100);
        $houseRent = $availableGross * ($houseRentPercentage / 100);

        return [
            'basic' => round($basic, 2),
            'house_rent' => round($houseRent, 2),
            'conveyance' => round($conveyance, 2),
            'medical' => round($medical, 2),
            'others' => round($others, 2),
        ];
    }

    public function update(Appraisal $appraisal, array $data)
{
    DB::beginTransaction();

    try {
        $appraisal->update($data);

        if ($data['status'] === 'approved') {
            $employee = Employee::with('latestEmployeeSalary')->findOrFail($data['employee_id']);
            $lastSalary = $employee->latestEmployeeSalary;

            if (!$lastSalary || !$lastSalary->salary_setup_id) {
                throw new \Exception('Salary setup not found for this employee.');
            }

            $salarySetup = SalarySetup::findOrFail($lastSalary->salary_setup_id);
            $gross = $data['new_salary'];

            // Deactivate previous salaries
            EmployeeSalary::where('employee_id', $data['employee_id'])->update(['status' => 0]);

            $components = $this->calculateSalaryComponents($gross, $salarySetup);

            // Create new salary record
            EmployeeSalary::create([
                'employee_id'    => $data['employee_id'],
                'salary_setup_id'=> $salarySetup->id,
                'gross'          => $gross,
                'basic'          => $components['basic'],
                'house_rent'     => $components['house_rent'],
                'conveyance'     => $components['conveyance'],
                'medical'        => $components['medical'],
                'others'         => $components['others'],
                'effective_date' => now(),
            ]);
        }

        DB::commit(); // ✅ must come before return
        return $appraisal;

    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


    public function delete(Appraisal $appraisal)
    {
        $appraisal->delete();
    }

    public function show($id)
    {
        return Appraisal::findOrFail($id);
    }
}
