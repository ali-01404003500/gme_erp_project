<?php

namespace Database\Seeders;

use App\Models\AccessControl\Permission;
use App\Models\AccessControl\PermissionMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Permission for every menus where need to inserting
         * Here is permissions structure
         * name: it permission name that show in access control as title
         * slug: a unique string that identify every permission separately it also should match with route name
         * description: A sort description about permission
         * key: every permission part of permission master. key is the master key that holding all permission
         */
        $permissions = [
            // Users Crud
            [
                'name' => 'Create Role',
                'slug' => 'access_control.roles.create',
                'description' => 'Role create permission',
                'key' => 'access_control.roles'
            ],
            [
                'name' => 'Role List',
                'slug' => 'access_control.roles.index',
                'description' => 'Role list permission',
                'key' => 'access_control.roles'
            ],

            [
                'name' => 'Update Role',
                'slug' => 'access_control.roles.update',
                'description' => 'Role update permission',
                'key' => 'access_control.roles'
            ],

            [
                'name' => 'Delete Role',
                'slug' => 'access_control.roles.destroy',
                'description' => 'Role delete permission',
                'key' => 'access_control.roles'
            ],
            //Global Settings
            [
                'name' => 'Global Settings',
                'slug' => 'access_control.global-settings.update',
                'description' => 'Global Settings permission',
                'key' => 'access_control.global-settings'
            ],


            //verification.verification-requests
            [
                'name' => 'Verification Requests',
                'slug' => 'verification.verification-requests',
                'description' => 'Verification Requests permission',
                'key' => 'verification'
            ],
            // 📂 Document Types
            [
                'name'=> 'Create Document Type',
                'slug'=> 'cms.document-types.create',
                'description'=> 'Permission to create document types',
                'key'=> 'cms.document-types',
            ],
            [
                'name'=> 'List Document Types',
                'slug'=> 'cms.document-types.index',
                'description'=> 'Permission to view document types list',
                'key'=> 'cms.document-types',
            ],
            [
                'name'=> 'Edit Document Type',
                'slug'=> 'cms.document-types.update',
                'description'=> 'Permission to edit document types',
                'key'=> 'cms.document-types',
            ],
            [
                'name'=> 'Delete Document Type',
                'slug'=> 'cms.document-types.destroy',
                'description'=> 'Permission to delete document types',
                'key'=> 'cms.document-types',
            ],

            // 📂 Document Heads
            [
                'name'=> 'Create Document Head',
                'slug'=> 'cms.document-heads.create',
                'description'=> 'Permission to create document heads',
                'key'=> 'cms.document-heads',
            ],
            [
                'name'=> 'List Document Heads',
                'slug'=> 'cms.document-heads.index',
                'description'=> 'Permission to view document heads list',
                'key'=> 'cms.document-heads',
            ],
            [
                'name'=> 'Edit Document Head',
                'slug'=> 'cms.document-heads.update',
                'description'=> 'Permission to edit document heads',
                'key'=> 'cms.document-heads',
            ],
            [
                'name'=> 'Delete Document Head',
                'slug'=> 'cms.document-heads.destroy',
                'description'=> 'Permission to delete document heads',
                'key'=> 'cms.document-heads',
            ],

            // 📂 Document Entries
            [
                'name'=> 'Create Document Entry',
                'slug'=> 'cms.document-entries.create',
                'description'=> 'Permission to create document entries',
                'key'=> 'cms.document-entries',
            ],
            [
                'name'=> 'List Document Entries',
                'slug'=> 'cms.document-entries.index',
                'description'=> 'Permission to view document entries list',
                'key'=> 'cms.document-entries',
            ],
            [
                'name'=> 'Edit Document Entry',
                'slug'=> 'cms.document-entries.update',
                'description'=> 'Permission to edit document entries',
                'key'=> 'cms.document-entries',
            ],
            [
                'name'=> 'Delete Document Entry',
                'slug'=> 'cms.document-entries.destroy',
                'description'=> 'Permission to delete document entries',
                'key'=> 'cms.document-entries',
            ],
            

            // 📂 Application Entries
            [
                'name'=> 'Create Application Entry',
                'slug'=> 'cms.application-entries.create',
                'description'=> 'Permission to create application entries',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'List Application Entries',
                'slug'=> 'cms.application-entries.index',
                'description'=> 'Permission to view application entries list',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'Edit Application Entry',
                'slug'=> 'cms.application-entries.update',
                'description'=> 'Permission to edit application entries',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'Delete Application Entry',
                'slug'=> 'cms.application-entries.destroy',
                'description'=> 'Permission to delete application entries',
                'key'=> 'cms.application-entries',
            ],

            // ✅ Custom Application Entry Actions
            [
                'name'=> 'Approve Application Entry',
                'slug'=> 'cms.application-entries.approved',
                'description'=> 'Permission to approve application entries',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'Handover Application Entry',
                'slug'=> 'cms.application-entries.handover',
                'description'=> 'Permission to handover application entries',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'Receive Application Entry',
                'slug'=> 'cms.application-entries.received',
                'description'=> 'Permission to mark application entries as received',
                'key'=> 'cms.application-entries',
            ],
            [
                'name'=> 'Deny Application Entry',
                'slug'=> 'cms.application-entries.deny',
                'description'=> 'Permission to deny application entries',
                'key'=> 'cms.application-entries',
            ],


            //employees
            [
                'name'=> 'Create Employee',
                'slug'=> 'hrm.employees.create',
                'description'=> 'Employee create permission',
                'key'=> 'hrm.employees'
            ],

            [
                'name'=> 'Employee List',
                'slug'=> 'hrm.employees.index',
                'description'=> 'Employee list permission',
                'key'=> 'hrm.employees'
            ],

            [
                'name'=> 'Employee Update',
                'slug'=> 'hrm.employees.update',
                'description'=> 'Employee update permission',
                'key'=> 'hrm.employees'
            ],

            [
                'name'=> 'Employee View',
                'slug'=> 'hrm.employees.show',
                'description'=> 'Employee show permission',
                'key'=> 'hrm.employees'
            ],

            [
                'name'=> 'Employee Delete',
                'slug'=> 'hrm.employees.destroy',
                'description'=> 'Employee delete permission',
                'key'=> 'hrm.employees'
            ],

            
            //attendance
            [
                'name'=> 'Attendance List',
                'slug'=> 'hrm.attendances.index',
                'description'=> 'Attendance list permission',
                'key'=> 'hrm.attendances'
            ],

            [
                'name'=> 'Create Attendance',
                'slug'=> 'hrm.attendances.create',
                'description'=> 'Attendance create permission',
                'key'=> 'hrm.attendances'
            ],

            [
                'name'=> 'Attendance Update',
                'slug'=> 'hrm.attendances.update',
                'description'=> 'Attendance update permission',
                'key'=> 'hrm.attendances'
            ],


            [
                'name'=> 'Attendance Delete',
                'slug'=> 'hrm.attendances.destroy',
                'description'=> 'Attendance delete permission',
                'key'=> 'hrm.attendances'
            ],

            
            //leave application
            [
                'name'=> 'Leave Application List',
                'slug'=> 'hrm.leaves.index',
                'description'=> 'Leave application list permission',
                'key'=> 'hrm.leaves'
            ],

            [
                'name'=> 'Create Leave Application',
                'slug'=> 'hrm.leaves.create',
                'description'=> 'Leave application create permission',
                'key'=> 'hrm.leaves'
            ],

            [
                'name'=> 'Leave Application Update',
                'slug'=> 'hrm.leaves.update',
                'description'=> 'Leave application update permission',
                'key'=> 'hrm.leaves'
            ],



            [
                'name'=> 'Leave Application Delete',
                'slug'=> 'hrm.leaves.destroy',
                'description'=> 'Leave application delete permission',
                'key'=> 'hrm.leaves'
            ],
            [
                'name'=> 'Leave Application Recommended',
                'slug'=> 'hrm.leaves.recommended',
                'description'=> 'Leave application recommended permission',
                'key'=> 'hrm.leaves'
            ],

            [
                'name'=> 'Leave Application Approved',
                'slug'=> 'hrm.leaves.approved',
                'description'=> 'Leave application approved permission',
                'key'=> 'hrm.leaves'
            ],

            //notice board
            [
                'name'=> 'Notice Board List',
                'slug'=> 'hrm.noticeboards.index',
                'description'=> 'Notice board list permission',
                'key'=> 'hrm.noticeboards'
            ],

            [
                'name'=> 'Create Notice Board',
                'slug'=> 'hrm.noticeboards.create',
                'description'=> 'Notice board create permission',
                'key'=> 'hrm.noticeboards'
            ],

            [
                'name'=> 'Notice Board Update',
                'slug'=> 'hrm.noticeboards.update',
                'description'=> 'Notice board update permission',
                'key'=> 'hrm.noticeboards'
            ],

            [
                'name'=> 'Notice Board View',
                'slug'=> 'hrm.noticeboards.show',
                'description'=> 'Notice board show permission',
                'key'=> 'hrm.noticeboards'
            ],

            [
                'name'=> 'Notice Board Delete',
                'slug'=> 'hrm.noticeboards.destroy',
                'description'=> 'Notice board delete permission',
                'key'=> 'hrm.noticeboards'
            ],

            //TA/DA
            [
                'name'=> 'TA/DA List',
                'slug'=> 'hrm.bills.index',
                'description'=> 'TA/DA list permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'Create TA/DA',
                'slug'=> 'hrm.bills.create',
                'description'=> 'TA/DA create permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA Update',
                'slug'=> 'hrm.bills.update',
                'description'=> 'TA/DA update permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA View',
                'slug'=> 'hrm.bills.show',
                'description'=> 'TA/DA show permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA Delete',
                'slug'=> 'hrm.bills.destroy',
                'description'=> 'TA/DA delete permission',
                'key'=> 'hrm.bills'
            ],
            [
                'name'=> 'TA/DA Team Leader Verify',
                'slug'=> 'hrm.bills.team_leader_verify',
                'description'=> 'TA/DA team leader verify permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA HR/Accounts Verify',
                'slug'=> 'hrm.bills.accounts_verify',
                'description'=> 'TA/DA HR/accounts verify permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA Final Approve',
                'slug'=> 'hrm.bills.final_approve',
                'description'=> 'TA/DA final approve permission',
                'key'=> 'hrm.bills'
            ],

            [
                'name'=> 'TA/DA Verify',
                'slug'=> 'hrm.bills.verify',
                'description'=> 'TA/DA Verification List',
                'key'=> 'hrm.bills'
            ],

    
            // PermissionTableSeeder additions
            // Employee Salary
            
            [
                'name' => 'Create Employee Salary',
                'slug' => 'hrm.employee-salarys.create',
                'description' => 'Employee salary create permission',
                'key' => 'hrm.employee-salarys',
            ],
            // Daily Visit Plans
            [
                'name' => 'Daily Visit Plans List',
                'slug' => 'hrm.daily-visit-plans.index',
                'description' => 'Daily visit plans list permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Create Daily Visit Plan',
                'slug' => 'hrm.daily-visit-plans.create',
                'description' => 'Daily visit plan create permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Daily Visit Plan Update',
                'slug' => 'hrm.daily-visit-plans.update',
                'description' => 'Daily visit plan update permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Daily Visit Plan View',
                'slug' => 'hrm.daily-visit-plans.show',
                'description' => 'Daily visit plan show permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Daily Visit Plan Delete',
                'slug' => 'hrm.daily-visit-plans.destroy',
                'description' => 'Daily visit plan delete permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Daily Visit Plan Approve',
                'slug' => 'hrm.daily-visit-plans.approve',
                'description' => 'Daily visit plan approve permission',
                'key' => 'hrm.daily-visit-plans',
            ],
            [
                'name' => 'Daily Visit Plan Deny',
                'slug' => 'hrm.daily-visit-plans.deny',
                'description' => 'Daily visit plan deny permission',
                'key' => 'hrm.daily-visit-plans',
            ],

            // Loans
            [
                'name' => 'Loans List',
                'slug' => 'hrm.loans.index',
                'description' => 'Loans list permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Create Loan',
                'slug' => 'hrm.loans.create',
                'description' => 'Loan create permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Loan Update',
                'slug' => 'hrm.loans.update',
                'description' => 'Loan update permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Loan View',
                'slug' => 'hrm.loans.show',
                'description' => 'Loan show permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Loan Delete',
                'slug' => 'hrm.loans.destroy',
                'description' => 'Loan delete permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Loan Approve',
                'slug' => 'hrm.loans.approve',
                'description' => 'Loan approve permission',
                'key' => 'hrm.loans',
            ],
            [
                'name' => 'Loan Deny',
                'slug' => 'hrm.loans.deny',
                'description' => 'Loan deny permission',
                'key' => 'hrm.loans',
            ],
            
            // Salary 
            [
                'name' => 'Payrolls View',
                'slug' => 'hrm.payrolls',
                'description' => 'Payrolls view permission',
                'key' => 'hrm.salary-generates',
            ],
            [
                'name' => 'Salary Generates List',
                'slug' => 'hrm.salary-generates.index',
                'description' => 'Salary generates list permission',
                'key' => 'hrm.salary-generates',
            ],
            [
                'name' => 'Create Salary Generate',
                'slug' => 'hrm.salary-generates.create',
                'description' => 'Salary generate create permission',
                'key' => 'hrm.salary-generates',
            ],
            [
                'name' => 'Salary Generate Update',
                'slug' => 'hrm.salary-generates.update',
                'description' => 'Salary generate update permission',
                'key' => 'hrm.salary-generates',
            ],
            [
                'name' => 'Salary Generate View',
                'slug' => 'hrm.salary-generates.show',
                'description' => 'Salary generate show permission',
                'key' => 'hrm.salary-generates',
            ],
           
            
            // Settings: Leave Types
            [
                'name' => 'Leave Types List',
                'slug' => 'hrm.settings.leave-types.index',
                'description' => 'Leave types list permission',
                'key' => 'hrm.settings.leave-types',
            ],
            [
                'name' => 'Create Leave Type',
                'slug' => 'hrm.settings.leave-types.create',
                'description' => 'Leave type create permission',
                'key' => 'hrm.settings.leave-types',
            ],
            [
                'name' => 'Leave Type Update',
                'slug' => 'hrm.settings.leave-types.update',
                'description' => 'Leave type update permission',
                'key' => 'hrm.settings.leave-types',
            ],
            [
                'name' => 'Leave Type Delete',
                'slug' => 'hrm.settings.leave-types.destroy',
                'description' => 'Leave type delete permission',
                'key' => 'hrm.settings.leave-types',
            ],

            // Settings: Shifts
            [
                'name' => 'Shifts List',
                'slug' => 'hrm.settings.shifts.index',
                'description' => 'Shifts list permission',
                'key' => 'hrm.settings.shifts',
            ],
            [
                'name' => 'Create Shift',
                'slug' => 'hrm.settings.shifts.create',
                'description' => 'Shift create permission',
                'key' => 'hrm.settings.shifts',
            ],
            [
                'name' => 'Shift Update',
                'slug' => 'hrm.settings.shifts.update',
                'description' => 'Shift update permission',
                'key' => 'hrm.settings.shifts',
            ],
            [
                'name' => 'Shift Delete',
                'slug' => 'hrm.settings.shifts.destroy',
                'description' => 'Shift delete permission',
                'key' => 'hrm.settings.shifts',
            ],

            // Settings: Holidays
            [
                'name' => 'Holidays List',
                'slug' => 'hrm.settings.holidays.index',
                'description' => 'Holidays list permission',
                'key' => 'hrm.settings.holidays',
            ],
            [
                'name' => 'Create Holiday',
                'slug' => 'hrm.settings.holidays.create',
                'description' => 'Holiday create permission',
                'key' => 'hrm.settings.holidays',
            ],
            [
                'name' => 'Holiday Update',
                'slug' => 'hrm.settings.holidays.update',
                'description' => 'Holiday update permission',
                'key' => 'hrm.settings.holidays',
            ],

            [
                'name' => 'Holiday Delete',
                'slug' => 'hrm.settings.holidays.destroy',
                'description' => 'Holiday delete permission',
                'key' => 'hrm.settings.holidays',
            ],

            // Settings: Notice Types
            [
                'name' => 'Notice Types List',
                'slug' => 'hrm.settings.notice-types.index',
                'description' => 'Notice types list permission',
                'key' => 'hrm.settings.notice-types',
            ],
            [
                'name' => 'Create Notice Type',
                'slug' => 'hrm.settings.notice-types.create',
                'description' => 'Notice type create permission',
                'key' => 'hrm.settings.notice-types',
            ],
            [
                'name' => 'Notice Type Update',
                'slug' => 'hrm.settings.notice-types.update',
                'description' => 'Notice type update permission',
                'key' => 'hrm.settings.notice-types',
            ],
            [
                'name' => 'Notice Type Delete',
                'slug' => 'hrm.settings.notice-types.destroy',
                'description' => 'Notice type delete permission',
                'key' => 'hrm.settings.notice-types',
            ],

            // Settings: Expense Types
            [
                'name' => 'Expense Types List',
                'slug' => 'hrm.settings.expense-types.index',
                'description' => 'Expense types list permission',
                'key' => 'hrm.settings.expense-types',
            ],
            [
                'name' => 'Create Expense Type',
                'slug' => 'hrm.settings.expense-types.create',
                'description' => 'Expense type create permission',
                'key' => 'hrm.settings.expense-types',
            ],
            [
                'name' => 'Expense Type Update',
                'slug' => 'hrm.settings.expense-types.update',
                'description' => 'Expense type update permission',
                'key' => 'hrm.settings.expense-types',
            ],
            [
                'name' => 'Expense Type Delete',
                'slug' => 'hrm.settings.expense-types.destroy',
                'description' => 'Expense type delete permission',
                'key' => 'hrm.settings.expense-types',
            ],

            // Settings: Transport Types
            [
                'name' => 'Transport Types List',
                'slug' => 'hrm.settings.transport-types.index',
                'description' => 'Transport types list permission',
                'key' => 'hrm.settings.transport-types',
            ],
            [
                'name' => 'Create Transport Type',
                'slug' => 'hrm.settings.transport-types.create',
                'description' => 'Transport type create permission',
                'key' => 'hrm.settings.transport-types',
            ],
            [
                'name' => 'Transport Type Update',
                'slug' => 'hrm.settings.transport-types.update',
                'description' => 'Transport type update permission',
                'key' => 'hrm.settings.transport-types',
            ],
            [
                'name' => 'Transport Type Delete',
                'slug' => 'hrm.settings.transport-types.destroy',
                'description' => 'Transport type delete permission',
                'key' => 'hrm.settings.transport-types',
            ],

            // Settings: Departments
            [
                'name' => 'Departments List',
                'slug' => 'hrm.settings.departments.index',
                'description' => 'Departments list permission',
                'key' => 'hrm.settings.departments',
            ],
            [
                'name' => 'Create Department',
                'slug' => 'hrm.settings.departments.create',
                'description' => 'Department create permission',
                'key' => 'hrm.settings.departments',
            ],
            [
                'name' => 'Department Update',
                'slug' => 'hrm.settings.departments.update',
                'description' => 'Department update permission',
                'key' => 'hrm.settings.departments',
            ],
            [
                'name' => 'Department Delete',
                'slug' => 'hrm.settings.departments.destroy',
                'description' => 'Department delete permission',
                'key' => 'hrm.settings.departments',
            ],

            // Settings: Designations
            [
                'name' => 'Designations List',
                'slug' => 'hrm.settings.designations.index',
                'description' => 'Designations list permission',
                'key' => 'hrm.settings.designations',
            ],
            [
                'name' => 'Create Designation',
                'slug' => 'hrm.settings.designations.create',
                'description' => 'Designation create permission',
                'key' => 'hrm.settings.designations',
            ],
            [
                'name' => 'Designation Update',
                'slug' => 'hrm.settings.designations.update',
                'description' => 'Designation update permission',
                'key' => 'hrm.settings.designations',
            ],
            [
                'name' => 'Designation Delete',
                'slug' => 'hrm.settings.designations.destroy',
                'description' => 'Designation delete permission',
                'key' => 'hrm.settings.designations',
            ],

            // Settings: Salary Setups
            [
                'name' => 'Salary Setups List',
                'slug' => 'hrm.settings.salary-setups.index',
                'description' => 'Salary setups list permission',
                'key' => 'hrm.settings.salary-setups',
            ],
            [
                'name' => 'Create Salary Setup',
                'slug' => 'hrm.settings.salary-setups.create',
                'description' => 'Salary setup create permission',
                'key' => 'hrm.settings.salary-setups',
            ],
            [
                'name' => 'Salary Setup Update',
                'slug' => 'hrm.settings.salary-setups.update',
                'description' => 'Salary setup update permission',
                'key' => 'hrm.settings.salary-setups',
            ],

            [
                'name' => 'Salary Setup Delete',
                'slug' => 'hrm.settings.salary-setups.destroy',
                'description' => 'Salary setup delete permission',
                'key' => 'hrm.settings.salary-setups',
            ],

            // Settings: Appraisal Policies
            [
                'name' => 'Appraisal Policies List',
                'slug' => 'hrm.settings.appraisal-policies.index',
                'description' => 'Appraisal policies list permission',
                'key' => 'hrm.settings.appraisal-policies',
            ],
            [
                'name' => 'Create Appraisal Policy',
                'slug' => 'hrm.settings.appraisal-policies.create',
                'description' => 'Appraisal policy create permission',
                'key' => 'hrm.settings.appraisal-policies',
            ],
            [
                'name' => 'Appraisal Policy Update',
                'slug' => 'hrm.settings.appraisal-policies.update',
                'description' => 'Appraisal policy update permission',
                'key' => 'hrm.settings.appraisal-policies',
            ],
            [
                'name' => 'Appraisal Policy Delete',
                'slug' => 'hrm.settings.appraisal-policies.destroy',
                'description' => 'Appraisal policy delete permission',
                'key' => 'hrm.settings.appraisal-policies',
            ],

            // KPIs: KPI Setups
            // Score Wise Suggestions
            [
                'name' => 'Score Wise Suggestions List',
                'slug' => 'hrm.kpis.score-wise-suggestions.index',
                'description' => 'Score wise suggestions list permission',
                'key' => 'hrm.kpis.score-wise-suggestions',
            ],
            [
                'name' => 'Create Score Wise Suggestion',
                'slug' => 'hrm.kpis.score-wise-suggestions.create',
                'description' => 'Score wise suggestion create permission',
                'key' => 'hrm.kpis.score-wise-suggestions',
            ],
            [
                'name' => 'Score Wise Suggestion Update',
                'slug' => 'hrm.kpis.score-wise-suggestions.update',
                'description' => 'Score wise suggestion update permission',
                'key' => 'hrm.kpis.score-wise-suggestions',
            ],
            [
                'name' => 'Score Wise Suggestion Delete',
                'slug' => 'hrm.kpis.score-wise-suggestions.destroy',
                'description' => 'Score wise suggestion delete permission',
                'key' => 'hrm.kpis.score-wise-suggestions',
            ],

            // Responsibility Entries
            [
                'name' => 'Responsibility Entries List',
                'slug' => 'hrm.kpis.responsibility-entries.index',
                'description' => 'Responsibility entries list permission',
                'key' => 'hrm.kpis.responsibility-entries',
            ],
            [
                'name' => 'Create Responsibility Entry',
                'slug' => 'hrm.kpis.responsibility-entries.create',
                'description' => 'Responsibility entry create permission',
                'key' => 'hrm.kpis.responsibility-entries',
            ],
            [
                'name' => 'Responsibility Entry Update',
                'slug' => 'hrm.kpis.responsibility-entries.update',
                'description' => 'Responsibility entry update permission',
                'key' => 'hrm.kpis.responsibility-entries',
            ],
            [
                'name' => 'Responsibility Entry Delete',
                'slug' => 'hrm.kpis.responsibility-entries.destroy',
                'description' => 'Responsibility entry delete permission',
                'key' => 'hrm.kpis.responsibility-entries',
            ],

            // KPI Templates
            [
                'name' => 'KPI Templates List',
                'slug' => 'hrm.kpis.kpi-templates.index',
                'description' => 'KPI templates list permission',
                'key' => 'hrm.kpis.kpi-templates',
            ],
            [
                'name' => 'Create KPI Template',
                'slug' => 'hrm.kpis.kpi-templates.create',
                'description' => 'KPI template create permission',
                'key' => 'hrm.kpis.kpi-templates',
            ],
            [
                'name' => 'KPI Template Update',
                'slug' => 'hrm.kpis.kpi-templates.update',
                'description' => 'KPI template update permission',
                'key' => 'hrm.kpis.kpi-templates',
            ],
            [
                'name' => 'KPI Template View',
                'slug' => 'hrm.kpis.kpi-templates.show',
                'description' => 'KPI template show permission',
                'key' => 'hrm.kpis.kpi-templates',
            ],
            [
                'name' => 'KPI Template Delete',
                'slug' => 'hrm.kpis.kpi-templates.destroy',
                'description' => 'KPI template delete permission',
                'key' => 'hrm.kpis.kpi-templates',
            ],

            // KPI Assignments
            [
                'name' => 'KPI Assignments List',
                'slug' => 'hrm.kpis.kpi-assignments.index',
                'description' => 'KPI assignments list permission',
                'key' => 'hrm.kpis.kpi-assignments',
            ],
            [
                'name' => 'Create KPI Assignment',
                'slug' => 'hrm.kpis.kpi-assignments.create',
                'description' => 'KPI assignment create permission',
                'key' => 'hrm.kpis.kpi-assignments',
            ],
            [
                'name' => 'KPI Assignment Update',
                'slug' => 'hrm.kpis.kpi-assignments.update',
                'description' => 'KPI assignment update permission',
                'key' => 'hrm.kpis.kpi-assignments',
            ],
            [
                'name' => 'KPI Assignment View',
                'slug' => 'hrm.kpis.kpi-assignments.show',
                'description' => 'KPI assignment show permission',
                'key' => 'hrm.kpis.kpi-assignments',
            ],
            [
                'name' => 'KPI Assignment Delete',
                'slug' => 'hrm.kpis.kpi-assignments.destroy',
                'description' => 'KPI assignment delete permission',
                'key' => 'hrm.kpis.kpi-assignments',
            ],

            // Monthly KPI Appraisals
            [
                'name' => 'Monthly KPI Appraisals List',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.index',
                'description' => 'Monthly KPI appraisals list permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Create Monthly KPI Appraisal',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.create',
                'description' => 'Monthly KPI appraisal create permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Monthly KPI Appraisal Update',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.update',
                'description' => 'Monthly KPI appraisal update permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Monthly KPI Appraisal View',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.show',
                'description' => 'Monthly KPI appraisal show permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Monthly KPI Appraisal Delete',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.destroy',
                'description' => 'Monthly KPI appraisal delete permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Monthly KPI Appraisal Approve',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.approve',
                'description' => 'Monthly KPI appraisal approve permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],
            [
                'name' => 'Monthly KPI Appraisal Reject',
                'slug' => 'hrm.kpis.monthly-kpi-appraisals.reject',
                'description' => 'Monthly KPI appraisal reject permission',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
            ],

            // Jobs
            [
                'name' => 'Jobs List',
                'slug' => 'hrm.jobs.index',
                'description' => 'Jobs list permission',
                'key' => 'hrm.jobs',
            ],
            [
                'name' => 'Create Job',
                'slug' => 'hrm.jobs.create',
                'description' => 'Job create permission',
                'key' => 'hrm.jobs',
            ],
            [
                'name' => 'Job Update',
                'slug' => 'hrm.jobs.update',
                'description' => 'Job update permission',
                'key' => 'hrm.jobs',
            ],
            [
                'name' => 'Job View',
                'slug' => 'hrm.jobs.show',
                'description' => 'Job show permission',
                'key' => 'hrm.jobs',
            ],
            [
                'name' => 'Job Delete',
                'slug' => 'hrm.jobs.destroy',
                'description' => 'Job delete permission',
                'key' => 'hrm.jobs',
            ],

            // Job Templates
            [
                'name' => 'Job Templates List',
                'slug' => 'hrm.job-templates.index',
                'description' => 'Job templates list permission',
                'key' => 'hrm.job-templates',
            ],
            [
                'name' => 'Create Job Template',
                'slug' => 'hrm.job-templates.create',
                'description' => 'Job template create permission',
                'key' => 'hrm.job-templates',
            ],
            [
                'name' => 'Job Template Update',
                'slug' => 'hrm.job-templates.update',
                'description' => 'Job template update permission',
                'key' => 'hrm.job-templates',
            ],
           
            [
                'name' => 'Job Template Delete',
                'slug' => 'hrm.job-templates.destroy',
                'description' => 'Job template delete permission',
                'key' => 'hrm.job-templates',
            ],

            // Job Applications
            [
                'name' => 'Job Applications List',
                'slug' => 'hrm.job-applications.index',
                'description' => 'Job applications list permission',
                'key' => 'hrm.job-applications',
            ],
            
           
            [
                'name' => 'Job Application View',
                'slug' => 'hrm.job-applications.show',
                'description' => 'Job application show permission',
                'key' => 'hrm.job-applications',
            ],
            [
                'name' => 'Job Application Delete',
                'slug' => 'hrm.job-applications.destroy',
                'description' => 'Job application delete permission',
                'key' => 'hrm.job-applications',
            ],
            [
                'name' => 'Job Application Approve',
                'slug' => 'hrm.job-applications.update-status',
                'description' => 'Job application update status permission',
                'key' => 'hrm.job-applications',
            ],

            // Reports
            [
                'name' => 'Daily Attendance Report',
                'slug' => 'hrm.reports.daily-attendance-report',
                'description' => 'Daily attendance report view permission',
                'key' => 'hrm.reports',
            ],
            [
                'name' => 'Monthly Attendance Report',
                'slug' => 'hrm.reports.monthly-attendance-report',
                'description' => 'Monthly attendance report view permission',
                'key' => 'hrm.reports',
            ],

           
            // Custom Employee Routes
            [
                'name' => 'Employee Import',
                'slug' => 'hrm.employees.import',
                'description' => 'Employee import permission',
                'key' => 'hrm.employees',
            ],
            


           

            //customers
            [
                'name'=> 'Create Customer',
                'slug'=> 'crm.customers.create',
                'description'=> 'Customer create permission',
                'key'=> 'crm.customers'
            ],

            [
                'name'=> 'Customer List',
                'slug'=> 'crm.customers.index',
                'description'=> 'Customer list permission',
                'key'=> 'crm.customers'
            ],

            [
                'name'=> 'Customer Update',
                'slug'=> 'crm.customers.update',
                'description'=> 'Customer update permission',
                'key'=> 'crm.customers'
            ],

            [
                'name'=> 'Customer View',
                'slug'=> 'crm.customers.show',
                'description'=> 'Customer show permission',
                'key'=> 'crm.customers'
            ],

            [
                'name'=> 'Customer Delete',
                'slug'=> 'crm.customers.destroy',
                'description'=> 'Customer delete permission',
                'key'=> 'crm.customers'
            ],
            // crm.customers.approve
            // crm.customers.destroy
            // crm.customers.settings

            [
                'name'   => "Customer Approve",
                'slug'   => "crm.customers.approve",
                'description' => "Customer approve permission",
                'key'    => "crm.customers"
            ],

            [
                'name'   => "Customer Deny",
                'slug'   => "crm.customers.deny",
                'description' => "Customer deny permission",
                'key'    => "crm.customers"
            ]
            ,
            [
                'name'   => "Customer Settings",
                'slug'   => "crm.customers.settings",
                'description' => "Customer settings permission",
                'key'    => "crm.customers"
            ],
            
            // Brokers
            [
                'name' => 'Create Broker',
                'slug' => 'crm.brokers.create',
                'description' => 'Broker create permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker List',
                'slug' => 'crm.brokers.index',
                'description' => 'Broker list permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker Update',
                'slug' => 'crm.brokers.update',
                'description' => 'Broker update permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker View',
                'slug' => 'crm.brokers.show',
                'description' => 'Broker show permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker Delete',
                'slug' => 'crm.brokers.destroy',
                'description' => 'Broker delete permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker Approve',
                'slug' => 'crm.brokers.approve',
                'description' => 'Broker approve permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker Deny',
                'slug' => 'crm.brokers.deny',
                'description' => 'Broker deny permission',
                'key' => 'crm.brokers'
            ],

            [
                'name' => 'Broker Settings',
                'slug' => 'crm.brokers.settings',
                'description' => 'Broker settings permission',
                'key' => 'crm.brokers'
            ],

            //Customer Ratings
            [
                'name' => 'Create Rating',
                'slug' => 'crm.customer-ratings.create',
                'description' => 'Customer Rating create permission',
                'key' => 'crm.customer-ratings'
            ],

            [
                'name' => 'Rating List',
                'slug' => 'crm.customer-ratings.index',
                'description' => 'Customer Rating list permission',
                'key' => 'crm.customer-ratings'
            ],

            [
                'name' => 'Rating Update',
                'slug' => 'crm.customer-ratings.update',
                'description'=> 'Customer Rating update permission',
                'key' => 'crm.customer-ratings'
            ],

            [
                'name' => 'Rating View',
                'slug'=> 'crm.customer-ratings.show',
                'description'=> 'Customer Rating show permission',
                'key' => 'crm.customer-ratings'
            ],

            [
                'name'=> 'Rating Delete',
                'slug'=> 'crm.customer-ratings.destroy',
                'description'=> 'Customer Rating delete permission',
                'key'=> 'crm.customer-ratings'
            ],

            //customer-shippings
            [
                'name'=> 'Create Shipping',
                'slug'=> 'crm.customer-shippings.create',
                'description'=> 'Customer Shipping create permission',
                'key'=> 'crm.customer-shippings'
            ],

            [
                'name'=> 'Shipping List',
                'slug'=> 'crm.customer-shippings.index',
                'description'=> 'Customer Shipping list permission',
                'key'=> 'crm.customer-shippings'
            ],

            [
                'name'=> 'Shipping Update',
                'slug'=> 'crm.customer-shippings.update',
                'description'=> 'Customer Shipping update permission',
                'key'=> 'crm.customer-shippings'
            ],

            [
                'name'=> 'Shipping View',
                'slug'=> 'crm.customer-shippings.show',
                'description'=> 'Customer Shipping show permission',
                'key'=> 'crm.customer-shippings'
            ],

            [
                'name'=> 'Shipping Delete',
                'slug'=> 'crm.customer-shippings.destroy',
                'description'=> 'Customer Shipping delete permission',
                'key'=> 'crm.customer-shippings'
            ],

            //customer-types
            [
                'name'=> 'Create Customer Type',
                'slug'=> 'crm.customer-types.create',
                'description'=> 'Customer Type create permission',
                'key'=> 'crm.customer-types'
            ],

            [
                'name'=> 'Customer Type List',
                'slug'=> 'crm.customer-types.index',
                'description'=> 'Customer Type list permission',
                'key'=> 'crm.customer-types'
            ],

            [
                'name'=> 'Customer Type Update',
                'slug'=> 'crm.customer-types.update',
                'description'=> 'Customer Type update permission',
                'key'=> 'crm.customer-types'
            ],

            [
                'name'=> 'Customer Type View',
                'slug'=> 'crm.customer-types.show',
                'description'=> 'Customer Type show permission',
                'key'=> 'crm.customer-types'
            ],

            [
                'name'=> 'Customer Type Delete',
                'slug'=> 'crm.customer-types.destroy',
                'description'=> 'Customer Type delete permission',
                'key'=> 'crm.customer-types'
            ],

            //daily-calls
            [
                'name'=> 'Create Daily Call',
                'slug'=> 'crm.daily-calls.create',
                'description'=> 'Daily Call create permission',
                'key'=> 'crm.daily-calls'
            ],

            [
                'name'=> 'Daily Call List',
                'slug'=> 'crm.daily-calls.index',
                'description'=> 'Daily Call list permission',
                'key'=> 'crm.daily-calls'
            ],

            [
                'name'=> 'Daily Call Update',
                'slug'=> 'crm.daily-calls.update',
                'description'=> 'Daily Call update permission',
                'key'=> 'crm.daily-calls'
            ],

            [
                'name'=> 'Daily Call View',
                'slug'=> 'crm.daily-calls.show',
                'description'=> 'Daily Call show permission',
                'key'=> 'crm.daily-calls'
            ],

            [
                'name'=> 'Daily Call Delete',
                'slug'=> 'crm.daily-calls.destroy',
                'description'=> 'Daily Call delete permission',
                'key'=> 'crm.daily-calls'
            ],

            //brands
            [
                'name'=> 'Create Brand',
                'slug'=> 'inv.brands.create',
                'description'=> 'Brand create permission',
                'key'=> 'inv.brands'
            ],

            [
                'name'=> 'Brand List',
                'slug'=> 'inv.brands.index',
                'description'=> 'Brand list permission',
                'key'=> 'inv.brands'
            ],

            [
                'name'=> 'Brand Update',
                'slug'=> 'inv.brands.update',
                'description'=> 'Brand update permission',
                'key'=> 'inv.brands'
            ],

            // [
            //     'name'=> 'Brand View',
            //     'slug'=> 'inv.brands.show',
            //     'description'=> 'Brand show permission',
            //     'key'=> 'inv.brands'
            // ],

            [
                'name'=> 'Brand Delete',
                'slug'=> 'inv.brands.destroy',
                'description'=> 'Brand delete permission',
                'key'=> 'inv.brands'
            ],

            //issue-products
            // [
            //     'name'=> 'Create Issue Product',
            //     'slug'=> 'inv.issue-products.create',
            //     'description'=> 'Issue Product create permission',
            //     'key'=> 'inv.issue-products'
            // ],

            // [
            //     'name'=> 'Issue Product List',
            //     'slug'=> 'inv.issue-products.index',
            //     'description'=> 'Issue Product list permission',
            //     'key'=> 'inv.issue-products'
            // ],

            // [
            //     'name'=> 'Issue Product Update',
            //     'slug'=> 'inv.issue-products.update',
            //     'description'=> 'Issue Product update permission',
            //     'key'=> 'inv.issue-products'
            // ],

            // [
            //     'name'=> 'Issue Product View',
            //     'slug'=> 'inv.issue-products.show',
            //     'description'=> 'Issue Product show permission',
            //     'key'=> 'inv.issue-products'
            // ],

            // [
            //     'name'=> 'Issue Product Delete',
            //     'slug'=> 'inv.issue-products.destroy',
            //     'description'=> 'Issue Product delete permission',
            //     'key'=> 'inv.issue-products'
            // ],

            //product-catalogs
            [
                'name'=> 'Create Product Catalog',
                'slug'=> 'inv.product-catalogs.create',
                'description'=> 'Product Catalog create permission',
                'key'=> 'inv.product-catalogs'
            ],

            [
                'name'=> 'Product Catalog List',
                'slug'=> 'inv.product-catalogs.index',
                'description'=> 'Product Catalog list permission',
                'key'=> 'inv.product-catalogs'
            ],

            [
                'name'=> 'Product Catalog Update',
                'slug'=> 'inv.product-catalogs.update',
                'description'=> 'Product Catalog update permission',
                'key'=> 'inv.product-catalogs'
            ],

            [
                'name'=> 'Product Catalog View',
                'slug'=> 'inv.product-catalogs.show',
                'description'=> 'Product Catalog show permission',
                'key'=> 'inv.product-catalogs'
            ],

            [
                'name'=> 'Product Catalog Delete',
                'slug'=> 'inv.product-catalogs.destroy',
                'description'=> 'Product Catalog delete permission',
                'key'=> 'inv.product-catalogs'
            ],
            [
                'name'=> 'Product Catalog Settings',
                'slug'=> 'inv.product-catalogs.settings',
                'description'=> 'Product Catalog settings permission',
                'key'=> 'inv.product-catalogs'
            ],

            // product-transfers
            [
                'name'=> 'Create Product Transfer',
                'slug'=> 'inv.product-transfers.create',
                'description'=> 'Product Transfer create permission',
                'key'=> 'inv.product-transfers'
            ],

            [
                'name'=> 'Product Transfer List',
                'slug'=> 'inv.product-transfers.index',
                'description'=> 'Product Transfer list permission',
                'key'=> 'inv.product-transfers'
            ],

            [
                'name'=> 'Product Transfer Update',
                'slug'=> 'inv.product-transfers.update',
                'description'=> 'Product Transfer update permission',
                'key'=> 'inv.product-transfers'
            ],

            [
                'name'=> 'Product Transfer View',
                'slug'=> 'inv.product-transfers.show',
                'description'=> 'Product Transfer show permission',
                'key'=> 'inv.product-transfers'
            ],

            [
                'name'=> 'Product Transfer Delete',
                'slug'=> 'inv.product-transfers.destroy',
                'description'=> 'Product Transfer delete permission',
                'key'=> 'inv.product-transfers'
            ],

            // product-transfer-requests
            [
                'name'=> 'Create Product Transfer',
                'slug'=> 'inv.product-transfer-requests.create',
                'description'=> 'Product Transfer create permission',
                'key'=> 'inv.product-transfer-requests'
            ],

            [
                'name'=> 'Product Transfer List',
                'slug'=> 'inv.product-transfer-requests.index',
                'description'=> 'Product Transfer list permission',
                'key'=> 'inv.product-transfer-requests'
            ],

            [
                'name'=> 'Product Transfer Update',
                'slug'=> 'inv.product-transfer-requests.update',
                'description'=> 'Product Transfer update permission',
                'key'=> 'inv.product-transfer-requests'
            ],

            [
                'name'=> 'Product Transfer View',
                'slug'=> 'inv.product-transfer-requests.show',
                'description'=> 'Product Transfer show permission',
                'key'=> 'inv.product-transfer-requests'
            ],

            [
                'name'=> 'Product Transfer Delete',
                'slug'=> 'inv.product-transfer-requests.destroy',
                'description'=> 'Product Transfer delete permission',
                'key'=> 'inv.product-transfer-requests'
            ],

            
            [
                'name'=> 'Product Transfer Approve',
                'slug'=> 'inv.product-transfer-requests.approve',
                'description'=> 'Product Transfer approve permission',
                'key'=> 'inv.product-transfer-requests'
            ],
            
            // product-offers
            [
                'name'=> 'Create Offers',
                'slug'=> 'inv.offers.create',
                'description'=> 'Offers create permission',
                'key'=> 'inv.offers'
            ],

            [
                'name'=> 'Offers List',
                'slug'=> 'inv.offers.index',
                'description'=> 'Offers list permission',
                'key'=> 'inv.offers'
            ],

            [
                'name'=> 'Offers Update',
                'slug'=> 'inv.offers.update',
                'description'=> 'Offers update permission',
                'key'=> 'inv.offers'
            ],

            [
                'name'=> 'Offers View',
                'slug'=> 'inv.offers.show',
                'description'=> 'Offers show permission',
                'key'=> 'inv.offers'
            ],

            [
                'name'=> 'Offers Delete',
                'slug'=> 'inv.offers.destroy',
                'description'=> 'Offers delete permission',
                'key'=> 'inv.offers'
            ],

            // [
            //     'name'=> 'Create Stocks',
            //     'slug'=> 'inv.stocks.create',
            //     'description'=> 'Stocks create permission',
            //     'key'=> 'inv.stocks'
            // ],

            [
                'name'=> 'Stocks List',
                'slug'=> 'inv.stocks.index',
                'description'=> 'Stocks list permission',
                'key'=> 'inv.stocks'
            ],

            // [
            //     'name'=> 'Stocks Update',
            //     'slug'=> 'inv.stocks.update',
            //     'description'=> 'Stocks update permission',
            //     'key'=> 'inv.stocks'
            // ],

            // [
            //     'name'=> 'Stocks View',
            //     'slug'=> 'inv.stocks.show',
            //     'description'=> 'Stocks show permission',
            //     'key'=> 'inv.stocks'
            // ],

            // [
            //     'name'=> 'Stocks Delete',
            //     'slug'=> 'inv.stocks.destroy',
            //     'description'=> 'Stocks delete permission',
            //     'key'=> 'inv.stocks'
            // ],

            //branchs
            //branchs
            [
                'name'=> 'Create Branches',
                'slug'=> 'access_control.branchs.create',
                'description'=> 'Branches create permission',
                'key'=> 'access_control.branchs'
            ],

            [
                'name'=> 'Branches List',
                'slug'=> 'access_control.branchs.index',
                'description'=> 'Branches list permission',
                'key'=> 'access_control.branchs'
            ],

            [
                'name'=> 'Branches Update',
                'slug'=> 'access_control.branchs.update',
                'description'=> 'Branches update permission',
                'key'=> 'access_control.branchs'
            ],

            [
                'name'=> 'Branches View',
                'slug'=> 'access_control.branchs.show',
                'description'=> 'Branches show permission',
                'key'=> 'access_control.branchs'
            ],

            [
                'name'=> 'Branches Delete',
                'slug'=> 'access_control.branchs.destroy',
                'description'=> 'Branches delete permission',
                'key'=> 'access_control.branchs'
            ],

            
            //units
            [
                'name'=> 'Create Unit',
                'slug'=> 'inv.settings.units.create',
                'description'=> 'Unit create permission',
                'key'=> 'inv.settings.units'
            ],

            [
                'name'=> 'Units List',
                'slug'=> 'inv.settings.units.index',
                'description'=> 'Units list permission',
                'key'=> 'inv.settings.units'
            ],

            [
                'name'=> 'Units Update',
                'slug'=> 'inv.settings.units.update',
                'description'=> 'Units update permission',
                'key'=> 'inv.settings.units'
            ],

            // [
            //     'name'=> 'Units View',
            //     'slug'=> 'inv.settings.units.show',
            //     'description'=> 'Units show permission',
            //     'key'=> 'inv.settings.units'
            // ],

            [
                'name'=> 'Units Delete',
                'slug'=> 'inv.settings.units.destroy',
                'description'=> 'Units delete permission',
                'key'=> 'inv.settings.units'
            ],

            //product-types
            [
                'name'=> 'Create Product Type',
                'slug'=> 'inv.product-types.create',
                'description'=> 'Product Type create permission',
                'key'=> 'inv.product-types'
            ],

            [
                'name'=> 'Product Type List',
                'slug'=> 'inv.product-types.index',
                'description'=> 'Product Type list permission',
                'key'=> 'inv.product-types'
            ],

            [
                'name'=> 'Product Type Update',
                'slug'=> 'inv.product-types.update',
                'description'=> 'Product Type update permission',
                'key'=> 'inv.product-types'
            ],

            // [
            //     'name'=> 'Product Type View',
            //     'slug'=> 'inv.product-types.show',
            //     'description'=> 'Product Type show permission',
            //     'key'=> 'inv.product-types'
            // ],

            [
                'name'=> 'Product Type Delete',
                'slug'=> 'inv.product-types.destroy',
                'description'=> 'Product Type delete permission',
                'key'=> 'inv.product-types'
            ],

            //products
            // [
            //     'name'=> 'Create Product',
            //     'slug'=> 'inv.products.create',
            //     'description'=> 'Product create permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product List',
            //     'slug'=> 'inv.products.index',
            //     'description'=> 'Product list permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product Update',
            //     'slug'=> 'inv.products.update',
            //     'description'=> 'Product update permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product View',
            //     'slug'=> 'inv.products.show',
            //     'description'=> 'Product show permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product Delete',
            //     'slug'=> 'inv.products.destroy',
            //     'description'=> 'Product delete permission',
            //     'key'=> 'inv.products'
            // ],

            //products
            [
                'name'=> 'Product Price List',
                'slug'=> 'inv.products.price-list',
                'description'=> 'Product price list permission',
                'key'=> 'inv.products'
            ],

            // [
            //     'name'=> 'Product List',
            //     'slug'=> 'inv.products.index',
            //     'description'=> 'Product list permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product Update',
            //     'slug'=> 'inv.products.update',
            //     'description'=> 'Product update permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product View',
            //     'slug'=> 'inv.products.show',
            //     'description'=> 'Product show permission',
            //     'key'=> 'inv.products'
            // ],

            // [
            //     'name'=> 'Product Delete',
            //     'slug'=> 'inv.products.destroy',
            //     'description'=> 'Product delete permission',
            //     'key'=> 'inv.products'
            // ],

            //approvers
            // [
            //     'name'=> 'Create Approver',
            //     'slug'=> 'inv.settings.approvers.create',
            //     'description'=> 'Approver create permission',
            //     'key'=> 'inv.settings.approvers'
            // ],

            // [
            //     'name'=> 'Approver List',
            //     'slug'=> 'inv.settings.approvers.index',
            //     'description'=> 'Approver list permission',
            //     'key'=> 'inv.settings.approvers'
            // ],

            // [
            //     'name'=> 'Approver Update',
            //     'slug'=> 'inv.settings.approvers.update',
            //     'description'=> 'Approver update permission',
            //     'key'=> 'inv.settings.approvers'
            // ],

            // [
            //     'name'=> 'Approver View',
            //     'slug'=> 'inv.settings.approvers.show',
            //     'description'=> 'Approver show permission',
            //     'key'=> 'inv.settings.approvers'
            // ],

            // [
            //     'name'=> 'Approver Delete',
            //     'slug'=> 'inv.settings.approvers.destroy',
            //     'description'=> 'Approver delete permission',
            //     'key'=> 'inv.settings.approvers'
            // ],

             //tags
             [
                'name'=> 'Create Tag',
                'slug'=> 'inv.settings.tags.create',
                'description'=> 'Tag create permission',
                'key'=> 'inv.settings.tags'
             ],

             [
                 
                'name'=> 'Tag List',
                'slug'=> 'inv.settings.tags.index',
                'description'=> 'Tag list permission',
                'key'=> 'inv.settings.tags'
             ],

             [
                'name'=> 'Tag Update',
                'slug'=> 'inv.settings.tags.update',
                'description'=> 'Tag update permission',
                'key'=> 'inv.settings.tags'
             ],

            //  [
            //     'name'=> 'Tag View',
            //     'slug'=> 'inv.settings.tags.show',
            //     'description'=> 'Tag show permission',
            //     'key'=> 'inv.settings.tags'
            //  ],

             [
                'name'=> 'Tag Delete',
                'slug'=> 'inv.settings.tags.destroy',
                'description'=> 'Tag delete permission',
                'key'=> 'inv.settings.tags'
             ],

             //units
             [
                'name'=> 'Create Unit',
                'slug'=> 'inv.settings.units.create',
                'description'=> 'Unit create permission',
                'key'=> 'inv.settings.units'
             ],

             [
                'name'=> 'Unit List',
                'slug'=> 'inv.settings.units.index',
                'description'=> 'Unit list permission',
                'key'=> 'inv.settings.units'
             ],

             [
                'name'=> 'Unit Update',
                'slug'=> 'inv.settings.units.update',
                'description'=> 'Unit update permission',
                'key'=> 'inv.settings.units'
             ],

            //  [
            //     'name'=> 'Unit View',
            //     'slug'=> 'inv.settings.units.show',
            //     'description'=> 'Unit show permission',
            //     'key'=> 'inv.settings.units'
            //  ],

             [
                'name'=> 'Unit Delete',
                'slug'=> 'inv.settings.units.destroy',
                'description'=> 'Unit delete permission',
                'key'=> 'inv.settings.units'
             ],


             //branch-types
             [
                'name'=> 'Create Branch Type',
                'slug'=> 'access_control.branch-types.create',
                'description'=> 'Branch Type create permission',
                'key'=> 'access_control.branch-types'
             ],

             [
                'name'=> 'Branch Type List',
                'slug'=> 'access_control.branch-types.index',
                'description'=> 'Branch Type list permission',
                'key'=> 'access_control.branch-types'
             ],

             [
                'name'=> 'Branch Type Update',
                'slug'=> 'access_control.branch-types.update',
                'description'=> 'Branch Type update permission',
                'key'=> 'access_control.branch-types'
             ],

             [
                'name'=> 'Branch Type View',
                'slug'=> 'access_control.branch-types.show',
                'description'=> 'Branch Type show permission',
                'key'=> 'access_control.branch-types'
             ],

             [
                'name'=> 'Branch Type Delete',
                'slug'=> 'access_control.branch-types.destroy',
                'description'=> 'Branch Type delete permission',
                'key'=> 'access_control.branch-types'
             ],


          

           

            //Divisions

            [
                'name'=> 'Divisions Create',
                'slug'=> 'location_manager.divisions.create',
                'description'=> 'Divisions create permission',
                'key'=> 'location_manager.divisions'
            ],

            [
                'name'=> 'Divisions List',
                'slug'=> 'location_manager.divisions.index',
                'description'=> 'Divisions index permission',
                'key'=> 'location_manager.divisions'
            ],

            [
                'name'=> 'Divisions Update',
                'slug'=> 'location_manager.divisions.update',
                'description'=> 'Divisions update permission',
                'key'=> 'location_manager.divisions'
            ],

            [
                'name'=> 'Divisions View',
                'slug'=> 'location_manager.divisions.show',
                'description'=> 'Divisions show permission',
                'key'=> 'location_manager.divisions'
            ],

            [
                'name'=> 'Divisions Delete',
                'slug'=> 'location_manager.divisions.destroy',
                'description'=> 'Divisions delete permission',
                'key'=> 'location_manager.divisions'
            ],

            //districts

            [
                'name'=> 'Create Districts',
                'slug'=> 'location_manager.districts.create',
                'description'=> 'Districts create permission',
                'key'=> 'location_manager.districts'
             ],

             [
                'name'=> 'Districts List',
                'slug'=> 'location_manager.districts.index',
                'description'=> 'Districts index permission',
                'key'=> 'location_manager.districts'
             ],

             [
                'name'=> 'Districts Update',
                'slug'=> 'location_manager.districts.update',
                'description'=> 'Districts update permission',
                'key'=> 'location_manager.districts'
             ],

             [
                'name'=> 'Districts View',
                'slug'=> 'location_manager.districts.show',
                'description'=> 'Districts show permission',
                'key'=> 'location_manager.districts'
             ],

             [
                'name'=> 'Districts Delete',
                'slug'=> 'location_manager.districts.destroy',
                'description'=> 'Districts delete permission',
                'key'=> 'location_manager.districts'
             ],


            //thanas

            [
                'name'=> 'Create Thanas',
                'slug'=> 'location_manager.thanas.create',
                'description'=> 'Thanas create permission',
                'key'=> 'location_manager.thanas'
             ],

             [
                'name'=> 'Thanas List',
                'slug'=> 'location_manager.thanas.index',
                'description'=> 'Thanas index permission',
                'key'=> 'location_manager.thanas'
             ],

             [
                'name'=> 'Thanas Update',
                'slug'=> 'location_manager.thanas.update',
                'description'=> 'Thanas update permission',
                'key'=> 'location_manager.thanas'
             ],

             [
                'name'=> 'Thanas View',
                'slug'=> 'location_manager.thanas.show',
                'description'=> 'Thanas show permission',
                'key'=> 'location_manager.thanas'
             ],

             [
                'name'=> 'Thanas Delete',
                'slug'=> 'location_manager.thanas.destroy',
                'description'=> 'Thanas delete permission',
                'key'=> 'location_manager.thanas'
             ],

             

            //Areas
            [
                'name'=> 'Create Areas',
                'slug'=> 'location_manager.areas.create',
                'description'=> 'Areas create permission',
                'key'=> 'location_manager.areas'
             ],

             [
                'name'=> 'Areas List',
                'slug'=> 'location_manager.areas.index',
                'description'=> 'Areas list permission',
                'key'=> 'location_manager.areas'
             ],

             [
                'name'=> 'Areas Update',
                'slug'=> 'location_manager.areas.update',
                'description'=> 'Areas update permission',
                'key'=> 'location_manager.areas'
             ],

             [
                'name'=> 'Areas View',
                'slug'=> 'location_manager.areas.show',
                'description'=> 'Areas show permission',
                'key'=> 'location_manager.areas'
             ],

             [
                'name'=> 'Areas Delete',
                'slug'=> 'location_manager.areas.destroy',
                'description'=> 'Areas delete permission',
                'key'=> 'location_manager.areas'
             ],

             //location-types
             [
                'name'=> 'Create Location Type',
                'slug'=> 'location_manager.location-types.create',
                'description'=> 'Location type create permission',
                'key'=> 'location_manager.location-types'
             ],

             [
                'name'=> 'Location Type List',
                'slug'=> 'location_manager.location-types.index',
                'description'=> 'Location type list permission',
                'key'=> 'location_manager.location-types'
             ],

             [
                'name'=> 'Location Type Update',
                'slug'=> 'location_manager.location-types.update',
                'description'=> 'Location type update permission',
                'key'=> 'location_manager.location-types'
             ],

             [
                'name'=> 'Location Type View',
                'slug'=> 'location_manager.location-types.show',
                'description'=> 'Location type show permission',
                'key'=> 'location_manager.location-types'
             ],

             [
                'name'=> 'Location Type Delete',
                'slug'=> 'location_manager.location-types.destroy',
                'description'=> 'Location type delete permission',
                'key'=> 'location_manager.location-types'
             ],

             //locations
            //  [
            //     'name'=> 'Create Location',
            //     'slug'=> 'location_manager.locations.create',
            //     'description'=> 'Location create permission',
            //     'key'=> 'location_manager.locations'
            //  ],

            //  [
            //     'name'=> 'Location List',
            //     'slug'=> 'location_manager.locations.index',
            //     'description'=> 'Location list permission',
            //     'key'=> 'location_manager.locations'
            //  ],

            //  [
            //     'name'=> 'Location Update',
            //     'slug'=> 'location_manager.locations.update',
            //     'description'=> 'Location update permission',
            //     'key'=> 'location_manager.locations'
            //  ],

            //  [
            //     'name'=> 'Location View',
            //     'slug'=> 'location_manager.locations.show',
            //     'description'=> 'Location show permission',
            //     'key'=> 'location_manager.locations'
            //  ],

            //  [
            //     'name'=> 'Location Delete',
            //     'slug'=> 'location_manager.locations.destroy',
            //     'description'=> 'Location delete permission',
            //     'key'=> 'location_manager.locations'
            //  ],


            

             //Requisition
             [
                'name'=> 'Requisition Create',
                'slug'=> 'purchase.requisitions.create',
                'description'=> 'Requisition create permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition List',
                'slug'=> 'purchase.requisitions.index',
                'description'=> 'Requisition list permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition View',
                'slug'=> 'purchase.requisitions.show',
                'description'=> 'Requisition show permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition Update',
                'slug'=> 'purchase.requisitions.update',
                'description'=> 'Requisition update permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition Delete',
                'slug'=> 'purchase.requisitions.destroy',
                'description'=> 'Requisition delete permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition Approval',
                'slug'=> 'purchase.requisitions.approve',
                'description'=> 'Requisition approval permission',
                'key'=> 'purchase.requisitions'
             ],

             [
                'name'=> 'Requisition Receive',
                'slug'=> 'purchase.requisitions.receive',
                'description'=> 'Requisition Receive permission',
                'key'=> 'purchase.requisitions'
             ],

             //Orders
             [
                'name'=> 'Orders Create',
                'slug'=> 'purchase.orders.create',
                'description'=> 'Orders create permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders List',
                'slug'=> 'purchase.orders.index',
                'description'=> 'Orders list permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders View',
                'slug'=> 'purchase.orders.show',
                'description'=> 'Orders show permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders Update',
                'slug'=> 'purchase.orders.update',
                'description'=> 'Orders update permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders Delete',
                'slug'=> 'purchase.orders.destroy',
                'description'=> 'Orders delete permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders Approval',
                'slug'=> 'purchase.orders.approve',
                'description'=> 'Orders approval permission',
                'key'=> 'purchase.orders'
             ],

             [
                'name'=> 'Orders Receive',
                'slug'=> 'purchase.orders.receive',
                'description'=> 'Orders Receive permission',
                'key'=> 'purchase.orders'
             ],

             //Purchase Return
             [
                'name'=> 'Purchase Return Create',
                'slug'=> 'purchase.returns.create',
                'description'=> 'Purchase Return create permission',
                'key'=> 'purchase.returns'
             ],

             [
                'name'=> 'Purchase Return List',
                'slug'=> 'purchase.returns.index',
                'description'=> 'Purchase Return list permission',
                'key'=> 'purchase.returns'
             ],

             [
                'name'=> 'Purchase Return View',
                'slug'=> 'purchase.returns.show',
                'description'=> 'Purchase Return show permission',
                'key'=> 'purchase.returns'
             ],

             [
                'name'=> 'Purchase Return Update',
                'slug'=> 'purchase.returns.update',
                'description'=> 'Purchase Return update permission',
                'key'=> 'purchase.returns'
             ],

             [
                'name'=> 'Purchase Return Delete',
                'slug'=> 'purchase.returns.destroy',
                'description'=> 'Purchase Return delete permission',
                'key'=> 'purchase.returns'
             ],

             [
                'name'=> 'Purchase Return Approval',
                'slug'=> 'purchase.returns.approve',
                'description'=> 'Purchase Return approval permission',
                'key'=> 'purchase.returns'
             ],


             //Offices
             [
                'name'=> 'Offices Create',
                'slug'=> 'purchase.offices.create',
                'description'=> 'Offices create permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices List',
                'slug'=> 'purchase.offices.index',
                'description'=> 'Offices list permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices View',
                'slug'=> 'purchase.offices.show',
                'description'=> 'Offices show permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices Update',
                'slug'=> 'purchase.offices.update',
                'description'=> 'Offices update permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices Delete',
                'slug'=> 'purchase.offices.destroy',
                'description'=> 'Offices delete permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices Approval',
                'slug'=> 'purchase.offices.approve',
                'description'=> 'Offices approval permission',
                'key'=> 'purchase.offices'
             ],

             [
                'name'=> 'Offices Receive',
                'slug'=> 'purchase.offices.receive',
                'description'=> 'Offices Receive permission',
                'key'=> 'purchase.offices'
             ],
             //suppliers
             [
                'name'=> 'Create Supplier',
                'slug'=> 'purchase.suppliers.create',
                'description'=> 'Supplier create permission',
                'key'=> 'purchase.suppliers'
             ],

             [
                'name'=> 'Supplier List',
                'slug'=> 'purchase.suppliers.index',
                'description'=> 'Supplier list permission',
                'key'=> 'purchase.suppliers'
             ],

             [
                'name'=> 'Supplier Update',
                'slug'=> 'purchase.suppliers.update',
                'description'=> 'Supplier update permission',
                'key'=> 'purchase.suppliers'
             ],

             [
                'name'=> 'Supplier View',
                'slug'=> 'purchase.suppliers.show',
                'description'=> 'Supplier show permission',
                'key'=> 'purchase.suppliers'
             ],

             [
                'name'=> 'Supplier Delete',
                'slug'=> 'purchase.suppliers.destroy',
                'description'=> 'Supplier delete permission',
                'key'=> 'purchase.suppliers'
             ],

             //vendors
             [
                'name'=> 'Create Vendor',
                'slug'=> 'purchase.vendors.create',
                'description'=> 'Vendor create permission',
                'key'=> 'purchase.vendors'
             ],

             [
                'name'=> 'Vendor List',
                'slug'=> 'purchase.vendors.index',
                'description'=> 'Vendor list permission',
                'key'=> 'purchase.vendors'
             ],

             [
                'name'=> 'Vendor Update',
                'slug'=> 'purchase.vendors.update',
                'description'=> 'Vendor update permission',
                'key'=> 'purchase.vendors'
             ],

             [
                'name'=> 'Vendor View',
                'slug'=> 'purchase.vendors.show',
                'description'=> 'Vendor show permission',
                'key'=> 'purchase.vendors'
             ],

             [
                'name'=> 'Vendor Delete',
                'slug'=> 'purchase.vendors.destroy',
                'description'=> 'Vendor delete permission',
                'key'=> 'purchase.vendors'
             ],
             [
                'name'=> 'Purchase Reports',
                'slug'=> 'purchase.reports.index',
                'description'=> 'Purchase reports permission',
                'key'=> 'purchase.reports'
             ],

             

             //sales orders
             [
                'name'=> 'Create Sales Orders',
                'slug'=> 'sales.sales-orders.create',
                'description'=> 'Sales Orders create permission',
                'key'=> 'sales.sales-orders'
             ],

             [
                'name'=> 'Sales Orders List',
                'slug'=> 'sales.sales-orders.index',
                'description'=> 'Sales Orders list permission',
                'key'=> 'sales.sales-orders'
             ],

             [
                'name'=> 'Sales Orders Update',
                'slug'=> 'sales.sales-orders.update',
                'description'=> 'Sales Orders update permission',
                'key'=> 'sales.sales-orders'
             ],

             [
                'name'=> 'Sales Orders View',
                'slug'=> 'sales.sales-orders.show',
                'description'=> 'Sales Orders show permission',
                'key'=> 'sales.sales-orders'
             ],

             [
                'name'=> 'Sales Orders Delete',
                'slug'=> 'sales.sales-orders.destroy',
                'description'=> 'Sales Orders delete permission',
                'key'=> 'sales.sales-orders'
             ],

             [
                'name'=> 'Sales Orders Approve',
                'slug'=> 'sales.sales-orders.approve',
                'description'=> 'Sales Orders approve permission',
                'key'=> 'sales.sales-orders'
             ],

            //  [
            //     'name'=> 'Sales Orders Receive',
            //     'slug'=> 'sales.sales-orders.receive',
            //     'description'=> 'Sales Orders receive permission',
            //     'key'=> 'sales.sales-orders'
            //  ],

             // Deliveries
             [
                'name'=> 'Sales Order Deliveries Create',
                'slug'=> 'sales.deliveries.create',
                'description'=> 'Sales Order Deliveries create permission',
                'key'=> 'sales.deliveries'
             ],

             [
                'name'=> 'Sales Order Deliveries List',
                'slug'=> 'sales.deliveries.index',
                'description'=> 'Sales Order Deliveries list permission',
                'key'=> 'sales.deliveries'
             ],

            //  [
            //     'name'=> 'Sales Order Deliveries Update',
            //     'slug'=> 'sales.deliveries.update',
            //     'description'=> 'Sales Order Deliveries update permission',
            //     'key'=> 'sales.deliveries'
            //  ],

             [
                'name'=> 'Sales Order Deliveries View',
                'slug'=> 'sales.deliveries.show',
                'description'=> 'Sales Order Deliveries show permission',
                'key'=> 'sales.deliveries'
             ],

            //  [
            //     'name'=> 'Sales Order Deliveries Delete',
            //     'slug'=> 'sales.deliveries.destroy',
            //     'description'=> 'Sales Order Deliveries delete permission',
            //     'key'=> 'sales.deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries Approve',
            //     'slug'=> 'sales.deliveries.approve',
            //     'description'=> 'Sales Order Deliveries approve permission',
            //     'key'=> 'sales.deliveries'
            //  ],

            //sales order deliveries

            //  [
            //     'name'=> 'Sales Order Deliveries Create',
            //     'slug'=> 'sales.sales-order-deliveries.create',
            //     'description'=> 'Sales Order Deliveries create permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries List',
            //     'slug'=> 'sales.sales-order-deliveries.index',
            //     'description'=> 'Sales Order Deliveries list permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries Update',
            //     'slug'=> 'sales.sales-order-deliveries.update',
            //     'description'=> 'Sales Order Deliveries update permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries View',
            //     'slug'=> 'sales.sales-order-deliveries.show',
            //     'description'=> 'Sales Order Deliveries show permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries Delete',
            //     'slug'=> 'sales.sales-order-deliveries.destroy',
            //     'description'=> 'Sales Order Deliveries delete permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries Approve',
            //     'slug'=> 'sales.sales-order-deliveries.approve',
            //     'description'=> 'Sales Order Deliveries approve permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            //  [
            //     'name'=> 'Sales Order Deliveries Receive',
            //     'slug'=> 'sales.sales-order-deliveries.receive',
            //     'description'=> 'Sales Order Deliveries receive permission',
            //     'key'=> 'sales.sales-order-deliveries'
            //  ],

            [
                'name'=> 'Shipments verifes access',
                'slug'=> 'sales.shipment-verifies.index',
                'description'=> 'Shipments verifes access permission',
                'key'=> 'sales.shipment-verifies'
            ],
            //sales.condition-amount-collects

            [
                'name'=> 'Sales Condition Collects List',
                'slug'=> 'sales.condition-amount-collects.index',
                'description'=> 'Sales Condition Amount Collects list permission',
                'key'=> 'sales.condition-amount-collects'
             ],

             [
                'name'=> 'Sales Condition Collects Approval',
                'slug'=> 'sales.condition-amount-collects.approved-list',
                'description'=> 'Sales Condition Amount Collects approval permission',
                'key'=> 'sales.condition-amount-collects'
             ],

            //sales requisitions

            [
                'name'=> 'Sales Requisitions Create',
                'slug'=> 'sales.sales-requisitions.create',
                'description'=> 'Sales Requisitions create permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions List',
                'slug'=> 'sales.sales-requisitions.index',
                'description'=> 'Sales Requisitions list permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions Update',
                'slug'=> 'sales.sales-requisitions.update',
                'description'=> 'Sales Requisitions update permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions View',
                'slug'=> 'sales.sales-requisitions.show',
                'description'=> 'Sales Requisitions show permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions Delete',
                'slug'=> 'sales.sales-requisitions.destroy',
                'description'=> 'Sales Requisitions delete permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions Approve',
                'slug'=> 'sales.sales-requisitions.approve',
                'description'=> 'Sales Requisitions approve permission',
                'key'=> 'sales.sales-requisitions'
             ],

             [
                'name'=> 'Sales Requisitions Verify',
                'slug'=> 'sales.sales-requisitions.verify',
                'description'=> 'Sales Requisitions verify permission',
                'key'=> 'sales.sales-requisitions'
             ],

            //  [
            //     'name'=> 'Sales Requisitions Receive',
            //     'slug'=> 'sales.sales-requisitions.receive',
            //     'description'=> 'Sales Requisitions receive permission',
            //     'key'=> 'sales.sales-requisitions'
            //  ],

            //sales returns

            [
                'name'=> 'Sales Returns Create',
                'slug'=> 'sales.sales-returns.create',
                'description'=> 'Sales Returns create permission',
                'key'=> 'sales.sales-returns'
             ],

             [
                'name'=> 'Sales Returns List',
                'slug'=> 'sales.sales-returns.index',
                'description'=> 'Sales Returns list permission',
                'key'=> 'sales.sales-returns'
             ],

             [
                'name'=> 'Sales Returns Update',
                'slug'=> 'sales.sales-returns.update',
                'description'=> 'Sales Returns update permission',
                'key'=> 'sales.sales-returns'
             ],

             [
                'name'=> 'Sales Returns View',
                'slug'=> 'sales.sales-returns.show',
                'description'=> 'Sales Returns show permission',
                'key'=> 'sales.sales-returns'
             ],

             [
                'name'=> 'Sales Returns Delete',
                'slug'=> 'sales.sales-returns.destroy',
                'description'=> 'Sales Returns delete permission',
                'key'=> 'sales.sales-returns'
             ],

             [
                'name'=> 'Sales Returns Approve',
                'slug'=> 'sales.sales-returns.approve',
                'description'=> 'Sales Returns approve permission',
                'key'=> 'sales.sales-returns'
             ],

            //Sales Commissions

            [
                'name'=> 'Sales Commissions Create',
                'slug'=> 'sales.sales-commissions.create',
                'description'=> 'Sales Commissions create permission',
                'key'=> 'sales.sales-commissions'
             ],

             [
                'name'=> 'Sales Commissions List',
                'slug'=> 'sales.sales-commissions.index',
                'description'=> 'Sales Commissions list permission',
                'key'=> 'sales.sales-commissions'
             ],

             [
                'name'=> 'Sales Commissions Verify',
                'slug'=> 'sales.sales-commissions.verify',
                'description'=> 'Sales Commissions verify permission',
                'key'=> 'sales.sales-commissions'
             ],

             //Fake Invoices

             [
                'name'=> 'Fake Invoices Create',
                'slug'=> 'sales.fake-invoices.create',
                'description'=> 'Fake Invoices create permission',
                'key'=> 'sales.fake-invoices'
             ],

             [
                'name'=> 'Fake Invoices List',
                'slug'=> 'sales.fake-invoices.index',
                'description'=> 'Fake Invoices list permission',
                'key'=> 'sales.fake-invoices'
             ],

             [
                'name'=> 'Fake Invoices Update',
                'slug'=> 'sales.fake-invoices.update',
                'description'=> 'Fake Invoices update permission',
                'key'=> 'sales.fake-invoices'
             ],

             [
                'name'=> 'Fake Invoices View',
                'slug'=> 'sales.fake-invoices.show',
                'description'=> 'Fake Invoices show permission',
                'key'=> 'sales.fake-invoices'
             ],

             [
                'name'=> 'Fake Invoices Delete',
                'slug'=> 'sales.fake-invoices.destroy',
                'description'=> 'Fake Invoices delete permission',
                'key'=> 'sales.fake-invoices'
             ],

            //backup challans

             [
                'name'=> 'Backup Challans Create',
                'slug'=> 'sales.backup-challans.create',
                'description'=> 'Backup Challans create permission',
                'key'=> 'sales.backup-challans'
             ],

             [
                'name'=> 'Backup Challans List',
                'slug'=> 'sales.backup-challans.index',
                'description'=> 'Backup Challans list permission',
                'key'=> 'sales.backup-challans'
             ],

             [
                'name'=> 'Backup Challans Update',
                'slug'=> 'sales.backup-challans.update',
                'description'=> 'Backup Challans update permission',
                'key'=> 'sales.backup-challans'
             ],

             [
                'name'=> 'Backup Challans View',
                'slug'=> 'sales.backup-challans.show',
                'description'=> 'Backup Challans show permission',
                'key'=> 'sales.backup-challans'
             ],

             [
                'name'=> 'Backup Challans Delete',
                'slug'=> 'sales.backup-challans.destroy',
                'description'=> 'Backup Challans delete permission',
                'key'=> 'sales.backup-challans'
             ],

             [
                'name'=> 'Backup Challans Approve',
                'slug'=> 'sales.backup-challans.approve',
                'description'=> 'Backup Challans approve permission',
                'key'=> 'sales.backup-challans'
             ],


            //quotations
            [
                'name'=> 'Quotations Create',
                'slug'=> 'sales.quotations.create',
                'description'=> 'Quotations create permission',
                'key'=> 'sales.quotations'
            ],

            [
                'name'=> 'Quotations List',
                'slug'=> 'sales.quotations.index',
                'description'=> 'Quotations list permission',
                'key'=> 'sales.quotations'
            ],

            [
                'name'=> 'Quotations Update',
                'slug'=> 'sales.quotations.update',
                'description'=> 'Quotations update permission',
                'key'=> 'sales.quotations'
            ],

            // [
            //     'name'=> 'Quotations View',
            //     'slug'=> 'sales.quotations.show',
            //     'description'=> 'Quotations show permission',
            //     'key'=> 'sales.quotations'
            // ],

            [
                'name'=> 'Quotations Delete',
                'slug'=> 'sales.quotations.destroy',
                'description'=> 'Quotations delete permission',
                'key'=> 'sales.quotations'
            ],

            [
                'name'=> 'Quotations Approve',
                'slug'=> 'sales.quotations.approve',
                'description'=> 'Quotations approve permission',
                'key'=> 'sales.quotations'
            ],
            [
                'name'=> 'Quotations Print',
                'slug'=> 'sales.quotations.print',
                'description'=> 'Quotations print permission',
                'key'=> 'sales.quotations'
            ],
            [
                'name'=> 'Quotations Convert to Sales Order',
                'slug'=> 'sales.quotations.sales.order',
                'description'=> 'Quotations convert to sales order permission',
                'key'=> 'sales.quotations'
            ],
            //Qurier information
            
            [
                'name'=> 'Sales Couriers List',
                'slug'=> 'sales.couriers.index',
                'description'=> 'Sales couriers list permission',
                'key'=> 'sales.couriers'
            ],
            [
                'name'=> 'Sales Couriers Create',
                'slug'=> 'sales.couriers.create',
                'description'=> 'Sales couriers create permission',
                'key'=> 'sales.couriers'
            ],
            [
                'name'=> 'Sales Couriers Update',
                'slug'=> 'sales.couriers.update',
                'description'=> 'Sales couriers update permission',
                'key'=> 'sales.couriers'
            ],
            [
                'name'=> 'Sales Couriers View',
                'slug'=> 'sales.couriers.show',
                'description'=> 'Sales couriers show permission',
                'key'=> 'sales.couriers'
            ],
            [
                'name'=> 'Sales Couriers Delete',
                'slug'=> 'sales.couriers.destroy',
                'description'=> 'Sales couriers delete permission',
                'key'=> 'sales.couriers'
            ],



            [
                'name'=> 'Account Group List',
                'slug'=> 'account.account-setup.account-groups.index',
                'description'=> 'Account group list permission',
                'key'=> 'account.account-setup.account-groups'
            ],

            //  [
            //     'name'=> 'Account Group Create',
            //     'slug'=> 'account.account-setup.account-groups.create',
            //     'description'=> 'Account group create permission',
            //     'key'=> 'account.account-setup.account-groups'
            //  ],

            //  [
            //     'name'=> 'Account Group View',
            //     'slug'=> 'account.account-setup.account-groups.show',
            //     'description'=> 'Account group show permission',
            //     'key'=> 'account.account-setup.account-groups'
            //  ],

            //  [
            //     'name'=> 'Account Group Edit',
            //     'slug'=> 'account.account-setup.account-groups.update',
            //     'description'=> 'Account group edit permission',
            //     'key'=> 'account.account-setup.account-groups'
            //  ],

            //  [
            //     'name'=> 'Account Group Delete',
            //     'slug'=> 'account.account-setup.account-groups.destroy',
            //     'description'=> 'Account group delete permission',
            //     'key'=> 'account.account-setup.account-groups'
            //  ],

                //  account controls
             [
                'name'=> 'Account Control List',
                'slug'=> 'account.account-setup.account-controls.index',
                'description'=> 'Account control list permission',
                'key'=> 'account.account-setup.account-controls'
             ],

             [
                'name'=> 'Account Control Create',
                'slug'=> 'account.account-setup.account-controls.create',
                'description'=> 'Account control create permission',
                'key'=> 'account.account-setup.account-controls'
             ],

            //  [
            //     'name'=> 'Account Control View',
            //     'slug'=> 'account.account-setup.account-controls.show',
            //     'description'=> 'Account control show permission',
            //     'key'=> 'account.account-setup.account-controls'
            //  ],

             [
                'name'=> 'Account Control Edit',
                'slug'=> 'account.account-setup.account-controls.update',
                'description'=> 'Account control edit permission',
                'key'=> 'account.account-setup.account-controls'
             ],

             [
                'name'=> 'Account Control Delete',
                'slug'=> 'account.account-setup.account-controls.destroy',
                'description'=> 'Account control delete permission',
                'key'=> 'account.account-setup.account-controls'
             ],

             //  account subsidiaries
             [
                'name'=> 'Subsidiary List',
                'slug'=> 'account.account-setup.account-subsidiaries.index',
                'description'=> 'Subsidiary list permission',
                'key'=> 'account.account-setup.account-subsidiaries'
             ],

             [
                'name'=> 'Subsidiary Create',
                'slug'=> 'account.account-setup.account-subsidiaries.create',
                'description'=> 'Subsidiary create permission',
                'key'=> 'account.account-setup.account-subsidiaries'
             ],

            //  [
            //     'name'=> 'Subsidiary View',
            //     'slug'=> 'account.account-setup.account-subsidiaries.show',
            //     'description'=> 'Subsidiary show permission',
            //     'key'=> 'account.account-setup.account-subsidiaries'
            //  ],

             [
                'name'=> 'Subsidiary Edit',
                'slug'=> 'account.account-setup.account-subsidiaries.update',
                'description'=> 'Subsidiary edit permission',
                'key'=> 'account.account-setup.account-subsidiaries'
             ],

             [
                'name'=> 'Subsidiary Delete',
                'slug'=> 'account.account-setup.account-subsidiaries.destroy',
                'description'=> 'Subsidiary delete permission',
                'key'=> 'account.account-setup.account-subsidiaries'
             ],

                //  chart of accounts
             [
                'name'=> 'Chart of Account List',
                'slug'=> 'account.account-setup.accounts.index',
                'description'=> 'Chart of Account list permission',
                'key'=> 'account.account-setup.accounts'
             ],

             [
                'name'=> 'Chart of Account Create',
                'slug'=> 'account.account-setup.accounts.create',
                'description'=> 'Chart of Account create permission',
                'key'=> 'account.account-setup.accounts'
             ],

            //  [
            //     'name'=> 'Chart of Account View',
            //     'slug'=> 'account.account-setup.accounts.show',
            //     'description'=> 'Chart of Account show permission',
            //     'key'=> 'account.account-setup.accounts'
            //  ],

             [
                'name'=> 'Chart of Account Edit',
                'slug'=> 'account.account-setup.accounts.update',
                'description'=> 'Chart of Account edit permission',
                'key'=> 'account.account-setup.accounts'
             ],

             [
                'name'=> 'Chart of Account Delete',
                'slug'=> 'account.account-setup.accounts.destroy',
                'description'=> 'Chart of Account delete permission',
                'key'=> 'account.account-setup.accounts'
             ],

            //  [
            //     'name'=> 'Account Opening Balance List',
            //     'slug'=> 'account.account-setup.account-opening-balances.index',
            //     'description'=> 'Account opening balance list permission',
            //     'key'=> 'account.account-setup.account-opening-balances'
            //  ],

            //  [
            //     'name'=> 'Account Opening Balance Create',
            //     'slug'=> 'account.account-setup.account-opening-balances.create',
            //     'description'=> 'Account opening balance create permission',
            //     'key'=> 'account.account-setup.account-opening-balances'
            //  ],

            //  [
            //     'name'=> 'Account Opening Balance View',
            //     'slug'=> 'account.account-setup.account-opening-balances.show',
            //     'description'=> 'Account opening balance show permission',
            //     'key'=> 'account.account-setup.account-opening-balances'
            //  ],

            //  [
            //     'name'=> 'Account Opening Balance Edit',
            //     'slug'=> 'account.account-setup.account-opening-balances.update',
            //     'description'=> 'Account opening balance edit permission',
            //     'key'=> 'account.account-setup.account-opening-balances'
            //  ],

            //  [
            //     'name'=> 'Account Opening Balance Delete',
            //     'slug'=> 'account.account-setup.account-opening-balances.destroy',
            //     'description'=> 'Account opening balance delete permission',
            //     'key'=> 'account.account-setup.account-opening-balances'
            //  ],

             // Bank Accounts
             [
                'name'=> 'Bank Accounts List',
                'slug'=> 'account.account-setup.bank-accounts.index',
                'description'=> 'Bank accounts list permission',
                'key'=> 'account.account-setup.bank-accounts'
             ],

             [
                'name'=> 'Bank Accounts Delete',
                'slug'=> 'account.account-setup.bank-accounts.destroy',
                'description'=> 'Bank accounts delete permission',
                'key'=> 'account.account-setup.bank-accounts'
             ],
             [
                'name'=> 'Bank Accounts Create',
                'slug'=> 'account.account-setup.bank-accounts.create',
                'description'=> 'Bank accounts create permission',
                'key'=> 'account.account-setup.bank-accounts'
             ],
             [
                'name'=> 'Bank Accounts Edit',
                'slug'=> 'account.account-setup.bank-accounts.update',
                'description'=> 'Bank accounts edit permission',
                'key'=> 'account.account-setup.bank-accounts'
             ],

             //Bank Branches
             [
                'name'=> 'Bank Branches List',
                'slug'=> 'account.account-setup.bank-branches.index',
                'description'=> 'Bank branches list permission',
                'key'=> 'account.account-setup.bank-branches'
             ],

             [
                'name'=> 'Bank Branches Delete',
                'slug'=> 'account.account-setup.bank-branches.destroy',
                'description'=> 'Bank branches delete permission',
                'key'=> 'account.account-setup.bank-branches'
             ],
             [
                'name'=> 'Bank Branches Create',
                'slug'=> 'account.account-setup.bank-branches.create',
                'description'=> 'Bank branches create permission',
                'key'=> 'account.account-setup.bank-branches'
             ],
             [
                'name'=> 'Bank Branches Edit',
                'slug'=> 'account.account-setup.bank-branches.update',
                'description'=> 'Bank branches edit permission',
                'key'=> 'account.account-setup.bank-branches'
             ],
             

             //Banks
             [
                'name'=> 'Banks List',
                'slug'=> 'account.account-setup.banks.index',
                'description'=> 'Banks list permission',
                'key'=> 'account.account-setup.banks'
             ],

             [
                'name'=> 'Banks Delete',
                'slug'=> 'account.account-setup.banks.destroy',
                'description'=> 'Banks delete permission',
                'key'=> 'account.account-setup.banks'
             ],
             [
                'name'=> 'Banks Create',
                'slug'=> 'account.account-setup.banks.create',
                'description'=> 'Banks create permission',
                'key'=> 'account.account-setup.banks'
             ],
             [
                'name'=> 'Banks Edit',
                'slug'=> 'account.account-setup.banks.update',
                'description'=> 'Banks edit permission',
                'key'=> 'account.account-setup.banks'
             ],


                //account.cheque-verifications.check
                [
                    'name'=> 'Cheque Verifications List',
                    'slug'=> 'account.cheque-verifications.index',
                    'description'=> 'Cheque verifications list permission',
                    'key'=> 'account.cheque-verifications'
                ],
                [
                    'name'=> 'Cheque Deposit Verification',
                    'slug'=> 'account.cheque-verifications.deposit',
                    'description'=> 'Cheque verifications list permission',
                    'key'=> 'account.cheque-verifications'
                ],
    
                [
                    'name'=> 'Cheque Checking Approve',
                    'slug'=> 'account.cheque-verifications.check',
                    'description'=> 'Cheque verifications check permission',
                    'key'=> 'account.cheque-verifications'
                ],
                [
                    'name'=> 'Cheque Check Verifications Approve',
                    'slug'=> 'account.cheque-verifications.check-verification',
                    'description'=> 'Cheque check Verifications  permission',
                    'key'=> 'account.cheque-verifications'
                ],

                //account.fund transfer
                [
                    'name'=> 'Fund Transfer List',
                    'slug'=> 'account.fund-tranfers.index',
                    'description'=> 'Fund Transfer list permission',
                    'key'=> 'account.fund-tranfers'
                ],
                [
                    'name'=> 'Fund Transfer Create',
                    'slug'=> 'account.fund-tranfers.create',
                    'description'=> 'Fund Transfer Create permission',
                    'key'=> 'account.fund-tranfers'
                ],
                [
                    'name'=> 'Fund Transfer Edit',
                    'slug'=> 'account.fund-tranfers.edit',
                    'description'=> 'Fund Transfer Edit permission',
                    'key'=> 'account.fund-tranfers'
                ],
                [
                    'name'=> 'Fund Transfer Delete',
                    'slug'=> 'account.fund-tranfers.delete',
                    'description'=> 'Fund Transfer delete permission',
                    'key'=> 'account.fund-tranfers'
                ],
    
                [
                    'name'=> 'Fund Transfer Verify',
                    'slug'=> 'account.fund-tranfers.verify',
                    'description'=> 'Fund Transfer verify permission',
                    'key'=> 'account.fund-tranfers'
                ],
                [
                    'name'=> 'Fund Transfer Approve',
                    'slug'=> 'account.fund-tranfers.approve',
                    'description'=> 'Fund Transfer approve permission',
                    'key'=> 'account.fund-tranfers'
                ],
                [
                    'name'=> 'Fund Transfer View',
                    'slug'=> 'account.fund-tranfers.view',
                    'description'=> 'Fund Transfer View permission',
                    'key'=> 'account.fund-tranfers'
                ],



             //default-payable-receivables
            //  [
            //     'name'=> 'Default Payable Receivables List',
            //     'slug'=> 'account.account-settings.default-payable-receivables.index',
            //     'description'=> 'Default payable receivables list permission',
            //     'key'=> 'account.account-settings.default-payable-receivables'
            //  ],

            //  [
            //     'name'=> 'Create Default Payable Receivable',
            //     'slug'=> 'account.account-settings.default-payable-receivables.create',
            //     'description'=> 'Default payable receivable create permission',
            //     'key'=> 'account.account-settings.default-payable-receivables'
            //  ],

            //  [
            //     'name'=> 'Default Payable Receivable View',
            //     'slug'=> 'account.account-settings.default-payable-receivables.show',
            //     'description'=> 'Default payable receivable show permission',
            //     'key'=> 'account.account-settings.default-payable-receivables'
            //  ],

            //  [
            //     'name'=> 'Default Payable Receivable Delete',
            //     'slug'=> 'account.account-settings.default-payable-receivables.destroy',
            //     'description'=> 'Default payable receivable delete permission',
            //     'key'=> 'account.account-settings.default-payable-receivables'
            //  ],

            //  [
            //     'name'=> 'Default Payable Receivable Edit',
            //     'slug'=> 'account.account-settings.default-payable-receivables.update',
            //     'description'=> 'Default payable receivable edit permission',
            //     'key'=> 'account.account-settings.default-payable-receivables'
            //  ],
            

            //  account.collections.collections.create
            // account.collections.collections.verify
            // account.collections.collections.approve
            // account.collections.collections.show
            // account.collections.collections.destroy

            //emi entries 
            //account.cheque-verifications.emi-entries
            [
                'name'=> 'EMI Entries List',
                'slug'=> 'account.emi-entries.index',
                'description'=> 'EMI entries list permission',
                'key'=> 'account.emi-entries'
            ],
            [
                'name'=> 'Create EMI Entries',
                'slug'=> 'account.emi-entries.create',
                'description'=> 'EMI entries create permission',
                'key'=> 'account.emi-entries'
            ],
            // [
            //     'name'=> 'EMI Entries View',
            //     'slug'=> 'account.emi-entries.show',
            //     'description'=> 'EMI entries show permission',
            //     'key'=> 'account.emi-entries'
            // ],
            [
                'name'=> 'EMI Entries Edit',
                'slug'=> 'account.emi-entries.update',
                'description'=> 'EMI entries edit permission',
                'key'=> 'account.emi-entries'
            ],
            [
                'name'=> 'EMI Entries Delete',
                'slug'=> 'account.emi-entries.destroy',
                'description'=> 'EMI entries delete permission',
                'key'=> 'account.emi-entries'
            ],
            [
                'name'=> 'EMI Collections',
                'slug'=> 'account.emi-entries.emi-collections',
                'description'=> 'EMI collections list permission',
                'key'=> 'account.emi-entries'
            ],

            [
                'name'=> 'EMI Installment Report',
                'slug'=> 'account.emi-reports.emi-installment-report',
                'description'=> 'EMI installment report permission',
                'key'=> 'account.emi-reports'
            ],
             [
                'name'=> 'EMI Customer Report',
                'slug'=> 'account.emi-reports.emi-customer-report',
                'description'=> 'EMI Customer report permission',
                'key'=> 'account.emi-reports'
            ],

            // account.advance-cheque-entries
            [
                'name'=> 'Advance Cheque Entries List',
                'slug'=> 'account.advance-cheque-entries.index',
                'description'=> 'Advance cheque entries list permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            [
                'name'=> 'Create Advance Cheque Entries',
                'slug'=> 'account.advance-cheque-entries.create',
                'description'=> 'Advance cheque entries create permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            [
                'name'=> 'Advance Cheque Entries View',
                'slug'=> 'account.advance-cheque-entries.show',
                'description'=> 'Advance cheque entries show permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            [
                'name'=> 'Advance Cheque Entries Edit',
                'slug'=> 'account.advance-cheque-entries.update',
                'description'=> 'Advance cheque entries edit permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            [
                'name'=> 'Advance Cheque Entries Delete',
                'slug'=> 'account.advance-cheque-entries.destroy',
                'description'=> 'Advance cheque entries delete permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            // account.advance-cheque-entries.approve
            [
                'name'=> 'Approve Advance Cheque Entries',
                'slug'=> 'account.advance-cheque-entries.approve',
                'description'=> 'Approve advance cheque entries permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            // account.advance-cheque-entries.check
            [
                'name'=> 'Check Advance Cheque Entries',
                'slug'=> 'account.advance-cheque-entries.check',
                'description'=> 'Check advance cheque entries permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            //advance cheque collection
            [
                'name'=> 'Advance Cheque Collection',
                'slug'=> 'account.advance-cheque-entries.advance-cheque-collections',
                'description'=> 'Advance cheque collection permission',
                'key'=> 'account.advance-cheque-entries'
            ],
            //collections
             [
                'name'=> 'Collections List',
                'slug'=> 'account.collections.collections.index',
                'description'=> 'Collections list permission',
                'key'=> 'account.collections.collections'
             ],

             [
                'name'=> 'Create Collections',
                'slug'=> 'account.collections.collections.create',
                'description'=> 'Collections create permission',
                'key'=> 'account.collections.collections'
             ],

             [
                'name'=> 'Collections View',
                'slug'=> 'account.collections.collections.show',
                'description'=> 'Collections show permission',
                'key'=> 'account.collections.collections'
             ],

             [
                'name'=> 'Collections Delete',
                'slug'=> 'account.collections.collections.destroy',
                'description'=> 'Collections delete permission',
                'key'=> 'account.collections.collections'
             ],

             [
                'name'=> 'Collections Update',
                'slug'=> 'account.collections.collections.update',
                'description'=> 'Collections update permission',
                'key'=> 'account.collections.collections'
             ],
             [
                'name'=> 'Collections Approve',
                'slug'=> 'account.collections.collections.approve',
                'description'=> 'Collections approve permission',
                'key'=> 'account.collections.collections'
             ],
             [             
                'name'=> 'Collections Verify',
                'slug'=> 'account.collections.collections.verify',
                'description'=> 'Collections verify permission',
                'key'=> 'account.collections.collections'
             ],

             //Invoice-wise Collections
             [
                'name'=> 'Invoice-wise Collections List',
                'slug'=> 'account.collections.invoice-wise-collections.index',
                'description'=> 'Invoice-wise Collections list permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Create Invoice-wise Collections',
                'slug'=> 'account.collections.invoice-wise-collections.create',
                'description'=> 'Invoice-wise Collections create permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Invoice-wise Collections View',
                'slug'=> 'account.collections.invoice-wise-collections.show',
                'description'=> 'Invoice-wise Collections show permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Invoice-wise Collections Delete',
                'slug'=> 'account.collections.invoice-wise-collections.destroy',
                'description'=> 'Invoice-wise Collections delete permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Invoice-wise Collections Update',
                'slug'=> 'account.collections.invoice-wise-collections.update',
                'description'=> 'Invoice-wise Collections update permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Invoice-wise Collections Approve',
                'slug'=> 'account.collections.invoice-wise-collections.approve',
                'description'=> 'Invoice-wise Collections approve permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             [
                'name'=> 'Invoice-wise Collections Verify',
                'slug'=> 'account.collections.invoice-wise-collections.verify',
                'description'=> 'Invoice-wise Collections verify permission',
                'key'=> 'account.collections.invoice-wise-collections'
             ],

             //supplier payments
             [
                'name'=> 'Payments List',
                'slug'=> 'account.payments.make-payments.index',
                'description'=> 'Payments list permission',
                'key'=> 'account.payments.make-payments'
             ],

             [
                'name'=> 'Create Payments',
                'slug'=> 'account.payments.make-payments.create',
                'description'=> 'Payments create permission',
                'key'=> 'account.payments.make-payments'
             ],

             [
                'name'=> 'Payments View',
                'slug'=> 'account.payments.make-payments.show',
                'description'=> 'Payments show permission',
                'key'=> 'account.payments.make-payments'
             ],

             [
                'name'=> 'Payments Delete',
                'slug'=> 'account.payments.make-payments.destroy',
                'description'=> 'Payments delete permission',
                'key'=> 'account.payments.make-payments'
             ],

             [
                'name'=> 'Payments Update',
                'slug'=> 'account.payments.make-payments.update',
                'description'=> 'Payments update permission',
                'key'=> 'account.payments.make-payments'
             ],
             [
                'name'=> 'Verify Payments',
                'slug'=> 'account.payments.make-payments.verify',
                'description'=> 'Payments verify permission',
                'key'=> 'account.payments.make-payments'
             ],
             [
                'name'=> 'Payments Approve',
                'slug'=> 'account.payments.make-payments.approve',
                'description'=> 'Payments approve permission',
                'key'=> 'account.payments.make-payments'
             ],
             //invoice-wise-payments
             [
                'name'=> 'Invoice-wise Payments List',
                'slug'=> 'account.payments.invoice-wise-payments.index',
                'description'=> 'Invoice-wise payments list permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],

             [
                'name'=> 'Create Invoice-wise Payments',
                'slug'=> 'account.payments.invoice-wise-payments.create',
                'description'=> 'Invoice-wise payments create permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],

             [
                'name'=> 'Invoice-wise Payments View',
                'slug'=> 'account.payments.invoice-wise-payments.show',
                'description'=> 'Invoice-wise payments show permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],

             [
                'name'=> 'Invoice-wise Payments Delete',
                'slug'=> 'account.payments.invoice-wise-payments.destroy',
                'description'=> 'Invoice-wise payments delete permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],

             [
                'name'=> 'Invoice-wise Payments Update',
                'slug'=> 'account.payments.invoice-wise-payments.update',
                'description'=> 'Invoice-wise payments update permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],
             [
                'name'=> 'Invoice-wise Payments Verify',
                'slug'=> 'account.payments.invoice-wise-payments.verify',
                'description'=> 'Invoice-wise payments verify permission',
                'key'=> 'account.payments.invoice-wise-payments'

             ],
             [
                'name'=> 'Invoice-wise Payments Approve',
                'slug'=> 'account.payments.invoice-wise-payments.approve',
                'description'=> 'Invoice-wise payments approve permission',
                'key'=> 'account.payments.invoice-wise-payments'
             ],
            
             //Broker Payments
             [
                'name'=> 'Broker Payments List',
                'slug'=> 'account.payments.broker-payments.index',
                'description'=> 'Broker payments list permission',
                'key'=> 'account.payments.broker-payments'
             ],

             [
                'name'=> 'Create Broker Payments',
                'slug'=> 'account.payments.broker-payments.create',
                'description'=> 'Broker payments create permission',
                'key'=> 'account.payments.broker-payments'
             ], 

            //  [ 
            //     'name'=> 'Broker Payments View',
            //     'slug'=> 'account.payments.broker-payments.show',
            //     'description'=> 'Broker payments show permission',
            //     'key'=> 'account.payments.broker-payments'
            //  ], 

            // [ 
            //     'name'=> 'Broker Payments Delete',
            //     'slug'=> 'account.payments.broker-payments.destroy',
            //     'description'=> 'Broker payments delete permission',
            //     'key'=> 'account.payments.broker-payments'
            // ], 

            [
                'name'=> 'Broker Payments Approve',
                'slug'=> 'account.payments.broker-payments.approve',
                'description'=> 'Broker payments approve permission',
                'key'=> 'account.payments.broker-payments'
            ],
            [
                'name'=> 'Broker Payments Verify',
                'slug'=> 'account.payments.broker-payments.verify',
                'description'=> 'Broker payments verify permission',
                'key'=> 'account.payments.broker-payments'
            ],
            //TA/DA Payments   
            [
                'name'=> 'TA/DA Payments List',
                'slug'=> 'account.payments.petty-cash-payments.index',
                'description'=> 'TA/DA payments list permission',
                'key'=> 'account.payments.petty-cash-payments'
             ],
             [
                'name'=> 'Create TA/DA Payments',
                'slug'=> 'account.payments.petty-cash-payments.create',
                'description'=> 'TA/DA payments create permission',
                'key'=> 'account.payments.petty-cash-payments'
             ],


            //Loan Payments   
            [
                'name'=> 'Loan Payments List',
                'slug'=> 'account.payments.loan-payment.index',
                'description'=> 'Loan payments list permission',
                'key'=> 'account.payments.loan-payment'
            ],
            [
                'name'=> 'Loan Payments',
                'slug'=> 'account.payments.loan-payment.payment',
                'description'=> 'Loan payments create permission',
                'key'=> 'account.payments.loan-payment'
            ],

            //Vender bill settings account.vendor-bills.settings

             [
                'name'=> 'Vendor Bill Settings List', 
                'slug'=> 'account.vendor-bills.settings.index',
                'description'=> 'Vendor bill settings list permission',
                'key'=> 'account.vendor-bills.settings'
             ],
             [
                'name'=> 'Create Vendor Bill Settings',
                'slug'=> 'account.vendor-bills.settings.create',
                'description'=> 'Vendor bill settings create permission',
                'key'=> 'account.vendor-bills.settings'
             ],
             [
                'name'=> 'Vendor Bill Settings View',
                'slug'=> 'account.vendor-bills.settings.show',
                'description'=> 'Vendor bill settings show permission',
                'key'=> 'account.vendor-bills.settings'
             ],
             [
                'name'=> 'Vendor Bill Settings Delete',
                'slug'=> 'account.vendor-bills.settings.destroy',
                'description'=> 'Vendor bill settings delete permission',
                'key'=> 'account.vendor-bills.settings'
             ],
             [
                'name'=> 'Vendor Bill Settings Update',
                'slug'=> 'account.vendor-bills.settings.update',
                'description'=> 'Vendor bill settings update permission',
                'key'=> 'account.vendor-bills.settings'
             ],

             // vendor-bill.generated-vendor-bills.edit 
             [
                'name' => 'Vendor Bill Generated List',
                'slug' => 'account.vendor-bills.generated-vendor-bills.index',
                'description' => 'Vendor bill generated list permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],
             [
                'name' => 'Vendor Bill Generated Edits',
                'slug' => 'account.vendor-bills.generated-vendor-bills.update',
                'description' => 'Vendor bill generated edits permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],
             [
                'name' => 'Vendor Bill Generated Verify',
                'slug' => 'account.vendor-bills.generated-vendor-bills.verify',
                'description' => 'Vendor bill generated verify permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],
             [
                'name' => 'Vendor Bill Generated Approve',
                'slug' => 'account.vendor-bills.generated-vendor-bills.approve',
                'description' => 'Vendor bill generated approve permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],
             [
                'name' => 'Vendor Bill Generated View',
                'slug' => 'account.vendor-bills.generated-vendor-bills.show',
                'description' => 'Vendor bill generated view permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],
             [
                'name' => 'Vendor Bill Generated Delete',
                'slug' => 'account.vendor-bills.generated-vendor-bills.destroy',
                'description' => 'Vendor bill generated delete permission',
                'key' => 'account.vendor-bills.generated-vendor-bills'
             ],

             //  account.i-o-u-requisition.i-o-u-requisition-entries
             [
                'name' => 'IOU Requisition List',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.index',
                'description' => 'IOU requisition list permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Create',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.create',
                'description' => 'IOU requisition create permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition View',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.show',
                'description' => 'IOU requisition show permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Delete',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.destroy',
                'description' => 'IOU requisition delete permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Update',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.update',
                'description' => 'IOU requisition update permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Approve',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.approve',
                'description' => 'IOU requisition approve permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Verify',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.verify',
                'description' => 'IOU requisition verify permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Pay',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.pay',
                'description' => 'IOU requisition pay permission',
                'key' => 'account.i-o-u-requisition'
             ],
             [
                'name' => 'IOU Requisition Return Pay',
                'slug' => 'account.i-o-u-requisition.i-o-u-requisition-entries.return',
                'description' => 'IOU requisition return pay permission',
                'key' => 'account.i-o-u-requisition'
             ],



             //Voucher Contras
             [
                'name'=> 'Voucher Contras List',
                'slug'=> 'account.voucher-contras.index',
                'description'=> 'Voucher contras list permission',
                'key'=> 'account.voucher-contras'
             ],

             [
                'name'=> 'Create Voucher Contras',
                'slug'=> 'account.voucher-contras.create',
                'description'=> 'Voucher contras create permission',
                'key'=> 'account.voucher-contras'
             ],

             [
                'name'=> 'Voucher Contras View',
                'slug'=> 'account.voucher-contras.show',
                'description'=> 'Voucher contras show permission',
                'key'=> 'account.voucher-contras'
             ],

             [
                'name'=> 'Voucher Contras Delete',
                'slug'=> 'account.voucher-contras.destroy',
                'description'=> 'Voucher contras delete permission',
                'key'=> 'account.voucher-contras'
             ],

             [
                'name'=> 'Voucher Contras Approve',
                'slug'=> 'account.voucher-contras.approve',
                'description'=> 'Voucher contras approve permission',
                'key'=> 'account.voucher-contras'
             ],

             [
                'name'=> 'Voucher Contras Edit',
                'slug'=> 'account.voucher-contras.update',
                'description'=> 'Voucher contras edit permission',
                'key'=> 'account.voucher-contras'
             ],


             //Voucher Journals
             [
                'name'=> 'Voucher Journals List',
                'slug'=> 'account.voucher-journals.index',
                'description'=> 'Voucher journals list permission',
                'key'=> 'account.voucher-journals'
             ],

             [
                'name'=> 'Create Voucher Journals',
                'slug'=> 'account.voucher-journals.create',
                'description'=> 'Voucher journals create permission',
                'key'=> 'account.voucher-journals'
             ],

             [
                'name'=> 'Voucher Journals View',
                'slug'=> 'account.voucher-journals.show',
                'description'=> 'Voucher journals show permission',
                'key'=> 'account.voucher-journals'
             ],

             [
                'name'=> 'Voucher Journals Delete',
                'slug'=> 'account.voucher-journals.destroy',
                'description'=> 'Voucher journals delete permission',
                'key'=> 'account.voucher-journals'
             ],

             [
                'name'=> 'Voucher Journals Approve',
                'slug'=> 'account.voucher-journals.approve',
                'description'=> 'Voucher journals approve permission',
                'key'=> 'account.voucher-journals'
             ],

             [
                'name'=> 'Voucher Journals Edit',
                'slug'=> 'account.voucher-journals.update',
                'description'=> 'Voucher journals edit permission',
                'key'=> 'account.voucher-journals'
             ],


             //Voucher Payments
             [
                'name'=> 'Voucher Payments List',
                'slug'=> 'account.voucher-payments.index',
                'description'=> 'Voucher payments list permission',
                'key'=> 'account.voucher-payments'
             ],

             [
                'name'=> 'Create Voucher Payments',
                'slug'=> 'account.voucher-payments.create',
                'description'=> 'Voucher payments create permission',
                'key'=> 'account.voucher-payments'
             ],

             [
                'name'=> 'Voucher Payments View',
                'slug'=> 'account.voucher-payments.show',
                'description'=> 'Voucher payments show permission',
                'key'=> 'account.voucher-payments'
             ],

             [
                'name'=> 'Voucher Payments Delete',
                'slug'=> 'account.voucher-payments.destroy',
                'description'=> 'Voucher payments delete permission',
                'key'=> 'account.voucher-payments'
             ],

             [
                'name'=> 'Voucher Payments Approve',
                'slug'=> 'account.voucher-payments.approve',
                'description'=> 'Voucher payments approve permission',
                'key'=> 'account.voucher-payments'
             ],

             [
                'name'=> 'Voucher Payments Edit',
                'slug'=> 'account.voucher-payments.update',
                'description'=> 'Voucher payments edit permission',
                'key'=> 'account.voucher-payments'
             ],


             //Voucher Receives
             [
                'name'=> 'Voucher Receives List',
                'slug'=> 'account.voucher-receives.index',
                'description'=> 'Voucher receives list permission',
                'key'=> 'account.voucher-receives'
             ],

             [
                'name'=> 'Create Voucher Receives',
                'slug'=> 'account.voucher-receives.create',
                'description'=> 'Voucher receives create permission',
                'key'=> 'account.voucher-receives'
             ],

             [
                'name'=> 'Voucher Receives View',
                'slug'=> 'account.voucher-receives.show',
                'description'=> 'Voucher receives show permission',
                'key'=> 'account.voucher-receives'
             ],

             [
                'name'=> 'Voucher Receives Delete',
                'slug'=> 'account.voucher-receives.destroy',
                'description'=> 'Voucher receives delete permission',
                'key'=> 'account.voucher-receives'
             ],

             [
                'name'=> 'Voucher Receives Edit',
                'slug'=> 'account.voucher-receives.update',
                'description'=> 'Voucher receives edit permission',
                'key'=> 'account.voucher-receives'
             ],

             [
                'name'=> 'Voucher Receives Approve',
                'slug'=> 'account.voucher-receives.approve',
                'description'=> 'Voucher receives approve permission',
                'key'=> 'account.voucher-receives'
             ],
             [
                'name' => 'Account Ledger Report',
                'slug' => 'account.report.account-ledger',
                'description' => 'Permission for account ledger report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Account Payable Report',
                'slug' => 'account.report.account-payable',
                'description' => 'Permission for account payable report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Account Receivable Report',
                'slug' => 'account.report.account-receivable',
                'description' => 'Permission for account receivable report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Chart of Account Report',
                'slug' => 'account.report.chart-of-account',
                'description' => 'Permission for chart of account report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Customer Ledger Report',
                'slug' => 'account.report.customer-ledger',
                'description' => 'Permission for customer ledger report',
                'key' => 'account.report'
            ],
            // [
            //     'name' => 'Expense Analysis Report',
            //     'slug' => 'account.report.expense-analysis',
            //     'description' => 'Permission for expense analysis report',
            //     'key' => 'account.report'
            // ],
            [
                'name' => 'Balance Sheet Report',
                'slug' => 'account.report.balance-sheet',
                'description' => 'Permission for balance sheet report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Cash Flow Report',
                'slug' => 'account.report.cash.flow',
                'description' => 'Permission for cash flow report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Equity Statement Report',
                'slug' => 'account.report.equity-statement',
                'description' => 'Permission for equity statement report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Income Statement Report',
                'slug' => 'account.report.income-statement',
                'description' => 'Permission for income statement report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Trial Balance Report',
                'slug' => 'account.report.trial-balance',
                'description' => 'Permission for trial balance report',
                'key' => 'account.report'
            ],
            [
                'name' => 'Ledger Journal Report',
                'slug' => 'account.report.ledger-journal',
                'description' => 'Permission for ledger journal report',
                'key' => 'account.report'
            ],
            // [
            //     'name' => 'Nominal Account Ledger Report',
            //     'slug' => 'account.report.nominal-account-ledger',
            //     'description' => 'Permission for nominal account ledger report',
            //     'key' => 'account.report'
            // ],
            // [
            //     'name' => 'Ratio Analysis Report',
            //     'slug' => 'account.report.ratio-analysis',
            //     'description' => 'Permission for ratio analysis report',
            //     'key' => 'account.report'
            // ],
            // [
            //     'name' => 'Received Payment Statement Report',
            //     'slug' => 'account.report.received-payment-statement',
            //     'description' => 'Permission for received payment statement report',
            //     'key' => 'account.report'
            // ],
            // [
            //     'name' => 'Revenue Analysis Report',
            //     'slug' => 'account.report.revenue-analysis',
            //     'description' => 'Permission for revenue analysis report',
            //     'key' => 'account.report'
            // ],
            // [
            //     'name' => 'Subsidiary Wise Ledger Report',
            //     'slug' => 'account.report.subsidiary-wise-ledger',
            //     'description' => 'Permission for subsidiary wise ledger report',
            //     'key' => 'account.report'
            // ],
            [
                'name' => 'Supplier Ledger Report',
                'slug' => 'account.report.supplier-ledger',
                'description' => 'Permission for supplier ledger report',
                'key' => 'account.report'
            ],
             [
                'name' => 'Vendor Ledger Report',
                'slug' => 'account.report.vendor-ledger',
                'description' => 'Permission for vendor ledger report',
                'key' => 'account.report'
            ],
            // [
            //     'name' => 'Supplier Report',
            //     'slug' => 'account.report.supplier',
            //     'description' => 'Permission for supplier report',
            //     'key' => 'account.report'
            // ],
            // [
            //     'name' => 'Transaction Ledger Report',
            //     'slug' => 'account.report.transaction-ledger',
            //     'description' => 'Permission for transaction ledger report',
            //     'key' => 'account.report'
            // ],
            [
                'name' => 'Voucher Report',
                'slug' => 'account.report.voucher-report',
                'description' => 'Permission for voucher report',
                'key' => 'account.report'
            ],

            //Dongle Or Serial Entries
            [
                'name'=> 'Dongle Or Serial Entries Create',
                'slug'=> 'licenses.dongle-or-serial-entries.create',
                'description'=> 'Dongle Or Serial Entries create permission',
                'key'=> 'licenses.dongle-or-serial-entries'
            ],

            [
                'name'=> 'Dongle Or Serial Entries List',
                'slug'=> 'licenses.dongle-or-serial-entries.index',
                'description'=> 'Dongle Or Serial Entries list permission',
                'key'=> 'licenses.dongle-or-serial-entries'
            ],

            [
                'name'=> 'Dongle Or Serial Entries Update',
                'slug'=> 'licenses.dongle-or-serial-entries.update',
                'description'=> 'Dongle Or Serial Entries update permission',
                'key'=> 'licenses.dongle-or-serial-entries'
            ],

            [
                'name'=> 'Dongle Or Serial Entries View',
                'slug'=> 'licenses.dongle-or-serial-entries.show',
                'description'=> 'Dongle Or Serial Entries show permission',
                'key'=> 'licenses.dongle-or-serial-entries'
            ],

            [
                'name'=> 'Dongle Or Serial Entries Delete',
                'slug'=> 'licenses.dongle-or-serial-entries.destroy',
                'description'=> 'Dongle Or Serial Entries delete permission',
                'key'=> 'licenses.dongle-or-serial-entries'
            ],

            //usg-opg-license-requisitions
            [
                'name'=> 'USG OPG License Requisitions Create',
                'slug'=> 'licenses.usg-opg-license-requisitions.create',
                'description'=> 'USG OPG License Requisitions create permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],

            [
                'name'=> 'USG OPG License Requisitions List',
                'slug'=> 'licenses.usg-opg-license-requisitions.index',
                'description'=> 'USG OPG License Requisitions list permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],

            [
                'name'=> 'USG OPG License Requisitions Update',
                'slug'=> 'licenses.usg-opg-license-requisitions.update',
                'description'=> 'USG OPG License Requisitions update permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],

            [
                'name'=> 'USG OPG License Requisitions View',
                'slug'=> 'licenses.usg-opg-license-requisitions.show',
                'description'=> 'USG OPG License Requisitions show permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],

            [
                'name'=> 'USG OPG License Requisitions Delete',
                'slug'=> 'licenses.usg-opg-license-requisitions.destroy',
                'description'=> 'USG OPG License Requisitions delete permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],
             [
                'name'=> 'USG OPG License Requisitions Approve',
                'slug'=> 'licenses.usg-opg-license-requisitions.approve',
                'description'=> 'USG OPG License Requisitions approve permission',
                'key'=> 'licenses.usg-opg-license-requisitions'
            ],


            //cbc-license-requisitions
            [
                'name'=> 'CBC License Requisitions Create',
                'slug'=> 'licenses.cbc-license-requisitions.create',
                'description'=> 'CBC License Requisitions create permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],

            [
                'name'=> 'CBC License Requisitions List',
                'slug'=> 'licenses.cbc-license-requisitions.index',
                'description'=> 'CBC License Requisitions list permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],

            [
                'name'=> 'CBC License Requisitions Update',
                'slug'=> 'licenses.cbc-license-requisitions.update',
                'description'=> 'CBC License Requisitions update permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],

            [
                'name'=> 'CBC License Requisitions View',
                'slug'=> 'licenses.cbc-license-requisitions.show',
                'description'=> 'CBC License Requisitions show permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],

            [
                'name'=> 'CBC License Requisitions Delete',
                'slug'=> 'licenses.cbc-license-requisitions.destroy',
                'description'=> 'CBC License Requisitions delete permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],
            [
                'name'=> 'CBC License Requisitions Approve',
                'slug'=> 'licenses.cbc-license-requisitions.approve',
                'description'=> 'CBC License Requisitions delete permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],
            [
                'name'=> 'CBC License Requisitions Approve',
                'slug'=> 'licenses.cbc-license-requisitions.approve',
                'description'=> 'CBC License Requisitions delete permission',
                'key'=> 'licenses.cbc-license-requisitions'
            ],

            //licenses.usg-opg-sms
            [
                'name'=> 'USG OPG SMS Update',
                'slug'=> 'licenses.usg-opg-sms.update',
                'description'=> 'USG OPG SMS update permission',
                'key'=> 'licenses.usg-opg-sms'
            ],
            [
                'name'=> 'CBC SMS Update',
                'slug'=> 'licenses.cbc-sms.update',
                'description'=> 'CBC SMS update permission',
                'key'=> 'licenses.cbc-sms'
            ],

            //reports
            [
                'name'=> 'Report List',
                'slug'=> 'licenses.reports.index',
                'description'=> 'Report list permission',
                'key'=> 'licenses.reports'
            ],

            //service
            [
                'name'=> 'Service Create',
                'slug'=> 'services.service.create',
                'description'=> 'Service create permission',
                'key'=> 'services.service',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service List',
                'slug'=> 'services.service.index',
                'description'=> 'Service list permission',
                'key'=> 'services.service',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service Update',
                'slug'=> 'services.service.update',
                'description'=> 'Service update permission',
                'key'=> 'services.service',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service View',
                'slug'=> 'services.service.show',
                'description'=> 'Service show permission',
                'key'=> 'services.service',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service Delete',
                'slug'=> 'services.service.destroy',
                'description'=> 'Service delete permission',
                'key'=> 'services.service',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service Type Create',
                'slug'=> 'services.settings.service-types.create',
                'description'=> 'Service type create permission',
                'key'=> 'services.settings.service-types',
                'parent_key' => 'services.settings',
            ],

            [
                'name'=> 'Service Type List',
                'slug'=> 'services.settings.service-types.index',
                'description'=> 'Service type list permission',
                'key'=> 'services.settings.service-types',
                'parent_key' => 'services.settings',
            ],

            [
                'name'=> 'Service Type Update',
                'slug'=> 'services.settings.service-types.update',
                'description'=> 'Service type update permission',
                'key'=> 'services.settings.service-types',
                'parent_key' => 'services.settings',
            ],

            // [
            //     'name'=> 'Service Type View',
            //     'slug'=> 'services.settings.service-types.show',
            //     'description'=> 'Service type show permission',
            //     'key'=> 'services.settings.service-types',
            //     'parent_key' => 'services.settings',
            // ],

            [
                'name'=> 'Service Type Delete',
                'slug'=> 'services.settings.service-types.destroy',
                'description'=> 'Service type delete permission',
                'key'=> 'services.settings.service-types',
                'parent_key' => 'services.settings',
            ],

            //service-assign
            // [
            //     'name'=> 'Service Assign Create',
            //     'slug'=> 'services.service-assign.create',
            //     'description'=> 'Service assign create permission',
            //     'key'=> 'services.service-assign',
            //     'parent_key' => 'services',
            // ],

            [
                'name'=> 'Service Assign List',
                'slug'=> 'services.service-assign.index',
                'description'=> 'Service assign list permission',
                'key'=> 'services.service-assign',
                'parent_key' => 'services',
            ],

            // [
            //     'name'=> 'Service Assign Update',
            //     'slug'=> 'services.service-assign.update',
            //     'description'=> 'Service assign update permission',
            //     'key'=> 'services.service-assign',
            //     'parent_key' => 'services',
            // ],

            // [
            //     'name'=> 'Service Assign View',
            //     'slug'=> 'services.service-assign.show',
            //     'description'=> 'Service assign show permission',
            //     'key'=> 'services.service-assign',
            //     'parent_key' => 'services',
            // ],

            // [
            //     'name'=> 'Service Assign Delete',
            //     'slug'=> 'services.service-assign.destroy',
            //     'description'=> 'Service assign delete permission',
            //     'key'=> 'services.service-assign',
            //     'parent_key' => 'services',
            // ],

            [
                'name'=> 'Service My Task Details',
                'slug'=> 'services.service-my-task.create',
                'description'=> 'Service my task create permission',
                'key'=> 'services.service-my-task',
                'parent_key' => 'services',
            ],

            [
                'name'=> 'Service My Task List',
                'slug'=> 'services.service-my-task.index',
                'description'=> 'Service my task list permission',
                'key'=> 'services.service-my-task',
                'parent_key' => 'services',
            ],

            // [
            //     'name'=> 'Service My Task Update',
            //     'slug'=> 'services.service-my-task.update',
            //     'description'=> 'Service my task update permission',
            //     'key'=> 'services.service-my-task',
            //     'parent_key' => 'services',
            // ],

            // [
            //     'name'=> 'Service My Task View',
            //     'slug'=> 'services.service-my-task.show',
            //     'description'=> 'Service my task show permission',
            //     'key'=> 'services.service-my-task',
            //     'parent_key' => 'services',
            // ],

            // [
            //     'name'=> 'Service My Task Delete',
            //     'slug'=> 'services.service-my-task.destroy',
            //     'description'=> 'Service my task delete permission',
            //     'key'=> 'services.service-my-task',
            // ],

            [
                'name' => 'Service My Task Solution Verification',
                'slug' => 'services.service-my-task.solution-verification',
                'description' => 'Service my task solution verification permission',
                'key' => 'services.service-my-task',
            ],

            //Document Entry
            [
                'name'=> 'Document Entry Create',
                'slug'=> 'services.document-entries.create',
                'description'=> 'Document entry create permission',
                'key'=> 'services.document-entries',
                
            ],

            [
                'name'=> 'Document Entry List',
                'slug'=> 'services.document-entries.index',
                'description'=> 'Document entry list permission',
                'key'=> 'services.document-entries',
                
            ],

            [
                'name'=> 'Document Entry Update',
                'slug'=> 'services.document-entries.update',
                'description'=> 'Document entry update permission',
                'key'=> 'services.document-entries',
                
            ],

            // [
            //     'name'=> 'Document Entry View',
            //     'slug'=> 'services.document-entries.show',
            //     'description'=> 'Document entry show permission',
            //     'key'=> 'services.document-entries',
                
            // ],

            [
                'name'=> 'Document Entry download',
                'slug'=> 'services.document-entries.download',
                'description'=> 'Document entry print permission',
                'key'=> 'services.document-entries',
            ],

            [
                'name'=> 'Document Entry Delete',
                'slug'=> 'services.document-entries.destroy',
                'description'=> 'Document entry delete permission',
                'key'=> 'services.document-entries',
            ],
            //Quotation 
// services.quotations.index
            [
                'name'=> 'Quotation Create',
                'slug'=> 'services.quotations.create',
                'description'=> 'Quotation create permission',
                'key'=> 'services.quotations',
                
            ],
            [
                'name'=> 'Quotation List',
                'slug'=> 'services.quotations.index',
                'description'=> 'Quotation list permission',
                'key'=> 'services.quotations',
                
            ],

            [
                'name'=> 'Quotation Update',
                'slug'=> 'services.quotations.update',
                'description'=> 'Quotation update permission',
                'key'=> 'services.quotations',
            ],

            [
                'name'=> 'Quotation Sales Order',
                'slug'=> 'services.quotations.sales-order',
                'description'=> 'Quotation sales order list permission',
                'key'=> 'services.quotations',
            ],
            // [
            //     'name'=> 'Quotation View',
            //     'slug'=> 'services.quotations.show',
            //     'description'=> 'Quotation show permission',
            //     'key'=> 'services.quotations',
                
            // ],

            [
                'name'=> 'Quotation Print',
                'slug'=> 'services.quotations.print',
                'description'=> 'Quotation print permission',
                'key'=> 'services.quotations',
                
            ],

            [
                'name'=> 'Quotation Delete',
                'slug'=> 'services.quotations.destroy',
                'description'=> 'Quotation delete permission',
                'key'=> 'services.quotations',
            ],


            // 📂 Legal Entries (resource)
            [
                'name'=> 'Create Legal Entry',
                'slug'=> 'legal.legal-entries.create',
                'description'=> 'Permission to create legal entries',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'List Legal Entries',
                'slug'=> 'legal.legal-entries.index',
                'description'=> 'Permission to view legal entries',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'Edit Legal Entry',
                'slug'=> 'legal.legal-entries.update',
                'description'=> 'Permission to edit legal entries',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'Delete Legal Entry',
                'slug'=> 'legal.legal-entries.destroy',
                'description'=> 'Permission to delete legal entries',
                'key'=> 'legal.legal-entries',
            ],

            // ✅ Custom Legal Entry Actions
            [
                'name'=> 'Approve Legal Entry',
                'slug'=> 'legal.legal-entries.approve',
                'description'=> 'Permission to approve legal entries',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'Deny Legal Entry',
                'slug'=> 'legal.legal-entries.deny',
                'description'=> 'Permission to deny legal entries',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'Update Legal Schedule',
                'slug'=> 'legal.legal-entries.legal-schedule-update',
                'description'=> 'Permission to update legal schedule',
                'key'=> 'legal.legal-entries',
            ],
            [
                'name'=> 'Legal Report',
                'slug'=> 'legal.legal-entries.reports',
                'description'=> 'Permission to update legal Report',
                'key'=> 'legal.legal-entries',
            ],

            // 📂 Legal Bill Entries (resource)
            [
                'name'=> 'Create Legal Bill Entry',
                'slug'=> 'legal.legal-bill-entries.create',
                'description'=> 'Permission to create legal bill entries',
                'key'=> 'legal.legal-bill-entries',
            ],
            [
                'name'=> 'List Legal Bill Entries',
                'slug'=> 'legal.legal-bill-entries.index',
                'description'=> 'Permission to view legal bill entries',
                'key'=> 'legal.legal-bill-entries',
            ],
            [
                'name'=> 'Edit Legal Bill Entry',
                'slug'=> 'legal.legal-bill-entries.update',
                'description'=> 'Permission to edit legal bill entries',
                'key'=> 'legal.legal-bill-entries',
            ],
            [
                'name'=> 'Delete Legal Bill Entry',
                'slug'=> 'legal.legal-bill-entries.destroy',
                'description'=> 'Permission to delete legal bill entries',
                'key'=> 'legal.legal-bill-entries',
            ],
            [
                'name'=> 'View Legal Bill Entry',
                'slug'=> 'legal.legal-bill-entries.show',
                'description'=> 'Permission to view legal bill schedule',
                'key'=> 'legal.legal-bill-entries',
            ],
            

            // CRM Reports
            [
                'name' => 'Customer List (Machine Code) Report',
                'description' => 'Permission to view CRM reports',
                'slug' => 'crm.reports.customer-machine-code',
                'key' => 'crm.reports',
            ],
            [
                'name' => 'Customer Balance Details Report',
                'description' => 'Permission to view customer balance details',
                'slug' => 'crm.reports.customer-balance-details',
                'key' => 'crm.reports',
            ],

            // Inventory Reports
            [
                'name' => 'Product Wise Stock Report',
                'description' => 'Permission to view product stock report',
                'slug' => 'inv.reports.product-stock',
                'key' => 'inv.reports',
            ],
            [
                'name' => 'Product Transfer Report',
                'description' => 'Permission to view product transfer report',
                'slug' => 'inv.reports.product-transfer',
                'key' => 'inv.reports',
            ],
            [
                'name' => 'Catalogue Report',
                'description' => 'Permission to view catalogue report',
                'slug' => 'inv.reports.catalogue-report',
                'key' => 'inv.reports',
            ],
            [
                'name' => 'Stock Balance Report',
                'description' => 'Permission to view stock balance report',
                'slug' => 'inv.reports.stock-balance',
                'key' => 'inv.reports',
            ],
            [
                'name' => 'Center Stock Report',
                'description' => 'Permission to view center-wise stock report',
                'slug' => 'inv.reports.center-stock',
                'key' => 'inv.reports',
            ],

            // Sales Reports
            [
                'name' => 'Sales Report',
                'description' => 'Permission to view sales report',
                'slug' => 'sales.reports.sales-report',
                'key' => 'sales.reports',
            ],
            [
                'name' => 'Fake Sales Report',
                'description' => 'Permission to view fake sales report',
                'slug' => 'sales.reports.fake-sales',
                'key' => 'sales.reports',
            ],
            [
                'name' => 'Broker Commission Report',
                'description' => 'Permission to view broker commission report',
                'slug' => 'sales.reports.broker-commissions',
                'key' => 'sales.reports',
            ],
            [
                'name' => 'Brand Supplier Sales Report',
                'description' => 'Permission to view brand supplier sales report',
                'slug' => 'sales.reports.brand-supplier-sales-report',
                'key' => 'sales.reports',
            ],
            [
                'name' => 'Shipment Explorer Report',
                'description' => 'Permission to view shipment explorer report',
                'slug' => 'sales.reports.shipment-explorer',
                'key' => 'sales.reports',
            ],

            // Service Reports
            [
                'name' => 'Service Report',
                'description' => 'Permission to view service reports',
                'slug' => 'services.reports.service-reports',
                'key' => 'services.reports',
            ],
            [
                'name' => 'Warranty Check Report',
                'description' => 'Permission to view warranty check report',
                'slug' => 'services.reports.warranty-check',
                'key' => 'services.reports',
            ],
            [
                'name' => 'Service Explorer Report',
                'description' => 'Permission to view service explorer report',
                'slug' => 'services.reports.service-explorer-reports',
                'key' => 'services.reports',
            ],
            [
                'name' => 'Monthly Service Report',
                'description' => 'Permission to view monthly service report',
                'slug' => 'services.reports.monthly-service-reports',
                'key' => 'services.reports',
            ],
            [
                'name' => 'Installation & Servicing Report',
                'description' => 'Permission to view installation report',
                'slug' => 'services.reports.installation-reports',
                'key' => 'services.reports',
            ],

            // License Reports
            [
                'name' => 'License Report',
                'description' => 'Permission to view license reports',
                'slug' => 'licenses.reports.index',
                'key' => 'licenses.reports',
            ],

            // Achievement Based Salary Policy view page
            [
                'name' => 'Achievement Based Salary Policy',
                'description' => 'Permission to view achievement based salary policy',
                'slug' => 'sales_target.settings.achievement-based-salary-policy.index',
                'key' => 'sales_target.settings.achievement-based-salary-policy',
            ],
            //create
            [
                'name' => 'Achievement Based Salary Policy Create',
                'description' => 'Permission to create achievement based salary policy',
                'slug' => 'sales_target.settings.achievement-based-salary-policy.create',
                'key' => 'sales_target.settings.achievement-based-salary-policy',
            ],
            //update
            [
                'name' => 'Achievement Based Salary Policy Update',
                'description' => 'Permission to update achievement based salary policy',
                'slug' => 'sales_target.settings.achievement-based-salary-policy.update',
                'key' => 'sales_target.settings.achievement-based-salary-policy',       
            ],
            //delete
            [
                'name' => 'Achievement Based Salary Policy Delete',
                'description' => 'Permission to delete achievement based salary policy',                
                'slug' => 'sales_target.settings.achievement-based-salary-policy.destroy',
                'key' => 'sales_target.settings.achievement-based-salary-policy',
            ],
            //details   
            [
                'name' => 'Achievement Based Salary Policy Details',
                'description' => 'Permission to view achievement based salary policy details',
                'slug' => 'sales_target.settings.achievement-based-salary-policy.show',
                'key' => 'sales_target.settings.achievement-based-salary-policy',
            ],
 
        ];

        foreach ($permissions as $permission) {
            try {
                 $master = PermissionMaster::where('key', $permission['key'])->first();
            // $master->permissions()->updateOrCreate([
            //     'slug' => $permission['slug'],
            // ], [
            //     'name' => $permission['name'],
            //     'slug' => $permission['slug'],
            //     'description' => $permission['description']
            // ]);
            
            DB::table('permissions')->insert([
                
            ]);
            Permission::updateOrCreate([
                'slug' => $permission['slug'],
            ], [
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'description' => $permission['description'],
                'permission_master_id' => $master->id
            ]);
            } catch (\Throwable $th) {
                dd( $th->getMessage(). " ".$permission['slug']);
            }
           
        }
    }
}
