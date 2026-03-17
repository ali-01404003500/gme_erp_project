<?php
namespace Modules\HRMS\Models;

use App\Models\AccessControl\Branch;
use App\Models\BaseModel;
use App\Models\User;
use App\Traits\AutoCreateUpdateAndHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\HRMS\Models\ApproverStep;
use Modules\HRMS\Models\Kpi\Assessment;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Models\Settings\Designation;
// use Modules\Inventory\Models\Settings\Approver;

class Employee extends BaseModel
{
    use HasFactory;
    use AutoCreateUpdateAndHistory;
    use SoftDeletes;

    protected $guarded = [];

    public $deletePrevent = [
        'salaryGenerates',
        'attendances',
        'leaves',
    ];

    public function approvers()
    {
        return $this->hasMany(ApproverStep::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function educationDetails()
    {
        return $this->hasMany(EducationDetail::class, 'employee_id');
    }

    public function employeementExperience()
    {
        return $this->hasMany(EmployeeExperience::class, 'employee_id');
    }

    public function documentsDetails()
    {
        return $this->hasMany(EmployeeDocuments::class, 'employee_id');
    }

    public function employeeFamilyContact()
    {
        return $this->hasMany(EmployeeFamilyContact::class, 'employee_id');
    }

    public function employementDetails()
    {
        return $this->hasMany(EmployementDetail::class, 'employee_id');
    }

    public function employementDetail()
    {
        return $this->hasOne(EmployementDetail::class, 'employee_id');
    }

    public function latestEmployeeSalary()
    {
        return $this->hasOne(EmployeeSalary::class, 'employee_id')->withoutGlobalScope('latest');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function salaryGenerates()
    {
        return $this->hasMany(SalaryGenerate::class, 'employee_id');
    }

    public function accounts()
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function employeeSalaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'employee_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaves()
    {
        return $this->hasMany(LeaveApplication::class, 'employee_id');
    }

    // ============================================

    public function bankAccount()
    {
        return $this->morphOne(BankAccount::class, 'sourceable');
    }

    public function createBankAccount()
    {
        $existingAccount = $this->bankAccount()->first();

        if ($existingAccount) {
            return $existingAccount;
        }

        $bankAccount = $this->bankAccount()->create([
            'payment_mode'    => "Cash",
            'account_name'    => $this->full_name . " – Cash in Hand",
            'account_code'    => $this->id . "1001",
            'opening_balance' => "0.00",
            'bank_id'         => null,
            'bank_branch_id'  => null,
            'bank_account_no' => null,
        ]);
        $bankAccount->getAccount();
        return $bankAccount;
    }

    public function getAccount()
    {
        $bankAccount = $this->bankAccount()->first();
        $account     = $bankAccount ? $bankAccount->getAccount() : null;
        if ($account == null) {
            $this->createBankAccount();
            $this->load('bankAccount');
            return $this->bankAccount->getAccount();
        }
        return $account;
    }

    public function getCashAccount()
    {
        $bankAccount = $this->bankAccount()->first();
        if ($bankAccount == null) {
            $this->createBankAccount();
            $this->load('bankAccount');
            return $this->bankAccount;
        }
        return $bankAccount;
    }



    // ============================================
    // SALARY ACCOUNTS (5041, 2002)
    // ============================================

    public function getSalaryAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 5041)->first();
        if ($account == null) {
            $this->createSalaryAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 5041)->first();
    }

    public function createSalaryAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 5041)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Salaries & Allowances Expense - " . $this->full_name,
            "account_number"        => '5041' . $this->id,
            "account_group_id"      => 5,
            "account_control_id"    => 5040,
            "account_subsidiary_id" => 5041,
            "opening_balance"       => "0.00",
            "remarks"               => "A Salaries & Allowances Expense account is created for " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    public function getSalaryLiabilitieAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 2002)->first();
        if ($account == null) {
            $this->createSalaryLiabilitieAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 2002)->first();
    }

    public function createSalaryLiabilitieAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2002)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Salaries Payable - " . $this->full_name,
            "account_number"        => '2002' . $this->id,
            "account_group_id"      => 2,
            "account_control_id"    => 2000,
            "account_subsidiary_id" => 2002,
            "opening_balance"       => "0.00",
            "remarks"               => "A Salaries Payable account is created for " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    // ============================================
    // LOAN ACCOUNTS (1015)
    // ============================================

    public function getLoanReceivableAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 1015)->first();
        if ($account == null) {
            $this->createLoanReceivableAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 1015)->first();
    }

    public function createLoanReceivableAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 1015)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Loan Receivable - " . $this->full_name,
            "account_number"        => '1015' . $this->id,
            "account_group_id"      => 1,
            "account_control_id"    => 1000,
            "account_subsidiary_id" => 1015,
            "opening_balance"       => "0.00",
            "remarks"               => "A Loan Receivable account is created for " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    // ============================================
    // STAFF ADVANCE ACCOUNTS (2008)
    // ============================================

    public function getStaffAdvanceAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 2008)->first();
        if ($account == null) {
            $this->createStaffAdvanceAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 2008)->first();
    }

    public function createStaffAdvanceAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2008)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Staff Advance - " . $this->full_name,
            "account_number"        => '1022' . $this->id,
            "account_group_id"      => 1,
            "account_control_id"    => 1000,
            "account_subsidiary_id" => 1022,
            "opening_balance"       => "0.00",
            "remarks"               => "A Staff IOU account is created for " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    // ============================================
    // STAFF LOAN ACCOUNTS (2008)
    // ============================================

    public function getStaffLoanAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 2008)->first();
        if ($account == null) {
            $this->createStaffLoanAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 2008)->first();
    }

    public function createStaffLoanAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2008)->first() != null) {
            return;
        }
        $this->accounts()->create([

            "name" => "Staff Loan - " . $this->full_name,
            "account_number" => '2008' . $this->id,
            "account_group_id" => 1,
            "account_control_id" => 2000,
            "account_subsidiary_id" => 2008,
            "opening_balance" => "0.00",
            "remarks" => "A Staff Loan account is created for " . $this->full_name,
            "is_deletable" => 0,


        ]);
    }

    // ============================================
    // PETTY CASH ACCOUNTS (2009, 2015)
    // ============================================

    /**
     * Get Employee Cash Account (for petty cash transactions)
     * Account Subsidiary ID: 2009 (Asset Account - Current Asset)
     */
    public function getEmployeeCashAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 2009)->first();
        if ($account == null) {
            $this->createEmployeeCashAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 2009)->first();
    }

    /**
     * Create Employee Cash Account
     * This is an ASSET account (employees hold cash)
     */
    public function createEmployeeCashAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2009)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Employee Cash - " . $this->full_name,
            "account_number"        => '2009' . $this->id,
            "account_group_id"      => 1,    // Assets
            "account_control_id"    => 1000, // Current Assets
            "account_subsidiary_id" => 2009, // Employee Cash
            "opening_balance"       => "0.00",
            "remarks"               => "Employee cash account for petty cash - " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    /**
     * Get Petty Cash Payable Account
     * Account Subsidiary ID: 2015 (Liability Account)
     */
    public function getPettyCashPayableAccount()
    {
        $account = $this->accounts()->where('account_subsidiary_id', 2015)->first();
        if ($account == null) {
            $this->createPettyCashPayableAccount();
            $this->load('accounts');
        }
        return $this->accounts()->where('account_subsidiary_id', 2015)->first();
    }

    /**
     * Create Petty Cash Payable Account
     * This is a LIABILITY account (company owes employee for petty cash)
     */
    public function createPettyCashPayableAccount()
    {
        if ($this->accounts->where('account_subsidiary_id', 2015)->first() != null) {
            return;
        }
        $this->accounts()->create([
            "name"                  => "Petty Cash Payable - " . $this->full_name,
            "account_number"        => '2015' . $this->id,
            "account_group_id"      => 2,    // Liabilities
            "account_control_id"    => 2000, // Current Liabilities
            "account_subsidiary_id" => 2015, // Petty Cash Payable
            "opening_balance"       => "0.00",
            "remarks"               => "Petty cash payable account for " . $this->full_name,
            "is_deletable"          => 0,
        ]);
    }

    /**
     * Get or create Employee Cash Account for a given user (static method)
     * This is used for login user's cash account during petty cash payments
     *
     * @param \App\Models\User $user
     * @return \Modules\Account\Models\Account
     */
    public function getOrCreateLoginUserCashAccount($user)
    {
        // Try to get employee for this user
        $employee = self::where('user_id', $user->id)->first();

        if ($employee) {
            return $employee->getAccount();
        }
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
  
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


}
