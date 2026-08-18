<?php
namespace Modules\SalesTarget\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Transaction;
use Modules\HRMS\Models\BillsAndAllowance;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\SalesTarget\Models\Target;
use Modules\Sales\Models\SalesCommission;
use Modules\Sales\Models\SalesOrder;

 
use Exception;
use Carbon\Carbon;
use Modules\SalesTarget\Models\SalesSalaryBracket;
use Modules\SalesTarget\Models\SalesTarget;
use Modules\SalesTarget\Models\SalesTargetRateTier;
use Modules\SalesTarget\Models\SalesTargetSlab;

class SalesTargetService
{
    // ---------- 1. Slab খোঁজা ----------
    public function findSlabBySalary(float $salary): ?SalesTargetSlab
    {
        return SalesTargetSlab::where('is_active', true)
            ->where('min_salary', '<=', $salary)
            ->where('max_salary', '>=', $salary)
            ->first();
    }

    // ---------- 2. Target Assign (existing employee salary table theke fetch) ----------
    public function assignTarget(int $employeeId, int $month, int $year, string $salaryBasis = 'basic'): SalesTarget
    {
        // ⚠️ column name গুলো আপনার actual employee/users table অনুযায়ী বদলান
        $employee = User::findOrFail($employeeId);
        $basicSalary = $employee->basic_salary;
        $ta = $employee->ta ?? 0;
        $da = $employee->da ?? 0;
        $grossSalary = $basicSalary + $ta + $da;

        $salaryForSlab = $salaryBasis === 'gross' ? $grossSalary : $basicSalary;
        $slab = $this->findSlabBySalary($salaryForSlab);

        if (!$slab) {
            throw new Exception("এই salary ({$salaryForSlab}) এর জন্য কোনো slab পাওয়া যায়নি।");
        }

        $target = SalesTarget::where('employee_id', $employeeId)
            ->where('period_month', $month)->where('period_year', $year)->first();

        if ($target && $target->is_locked) {
            throw new Exception("এই মাসের target lock করা আছে, পরিবর্তন করা যাবে না।");
        }

        return SalesTarget::updateOrCreate(
            ['employee_id' => $employeeId, 'period_month' => $month, 'period_year' => $year],
            [
                'sales_target_slab_id' => $slab->id,
                'salary_basis' => $salaryBasis,
                'salary_at_assign' => $basicSalary,
                'gross_salary_at_assign' => $grossSalary,
                'target_amount' => $slab->calculateTargetFor($salaryForSlab),
                'status' => 'pending',
            ]
        );
    }

    // ---------- 3. Achievement Record (Order create hole call hobe) ----------
    public function recordAchievement(int $employeeId, float $amount, ?Carbon $date = null): void
    {
        $date = $date ?? now();

        $target = SalesTarget::where('employee_id', $employeeId)
            ->where('period_month', $date->month)->where('period_year', $date->year)->first();

        if (!$target || $target->is_locked) {
            return; // target nei othoba already locked - ignore
        }

        $target->increment('achieved_amount', $amount);
        $target->refresh();

        $percent = $target->target_amount > 0
            ? round(($target->achieved_amount / $target->target_amount) * 100, 2)
            : 0;

        $target->update([
            'achievement_percent' => $percent,
            'status' => $percent >= 100 ? 'achieved' : 'in_progress',
        ]);

        $this->recalculateIncentive($target->fresh());
    }

    // ---------- 4. Incentive Rate Tier খোঁজা ----------
    public function findRateTier(float $achievementPercent): ?SalesTargetRateTier
    {
        return SalesTargetRateTier::where('is_active', true)
            ->where('min_percent', '<=', $achievementPercent)
            ->where(fn($q) => $q->whereNull('max_percent')->orWhere('max_percent', '>=', $achievementPercent))
            ->orderByDesc('min_percent')->first();
    }

    // ---------- 5. Salary Payout Bracket খোঁজা ----------
    public function findSalaryBracket(float $achievementPercent): ?SalesSalaryBracket
    {
        return SalesSalaryBracket::where('is_active', true)
            ->where('min_percent', '<=', $achievementPercent)
            ->where(fn($q) => $q->whereNull('max_percent')->orWhere('max_percent', '>=', $achievementPercent))
            ->orderByDesc('min_percent')->first();
    }

    // ---------- 6. Raw Incentive + Payout Bracket দুটোই apply করে Final Incentive বের করা ----------
    public function recalculateIncentive(SalesTarget $target): SalesTarget
    {
        if ($target->is_full_honor_override) {
            // Manual override thakle bracket calculation bypass hobe
            return $target;
        }

        $rateTier = $this->findRateTier($target->achievement_percent);
        $rawIncentive = $rateTier ? round($target->achieved_amount * ($rateTier->rate_percent / 100), 2) : 0;

        $bracket = $this->findSalaryBracket($target->achievement_percent);
        $payoutPercent = $bracket ? $bracket->payout_percent : 0;

        $finalIncentive = round($rawIncentive * ($payoutPercent / 100), 2);

        $target->update([
            'incentive_rate_applied' => $rateTier->rate_percent ?? null,
            'raw_incentive_amount' => $rawIncentive,
            'payout_percent_applied' => $payoutPercent,
            'final_incentive_amount' => $finalIncentive,
        ]);

        return $target->fresh();
    }

    // ---------- 7. Manual "Full Honor" Override ----------
    public function fullHonorOverride(int $employeeId, int $month, int $year, int $byUserId): SalesTarget
    {
        $target = SalesTarget::where('employee_id', $employeeId)
            ->where('period_month', $month)->where('period_year', $year)->firstOrFail();

        $target->update([
            'is_full_honor_override' => true,
            'override_by' => $byUserId,
            'override_at' => now(),
            'payout_percent_applied' => 100,
            'final_incentive_amount' => $target->raw_incentive_amount, // raw poriman e full pabe
        ]);

        return $target->fresh();
    }

    // ---------- 8. Payroll-এর আগে Lock ----------
    public function lockTarget(int $employeeId, int $month, int $year, int $lockedByUserId): SalesTarget
    {
        $target = SalesTarget::where('employee_id', $employeeId)
            ->where('period_month', $month)->where('period_year', $year)->firstOrFail();

        if ($target->is_locked) {
            return $target;
        }

        if (!$target->is_full_honor_override) {
            $this->recalculateIncentive($target); // shesh muhurte final calculation
        }

        $target->update(['is_locked' => true, 'locked_at' => now(), 'locked_by' => $lockedByUserId]);
        return $target->fresh();
    }

    // ---------- 9. Payroll Module থেকে কল হবে ----------
    public function getMonthlyIncentive(int $employeeId, int $month, int $year): float
    {
        $target = SalesTarget::where('employee_id', $employeeId)
            ->where('period_month', $month)->where('period_year', $year)->first();

        if (!$target) return 0;

        if (!$target->is_locked) {
            throw new Exception("Target lock করা হয়নি — payroll generate করার আগে lock করুন।");
        }

        return $target->final_incentive_amount;
    }
}