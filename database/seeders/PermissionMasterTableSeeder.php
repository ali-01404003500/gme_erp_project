<?php
namespace Database\Seeders;

use App\Models\AccessControl\PermissionMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            //dashboard
            [
                'title'       => 'Dashboard',
                'description' => "Dashboard for user or admin ",
                'key'         => 'dashboard',
            ],

            //CMS
            [
                'title'       => 'CMS',
                'description' => "Permission of Add, Remove, Update, Delete CMS",
                'key'         => 'cms',
            ],

            //Document Entries
            [
                'title'       => 'Document Entries',
                'description' => "Permission of Add, Remove, Update, Delete Document Entries",
                'key'         => 'cms.document-entries',
                'parent_key'  => 'cms',
            ],

            //Application Entries
            [
                'title'       => 'Application Entries',
                'description' => "Permission of Add, Remove, Update, Delete Application Entries",
                'key'         => 'cms.application-entries',
                'parent_key'  => 'cms',
            ],

            //Document Types
            [
                'title'       => 'Document Types',
                'description' => "Permission of Add, Remove, Update, Delete Document Types",
                'key'         => 'cms.document-types',
                'parent_key'  => 'cms',
            ],
            //Document Head
            [
                'title'       => 'Document Heads',
                'description' => "Permission of Add, Remove, Update, Delete Document Heads",
                'key'         => 'cms.document-heads',
                'parent_key'  => 'cms',
            ],

            //CRM
            [
                'title'       => 'Crm',
                'description' => "Permission of Add, Remove, Update, Delete Crm",
                'key'         => 'crm',
            ],
            //customers
            [
                'title'       => 'Customer',
                'description' => "Permission of Add, Remove, Update, Delete Customer",
                'key'         => 'crm.customers',
                'parent_key'  => 'crm',
            ],
            //brokers
            [
                'title'       => 'Broker',
                'description' => "Permission of Add, Remove, Update, Delete Broker",
                'key'         => 'crm.brokers',
                'parent_key'  => 'crm',
            ],
            //daily-calls
            [
                'title'       => 'Daily Calls',
                'description' => 'Permission of Add, Remove, Update, Delete Daily Calls',
                'key'         => 'crm.daily-calls',
                'parent_key'  => 'crm',
            ],
            //daily-Credit-calls
            [
                'title'       => 'Daily Credit Calls',
                'description' => 'Permission of Add, Legal, Show Daily Credit Calls',
                'key'         => 'crm.daily-credit-calls',
                'parent_key'  => 'crm',
            ],
            
            //customer-types
            [
                'title'       => 'Customer Types',
                'description' => "Permission of Add, Remove, Update, Delete Customer Types",
                "key"         => "crm.customer-types",
                'parent_key'  => 'crm',
            ],
            //customer-ratings
            [
                'title'       => 'Customer Ratings',
                'description' => "Permission of Add, Remove, Update, Delete Customer Ratings",
                'key'         => 'crm.customer-ratings',
                'parent_key'  => 'crm',
            ],
            //customer-shippings
            [
                'title'       => 'Customer Shippings',
                'description' => "Permission of Add, Remove, Update, Delete Customer Shippings",
                'key'         => 'crm.customer-shippings',
                'parent_key'  => 'crm',
            ],

            //Reports
            [
                'title'       => 'Reports',
                'description' => 'Permission of View Reports',
                'key'         => 'crm.reports',
                'parent_key'  => 'crm',
            ],

            // hrm
            [
                'title'       => 'Hrm & Payroll',
                'description' => "Permission of Add, Remove, Update, Delete Hrm & Payroll",
                'key'         => 'hrm',
            ],
            //employees
            [
                'title'       => 'Employee',
                'description' => "Permission of Add, Remove, Update, Delete Employee",
                'key'         => 'hrm.employees',
                'parent_key'  => 'hrm',
            ],

            //department
            [
                'title'       => 'Departments',
                'description' => 'Permission of Add, Remove, Update, Delete Departments',
                'key'         => 'hrm.departments',
                'parent_key'  => 'hrm',
            ],

            //designation
            [
                'title'       => 'Designations',
                'description' => 'Permission of Add, Remove, Update, Delete Designations',
                'key'         => 'hrm.designations',
                'parent_key'  => 'hrm',
            ],

            //Attendance
            [
                'title'       => 'Attendance',
                'description' => "Permission of Add, Remove, Update, Delete Employee Attendance",
                'key'         => 'hrm.attendances',
                'parent_key'  => 'hrm',
            ],

            //Attendance Policy
            [
                'title'       => 'Attendance Policy',
                'description' => "Permission of Add, Remove, Update, Delete Attendance Policy",
                'key'         => 'hrm.attendance-policies',
                'parent_key'  => 'hrm',
            ],

            //Leave
            [
                'title'       => 'Leave',
                'description' => "Permission of Add, Remove, Update, Delete Employee Leave",
                'key'         => 'hrm.leaves',
                'parent_key'  => 'hrm',
            ],

            //Leave Application
            [
                'title'       => 'Leave Application',
                'description' => "Permission of Add, Remove, Update, Delete Employee Leave Application",
                'key'         => 'hrm.leaves',
                'parent_key'  => 'hrm',
            ],

            //leave types
            [
                'title'       => 'Leave Types',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Types',
                'key'         => 'hrm.leave-types',
                'parent_key'  => 'hrm',
            ],

            //leave group
            [
                'title'       => 'Leave Groups',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Groups',
                'key'         => 'hrm.leave-group',
                'parent_key'  => 'hrm',
            ],

            //leave Year
            [
                'title'       => 'Leave Years',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Years',
                'key'         => 'hrm.leave-year',
                'parent_key'  => 'hrm',
            ],

            //leave Approver Setup
            [
                'title'       => 'Leave Approver Setup',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Approver Setup',
                'key'         => 'hrm.leave-approver-setup',
                'parent_key'  => 'hrm',
            ],

            //leave status
            [
                'title'       => 'Leave Status',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Status',
                'key'         => 'hrm.leave-status',
                'parent_key'  => 'hrm',
            ],

            //leave eligible employee
            [
                'title'       => 'Leave Eligible Employees',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Eligible Employees',
                'key'         => 'hrm.leave-eligible-employee',
                'parent_key'  => 'hrm',
            ],

            //leave adjustment
            [
                'title'       => 'Leave Adjustments',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Adjustments',
                'key'         => 'hrm.leave-adjustment',
                'parent_key'  => 'hrm',
            ],

            //salary generate
            [
                'title'       => 'Payroll Generate',
                'description' => 'Permission of Add, Remove, Update, Delete Employee Salary',
                'key'         => 'hrm.salary-generates',
                'parent_key'  => 'hrm',
            ],

            //salary setup
            [
                'title'       => 'Salary Setup',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Setup',
                'key'         => 'hrm.salary-setups',
                'parent_key'  => 'hrm',
            ],

            //salary generation policy
            [
                'title'       => 'Salary Generation Policy',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Generation Policy',
                'key'         => 'hrm.salary-generation-policy',
                'parent_key'  => 'hrm',
            ],

            //salary deduction policy
            [
                'title'       => 'Salary Deduction Policy',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Deduction Policy',
                'key'         => 'hrm.salary-deduction-policy',
                'parent_key'  => 'hrm',
            ],

            //salary Signatory
            [
                'title'       => 'Salary Signatory',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Signatory',
                'key'         => 'hrm.salary-signatories',
                'parent_key'  => 'hrm',
            ],

            //Bills
            [
                'title'       => 'TA/DA',
                'description' => "Permission of Add, Remove, Update, Delete TA/DA",
                'key'         => 'hrm.bills',
                'parent_key'  => 'hrm',
            ],
            // daily visit plan
            [
                'title'       => 'Daily Visit Plans',
                'description' => 'Permission of Add, Remove, Update, Delete Daily Visit Plans',
                'key'         => 'hrm.daily-visit-plans',
                'parent_key'  => 'hrm',
            ],
            //loan
            [
                'title'       => 'Loans',
                'description' => 'Permission of Add, Remove, Update, Delete Loans',
                'key'         => 'hrm.loans',
                'parent_key'  => 'hrm',
            ],

            //kpi
            [
                'title'       => 'KPIs',
                'description' => 'Permission of Add, Remove, Update, Delete KPIs',
                'key'         => 'hrm.kpis',
                'parent_key'  => 'hrm',
            ],
            //monthly kpi template
            [
                'title'       => 'Monthly KPI Appraisals',
                'description' => 'Permission of Add, Remove, Update, Delete Monthly KPI Appraisals',
                'key'         => 'hrm.kpis.monthly-kpi-appraisals',
                'parent_key'  => 'hrm.kpis',
            ],
            //kpi assign
            [
                'title'       => 'KPI Assignments',
                'description' => 'Permission of Add, Remove, Update, Delete KPI Assignments',
                'key'         => 'hrm.kpis.kpi-assignments',
                'parent_key'  => 'hrm.kpis',
            ],

            //kpi template
            [
                'title'       => 'KPI Templates',
                'description' => 'Permission of Add, Remove, Update, Delete KPI Templates',
                'key'         => 'hrm.kpis.kpi-templates',
                'parent_key'  => 'hrm.kpis',
            ],

            //kpi suggestions
            [
                'title'       => 'Score Wise Suggestions',
                'description' => 'Permission of Add, Remove, Update, Delete Score Wise Suggestions',
                'key'         => 'hrm.kpis.score-wise-suggestions',
                'parent_key'  => 'hrm.kpis',
            ],
            //kpi responsibility
            [
                'title'       => 'Responsibility Entries',
                'description' => 'Permission of Add, Remove, Update, Delete Responsibility Entries',
                'key'         => 'hrm.kpis.responsibility-entries',
                'parent_key'  => 'hrm.kpis',
            ],

            //NoticeBoard
            [
                'title'       => 'NoticeBoard',
                'description' => "Permission of Add, Remove, Update, Delete NoticeBoard",
                'key'         => 'hrm.noticeboards',
                'parent_key'  => 'hrm',
            ],

            //TA/DA
            [
                'title'       => 'TA/DA',
                'description' => "Permission of Add, Remove, Update, Delete TA/DA",
                'key'         => 'hrm.bills',
                'parent_key'  => 'hrm',
            ],
            // PermissionMasterTableSeeder additions

            [
                'title'       => 'Daily Visit Plans',
                'description' => 'Permission of Add, Remove, Update, Delete Daily Visit Plans',
                'key'         => 'hrm.daily-visit-plans',
                'parent_key'  => 'hrm',
            ],
            [
                'title'       => 'Loans',
                'description' => 'Permission of Add, Remove, Update, Delete Loans',
                'key'         => 'hrm.loans',
                'parent_key'  => 'hrm',
            ],
            [
                'title'       => 'Salary Generates',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Generates',
                'key'         => 'hrm.salary-generates',
                'parent_key'  => 'hrm',
            ],
            [
                'title'       => 'Settings',
                'description' => 'Permission of Add, Remove, Update, Delete HRMS Settings',
                'key'         => 'hrm.settings',
                'parent_key'  => 'hrm',
            ],
            [
                'title'       => 'Leave Types',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Types',
                'key'         => 'hrm.leave-types',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Shifts',
                'description' => 'Permission of Add, Remove, Update, Delete Shifts',
                'key'         => 'hrm.settings.shifts',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Hotspots',
                'description' => 'Permission of Add, Remove, Update, Delete Hotspots',
                'key'         => 'hrm.settings.hotspots',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Holidays',
                'description' => 'Permission of Add, Remove, Update, Delete Holidays',
                'key'         => 'hrm.settings.holidays',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Notice Types',
                'description' => 'Permission of Add, Remove, Update, Delete Notice Types',
                'key'         => 'hrm.settings.notice-types',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Expense Types',
                'description' => 'Permission of Add, Remove, Update, Delete Expense Types',
                'key'         => 'hrm.settings.expense-types',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Transport Types',
                'description' => 'Permission of Add, Remove, Update, Delete Transport Types',
                'key'         => 'hrm.settings.transport-types',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title' => 'Loan Payments',
                'description' => 'Permission of Add, Remove, Update, Delete Loan Payments',
                'key' => 'account.payments.loan-payment',
                'parent_key' => 'account.payments',
            ],

            //Loan Payments
            [
                'title' => 'Fund Transfer',
                'description' => 'Permission of Add, Remove, Update, Delete, Verify, Approve Fund Transfer',
                'key' => 'account.fund-transfers',
                'parent_key' => 'accounts',
            ],

            //Vendor Bills
            [
                'title' => 'Vendor Bills',
                'description' => 'Permission of Add, Remove, Update, Delete Vendor Bills',
                'key' => 'account.vendor-bills',
                'parent_key' => 'accounts',
            ],
            //Vendor Bills Settings
            [
                'title' => 'Vendor Bills Settings',
                'description' => 'Permission of Add, Remove, Update, Delete Vendor Bills Settings',
                'key' => 'account.vendor-bills.settings',
                'parent_key' => 'account.vendor-bills',
            ],
            //Vendor Bills Generated
            [
                'title' => 'Generated Vendor Bills',
                'description' => 'Permission of Add, Remove, Update, Delete Generated Vendor Bills',
                'key' => 'account.vendor-bills.generated-vendor-bills',
                'parent_key' => 'account.vendor-bills',
            ],

            //I/O U Requisitions
            [
                'title' => 'I/O U Requisitions',
                'description' => 'Permission of Add, Remove, Update, Delete I/O U Requisitions',
                'key' => 'account.i-o-u-requisition',
                'parent_key' => 'accounts',
            ],
            //I/O U Requisitions Entries
            [
                'title' => 'I/O U Requisitions Entries',
                'description' => 'Permission of Add, Remove, Update, Delete I/O U Requisitions Entries',
                'key' => 'account.i-o-u-requisition.i-o-u-requisition-entries',
                'parent_key' => 'account.i-o-u-requisition',
            ],

            // Supplier Payments
            // [
            //     'title' => 'Supplier Payments',
            //     'description' => 'Permission of Add, Remove, Update, Delete Supplier Payments',
            //     'key' => 'account.payments.supplier-payments',
            //     'parent_key' => 'accounts',
            // ],

            //Account vouchers
            [
                'title' => 'Contra Vouchers',
                'description' => 'Permission of Add, Remove, Update, Delete Contra Vouchers',
                'key' => 'account.voucher-contras',
                'parent_key' => 'accounts',
            ],

            //Journal Vouchers
            [
                'title' => 'Journal Vouchers',
                'description' => 'Permission of Add, Remove, Update, Delete Journal Vouchers',
                'key' => 'account.voucher-journals',
                'parent_key' => 'accounts',
            ],
            //Payment Vouchers
            [
                'title' => 'Payment Vouchers',
                'description' => 'Permission of Add, Remove, Update, Delete Payment Vouchers',
                'key' => 'account.voucher-payments',
                'parent_key' => 'accounts',
            ],
            //Receiving Vouchers
            [
                'title' => 'Receiving Vouchers',
                'description' => 'Permission of Add, Remove, Update, Delete Receiving Vouchers',
                'key' => 'account.voucher-receives',
                'parent_key' => 'accounts',
            ],

            //Account Reports
            [
                'title' => 'Account Reports',
                'description' => 'Permission of Add, Remove, Update, Delete Account Reports',
                'key' => 'account.report',
                'parent_key' => 'accounts',
            ],

            // cash transafer 
            [
                'title' => 'Cash Transfer',
                'description' => 'Permission of Add, Remove, Update, Delete Cash Transfer',
                'key' => 'account.cash-transfers',
                'parent_key' => 'accounts',
            ],
 

            
           
            [
                'title' => 'Access Control',
                'description' => "Permission of Add, Remove, Update, Delete Users",
                'key' => 'access_control',
            ],
            [
                'title' => 'Role',
                'description' => "Permission of Add, Remove, Update, Delete Role",
                'key' => 'access_control.roles',
                'parent_key' => 'access_control',
            ],

            //verification.verification-requests
            [
                'title' => 'Verifications',
                'description' => 'Verification of One Time Permissions',
                'key' => 'verification',
            ],
            [
                'title'       => 'Designations',
                'description' => 'Permission of Add, Remove, Update, Delete Designations',
                'key'         => 'hrm.settings.designations',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Salary Setups',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Setups',
                'key'         => 'hrm.salary-setups',
                'parent_key'  => 'hrm.settings',
            ],
            [
                'title'       => 'Appraisal Policies',
                'description' => 'Permission of Add, Remove, Update, Delete Appraisal Policies',
                'key'         => 'hrm.settings.appraisal-policies',
                'parent_key'  => 'hrm.settings',
            ],
            

            
 
           
            
          
           
            
            





        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('permissions')->truncate();
        PermissionMaster::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($data as $value) {
            if (isset($value['parent_key'])) {
                $parent = PermissionMaster::where('key', $value['parent_key'])->first();
                if ($parent == null) {
                    dd($value['parent_key']);
                }
            }
        }
    }
