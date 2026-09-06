<?php

namespace Database\Seeders;

use App\Models\AccessControl\PermissionMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'title' => 'Dashboard',
                'description' => "Dashboard for user or admin ",
                'key' => 'dashboard', 
            ],
 
            //CMS
            [
                'title' => 'CMS',
                'description' => "Permission of Add, Remove, Update, Delete CMS",
                'key' => 'cms',
            ],

            //Document Entries
            [
                'title' => 'Document Entries',
                'description' => "Permission of Add, Remove, Update, Delete Document Entries",
                'key' => 'cms.document-entries',
                'parent_key' => 'cms',
            ],

            //Application Entries
            [
                'title' => 'Application Entries',
                'description' => "Permission of Add, Remove, Update, Delete Application Entries",
                'key' => 'cms.application-entries',
                'parent_key' => 'cms',
            ],

            //Document Types
            [
                'title' => 'Document Types',
                'description' => "Permission of Add, Remove, Update, Delete Document Types",
                'key' => 'cms.document-types',
                'parent_key' => 'cms',
            ],
            //Document Head
            [
                'title' => 'Document Heads',
                'description' => "Permission of Add, Remove, Update, Delete Document Heads",
                'key' => 'cms.document-heads',
                'parent_key' => 'cms',
            ],



            //CRM
            [
                'title' => 'Crm',
                'description' => "Permission of Add, Remove, Update, Delete Crm",
                'key' => 'crm',
            ],
            //customers
            [
                'title' => 'Customer',
                'description' => "Permission of Add, Remove, Update, Delete Customer",
                'key' => 'crm.customers',
                'parent_key' => 'crm',
            ],
            //brokers
            [
                'title' => 'Broker',
                'description' => "Permission of Add, Remove, Update, Delete Broker",
                'key' => 'crm.brokers',
                'parent_key' => 'crm',
            ],
            //daily-calls
            [
                'title' => 'Daily Calls',
                'description' => 'Permission of Add, Remove, Update, Delete Daily Calls',
                'key' => 'crm.daily-calls',
                'parent_key' => 'crm',
            ],
            //daily-Credit-calls
            [
                'title' => 'Daily Credit Calls',
                'description' => 'Permission of Add, Legal, Show Daily Credit Calls',
                'key' => 'crm.daily-credit-calls',
                'parent_key' => 'crm',
            ],
            
            //customer-types
            [
                'title' => 'Customer Types',
                'description' => "Permission of Add, Remove, Update, Delete Customer Types",
                "key" => "crm.customer-types",
                'parent_key' => 'crm',
            ],
            //customer-ratings
            [
                'title' => 'Customer Ratings',
                'description' => "Permission of Add, Remove, Update, Delete Customer Ratings",
                'key' => 'crm.customer-ratings',
                'parent_key' => 'crm',
            ],
            //customer-shippings
            [
                'title' => 'Customer Shippings',
                'description' => "Permission of Add, Remove, Update, Delete Customer Shippings",
                'key' => 'crm.customer-shippings',
                'parent_key' => 'crm',
            ],
            
            //Reports
            [
                'title' => 'Reports',
                'description' => 'Permission of View Reports',
                'key' => 'crm.reports',
                'parent_key' => 'crm',
            ],

            // hrm
            [
                'title' => 'Hrm & Payroll',
                'description' => "Permission of Add, Remove, Update, Delete Hrm & Payroll",
                'key' => 'hrm',
            ],
            //employees
            [
                'title' => 'Employee',
                'description' => "Permission of Add, Remove, Update, Delete Employee",
                'key' => 'hrm.employees',
                'parent_key' => 'hrm',
            ],

            //department
            [
                'title' => 'Departments',
                'description' => 'Permission of Add, Remove, Update, Delete Departments',
                'key' => 'hrm.departments',
                'parent_key' => 'hrm',
            ],

            //designation
            [
                'title' => 'Designations',
                'description' => 'Permission of Add, Remove, Update, Delete Designations',
                'key' => 'hrm.designations',
                'parent_key' => 'hrm',
            ],
            
            //Attendance
            [
                'title' => 'Attendance',
                'description' => "Permission of Add, Remove, Update, Delete Employee Attendance",
                'key' => 'hrm.attendances',
                'parent_key' => 'hrm',
            ],

            //Attendance Policy
            [
                'title' => 'Attendance Policy',
                'description' => "Permission of Add, Remove, Update, Delete Attendance Policy",
                'key' => 'hrm.attendance-policies',
                'parent_key' => 'hrm',
            ],

            //Leave
            [
                'title' => 'Leave',
                'description' => "Permission of Add, Remove, Update, Delete Employee Leave",
                'key' => 'hrm.leaves',
                'parent_key' => 'hrm',
            ],

            //Leave Application
            [
                'title' => 'Leave Application',
                'description' => "Permission of Add, Remove, Update, Delete Employee Leave Application",
                'key' => 'hrm.leaves',
                'parent_key' => 'hrm',
            ],

            //leave types
            [
                'title' => 'Leave Types',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Types',
                'key' => 'hrm.leave-types',
                'parent_key' => 'hrm',
            ],

            //leave group
            [
                'title' => 'Leave Groups',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Groups',
                'key' => 'hrm.leave-groups',
                'parent_key' => 'hrm',
            ],


            //leave Year
            [
                'title' => 'Leave Years',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Years',
                'key' => 'hrm.leave-years',
                'parent_key' => 'hrm',
            ],

            //leave Approver Setup
            [
                'title' => 'Leave Approver Setup',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Approver Setup',
                'key' => 'hrm.settings.leave-approvers',
                'parent_key' => 'hrm',
            ],

            //leave status
            [
                'title' => 'Leave Status',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Status',
                'key' => 'hrm.leave-statuses',
                'parent_key' => 'hrm',
            ],


            //leave eligible employee
            [
                'title' => 'Leave Eligible Employees',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Eligible Employees',
                'key' => 'hrm.leave-eligible-employees',
                'parent_key' => 'hrm',
            ],

            //leave adjustment
            [
                'title' => 'Leave Adjustments',
                'description' => 'Permission of Add, Remove, Update, Delete Leave Adjustments',
                'key' => 'hrm.leave-adjustments',
                'parent_key' => 'hrm',
            ],

            //salary generate
            [
                'title' => 'Payroll Generate',
                'description' => 'Permission of Add, Remove, Update, Delete Employee Salary',
                'key' => 'hrm.salary-generates',
                'parent_key' => 'hrm',
            ],

            //salary setup
            [
                'title' => 'Salary Setup',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Setup',
                'key' => 'hrm.salary-setups',
                'parent_key' => 'hrm',
            ],

            //salary generation policy
            [
                'title' => 'Salary Generation Policy',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Generation Policy',
                'key' => 'hrm.salary-generation-policies',
                'parent_key' => 'hrm',
            ],

            //salary deduction policy
            [
                'title' => 'Salary Deduction Policy',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Deduction Policy',
                'key' => 'hrm.salary-deduction-policies',
                'parent_key' => 'hrm',
            ],

             //salary Signatory
            [
                'title' => 'Salary Signatory',
                'description' => 'Permission of Add, Remove, Update, Delete Salary Signatory',
                'key' => 'hrm.salary-signatories',
                'parent_key' => 'hrm',
            ],

            //Bills
            [
                'title' => 'TA/DA',
                'description' => "Permission of Add, Remove, Update, Delete TA/DA",
                'key' => 'hrm.bills',
                'parent_key' => 'hrm',
            ],
            // daily visit plan
            [
                'title' => 'Daily Visit Plans',
                'description' => 'Permission of Add, Remove, Update, Delete Daily Visit Plans',
                'key' => 'hrm.daily-visit-plans',
                'parent_key' => 'hrm',
            ],
            //loan
            [
                'title' => 'Loans',
                'description' => 'Permission of Add, Remove, Update, Delete Loans',
                'key' => 'hrm.loans',
                'parent_key' => 'hrm',
            ],
 
            //kpi
            [
                'title' => 'KPIs',
                'description' => 'Permission of Add, Remove, Update, Delete KPIs',
                'key' => 'hrm.kpis',
                'parent_key' => 'hrm',
            ],
            //monthly kpi template
            [
                'title' => 'Monthly KPI Appraisals',
                'description' => 'Permission of Add, Remove, Update, Delete Monthly KPI Appraisals',
                'key' => 'hrm.kpis.monthly-kpi-appraisals',
                'parent_key' => 'hrm.kpis',
            ],
            //kpi assign
            [
                'title' => 'KPI Assignments',
                'description' => 'Permission of Add, Remove, Update, Delete KPI Assignments',
                'key' => 'hrm.kpis.kpi-assignments',
                'parent_key' => 'hrm.kpis',
            ],

            //kpi template
            [
                'title' => 'KPI Templates',
                'description' => 'Permission of Add, Remove, Update, Delete KPI Templates',
                'key' => 'hrm.kpis.kpi-templates',
                'parent_key' => 'hrm.kpis',
            ],

            //kpi suggestions
            [
                'title' => 'Score Wise Suggestions',
                'description' => 'Permission of Add, Remove, Update, Delete Score Wise Suggestions',
                'key' => 'hrm.kpis.score-wise-suggestions',
                'parent_key' => 'hrm.kpis',
            ],
            //kpi responsibility
            [
                'title' => 'Responsibility Entries',
                'description' => 'Permission of Add, Remove, Update, Delete Responsibility Entries',
                'key' => 'hrm.kpis.responsibility-entries',
                'parent_key' => 'hrm.kpis',
            ],
          
            //NoticeBoard
            [
                'title' => 'NoticeBoard',
                'description' => "Permission of Add, Remove, Update, Delete NoticeBoard",
                'key' => 'hrm.noticeboards',
                'parent_key' => 'hrm',
            ],
            //notice type
            [
                'title' => 'Notice Types',
                'description' => 'Permission of Add, Remove, Update, Delete Notice Types',
                'key' => 'hrm.notice-types',
                'parent_key' => 'hrm',
            ],

            //report
            [
                'title' => 'Report',
                'description' => 'Permission of Add, Remove, Update, Delete Report',
                'key' => 'hrm.report',
                'parent_key' => 'hrm',
            ],

           
            [
                'title' => 'Jobs',
                'description' => 'Permission of Add, Remove, Update, Delete Jobs',
                'key' => 'hrm.jobs',
                'parent_key' => 'hrm',
            ],
            [
                'title' => 'Job Templates',
                'description' => 'Permission of Add, Remove, Update, Delete Job Templates',
                'key' => 'hrm.job-templates',
                'parent_key' => 'hrm',
            ],
            [
                'title' => 'Job Applications',
                'description' => 'Permission of Add, Remove, Update, Delete Job Applications',
                'key' => 'hrm.job-applications',
                'parent_key' => 'hrm',
            ],
            [
                'title' => 'Reports',
                'description' => 'Permission of View Reports',
                'key' => 'hrm.reports',
                'parent_key' => 'hrm',
            ],
            //hrm settings
            [
                'title' => 'Settings',
                'description' => 'Permission of Add, Remove, Update, Delete HRMS Settings',
                'key' => 'hrm.settings',
                'parent_key' => 'hrm',
            ],
            //shifts
            [
                'title' => 'Shifts',
                'description' => 'Permission of Add, Remove, Update, Delete Shifts',
                'key' => 'hrm.settings.shifts',
                'parent_key' => 'hrm.settings',
            ],

            //holiday
            [
                'title' => 'Holidays',
                'description' => 'Permission of Add, Remove, Update, Delete Holidays',
                'key' => 'hrm.settings.holidays',
                'parent_key' => 'hrm.settings',
            ],

            //hotspots
            [
                'title' => 'Hotspots',
                'description' => 'Permission of Add, Remove, Update, Delete Hotspots',
                'key' => 'hrm.settings.hotspots',
                'parent_key' => 'hrm.settings',
            ],
            //appraisal policies
            [
                'title' => 'Appraisal Policies',
                'description' => 'Permission of Add, Remove, Update, Delete Appraisal Policies',
                'key' => 'hrm.settings.appraisal-policies',
                'parent_key' => 'hrm.settings',
            ],
            //expense types
            [
                'title' => 'Expense Types',
                'description' => 'Permission of Add, Remove, Update, Delete Expense Types',
                'key' => 'hrm.settings.expense-types',
                'parent_key' => 'hrm.settings',
            ],
            //transport types
            [
                'title' => 'Transport Types',
                'description' => 'Permission of Add, Remove, Update, Delete Transport Types',
                'key' => 'hrm.settings.transport-types',
                'parent_key' => 'hrm.settings',
            ],
            
            //Import
            [
                'title' => 'Import',
                'description' => 'Permission of Add, Remove, Update, Delete Import',
                'key' => 'import',
            ],
            //purchase orders
            [
                'title' => 'Orders',
                'description' => 'Permission of Add, Remove, Update, Delete Purchase Orders',
                'key' => 'import.orders',
                'parent_key' => 'import',
            ],

            //purchase
            [
                'title' => 'Purchase',
                'description' => 'Permission of Add, Remove, Update, Delete Purchase',
                'key' => 'purchase',
            ],
            //purchase requisitions
            [
                'title' => 'Requisition',
                'description' => 'Permission of Add, Remove, Update, Delete Requisition',
                'key' => 'purchase.requisitions',
                'parent_key' => 'purchase',
            ],

            //purchase orders
            [
                'title' => 'Orders',
                'description' => 'Permission of Add, Remove, Update, Delete Orders',
                'key' => 'purchase.orders',
                'parent_key' => 'purchase',
            ],
            //Purchase Returns
            [
                'title' => 'Purchase Returns',
                'description' => 'Permission of Add, Remove, Update, Delete Purchase Returns',
                'key' => 'purchase.returns',
                'parent_key' => 'purchase',
            ],

            //offices purchase
            [
                'title' => 'Offices',
                'description' => 'Permission of Add, Remove, Update, Delete Offices',
                'key' => 'purchase.offices',
                'parent_key' => 'purchase',
            ],

            //suppliers
            [
                'title' => 'Suppliers',
                'description' => 'Permission of Add, Remove, Update, Delete Suppliers',
                'key' => 'purchase.suppliers',
                'parent_key' => 'purchase',
            ],

            //vendors
            [
                'title' => 'Vendors',
                'description' => 'Permission of Add, Remove, Update, Delete Vendors',
                'key' => 'purchase.vendors',
                'parent_key' => 'purchase',
            ],

            //Reports
            [
                'title' => 'Reports',
                'description' => 'Permission of Add, Remove, Update, Delete Reports',
                'key' => 'purchase.reports',
                'parent_key' => 'purchase',
            ],

            
            //sales
            [
                'title' => 'Sales',
                'description' => 'Permission of Add, Remove, Update, Delete Sales',
                'key' => 'sales',
            ],

            //sales orders
            [
                'title' => 'Sales Orders',
                'description' => 'Permission of Add, Remove, Update, Delete Sales Orders',
                'key' => 'sales.sales-orders',
                'parent_key' => 'sales',
            ],


            //sales order deliveries
            // [
            //     'title' => 'Sales Order Deliveries',
            //     'description' => 'Permission of Add, Remove, Update, Delete Sales Order Deliveries',
            //     'key' => 'sales.sales-order-deliveries',
            //     'parent_key' => 'sales',
            // ],

            //sales deliveries
            [
                'title' => 'Deliveries',
                'description' => 'Permission of Add, Remove, Update, Delete Deliveries',
                'key' => 'sales.deliveries',
                'parent_key' => 'sales',
            ],


            //sales.shipments
            [
                'title' => 'Shipment Verifies',
                'description' => 'Permission of Add, Remove, Update, Delete Shipments',
                'key' => 'sales.shipment-verifies',
                'parent_key' => 'sales',
            ],


            //sales.condition-amount-collects
            [
                'title' => 'Condition Amount Collects',
                'description' => 'Permission of Add, Remove, Update, Delete Condition Amount Collects',
                'key' => 'sales.condition-amount-collects',
                'parent_key' => 'sales',
            ],

            //sales.condition-approval
            [
                'title' => 'Condition Approval',
                'description' => 'Permission of Add, Remove, Update, Delete Condition Amount Collects',
                'key' => 'sales.condition-amount-collects',
                'parent_key' => 'sales',
            ],

            //Fake Invoices
            [
                'title' => 'Fake Invoices',
                'description' => 'Permission of Add, Remove, Update, Delete Fake Invoices',
                'key' => 'sales.fake-invoices',
                'parent_key' => 'sales',
            ],

            //sales requisitions
            [
                'title' => 'Sales Requisitions',
                'description' => 'Permission of Add, Remove, Update, Delete Sales Requisitions',
                'key' => 'sales.sales-requisitions',
                'parent_key' => 'sales',
            ],


            //sales order returns
            [
                'title' => 'Sales Returns',
                'description' => 'Permission of Add, Remove, Update, Delete Sales Returns',
                'key' => 'sales.sales-returns',
                'parent_key' => 'sales',

            ],
 

            //sales commissions
            [
                'title' => 'Sales Commissions',
                'description' => 'Permission of Add, Remove, Update, Delete Sales Commissions',
                'key' => 'sales.sales-commissions',
                'parent_key' => 'sales',
            ],

           

            //backup challans
            [
                'title' => 'Backup Challans',
                'description' => 'Permission of Add, Remove, Update, Delete Backup Challans',
                'key' => 'sales.backup-challans',
                'parent_key' => 'sales',
            ],

            //quotations
            [
                'title' => 'Quotations',
                'description' => 'Permission of Add, Remove, Update, Delete Quotations',
                'key' => 'sales.quotations',
                'parent_key' => 'sales',
            ],


            //couriers
            [
                'title' => 'Setting Couriers',
                'description' => 'Permission of Add, Remove, Update, Delete Couriers',
                'key' => 'sales.couriers',
                'parent_key' => 'sales',
            ],
            //Reports
            [
                'title' => 'Reports',
                'description' => 'Permission of Add, Remove, Update, Delete Reports',
                'key' => 'sales.reports',
                'parent_key' => 'sales',
            ],
 
            

            // Sales Target
            [
                'title' => 'Sales Target',
                'description' => "Permission of Add, Remove, Update, Delete Sales Target",
                'key' => 'sales_target',
            ],

            //Sales Target List
            [
                'title' => 'Sales Target Setup',
                'description' => "Permission of Add, Remove, Update, Delete Sales Target List",
                'key' => 'sales_target.sales-targets',
                'parent_key' => 'sales_target',
            ],

            //Sales Target Slabs
            [
                'title' => 'Sales Target Slabs',
                'description' => "Permission of Add, Remove, Update, Delete Sales Target Slabs List",
                'key' => 'sales_target.sales-target-slabs',
                'parent_key' => 'sales_target',
            ],

            //Sales Incentive Slabs 
            [
                'title' => 'Sales Incentive Slabs',
                'description' => "Permission of Add, Remove, Update, Delete Sales Incentive Slabs List",
                'key' => 'sales_target.sales-incentive-slabs',
                'parent_key' => 'sales_target',
            ],

            //Performance Base Salary Slab
            [
                'title' => 'Performance Base Salary',
                'description' => "Permission of Add, Remove, Update, Delete Performance Base Salary List",
                'key' => 'sales_target.sales-salary-brackets',
                'parent_key' => 'sales_target',
            ],

            //inv
            [
                'title' => 'Inventory',
                'description' => 'Permission of Add, Remove, Update, Delete Inventory',
                'key' => 'inv',
            ],

            //product-catalogs
            [
                'title' => 'Product Inventory',
                'description' => 'Permission of Add, Remove, Update, Delete Product Catalogs',
                'key' => 'inv.product-catalogs',
                'parent_key' => 'inv',
            ],

            //product-transfer-requests
            [
                'title' => 'Product Transfers Requests',
                'description' => 'Permission of Add, Remove, Update, Delete Product Transfers Requests',
                'key' => 'inv.product-transfer-requests',
                'parent_key' => 'inv',
            ],

            //product-transfers
            [
                'title' => 'Product Transfers',
                'description' => 'Permission of Add, Remove, Update, Delete Product Transfers',
                'key' => 'inv.product-transfers',
                'parent_key' => 'inv',
            ],

            //approvers
            [
                'title' => 'Approvers',
                'description' => 'Permission of Add, Remove, Update, Delete Approvers',
                'key' => 'inv.approvers',
                'parent_key' => 'inv',
            ],



            //product-transfers receive
            [
                'title' => 'Product Transfer Receive',
                'description' => 'Permission of Add, Remove, Update, Delete Product Transfer Receive',
                'key' => 'inv.product-transfers-receives',
                'parent_key' => 'inv',
            ],

             //gift/offers
            [
                'title' => 'Gift/Offers',
                'description' => 'Permission of Add, Remove, Update, Delete Offers Requests',
                'key' => 'inv.offers',
                'parent_key' => 'inv',
            ],
           

             //products price list
            [
                'title' => 'Products Price',
                'description' => 'Permission of Add, Remove, Update, Delete Products',
                'key' => 'inv.products',
                'parent_key' => 'inv',
            ],

            
            
            //stocks
            [
                'title' => 'Stocks',
                'description' => 'Permission of Add, Remove, Update, Delete Stocks',
                'key' => 'inv.stocks',
                'parent_key' => 'inv',
            ],


            //inv settings
            [
                'title' => 'Inventory Settings',
                'description' => 'Permission of Add, Remove, Update, Delete Inventory Settings',
                'key' => 'inv.settings',
                'parent_key' => 'inv',
            ],


            //product units
            [
                'title' => 'Product Units',
                'description' => 'Permission of Add, Remove, Update, Delete Product Units',
                'key' => 'inv.settings.units',
                'parent_key' => 'inv',
            ],

            
            //product-types
            [
                'title' => 'Product Types',
                'description' => 'Permission of Add, Remove, Update, Delete Product Types',
                'key' => 'inv.settings.product-types',
                'parent_key' => 'inv',
            ],
            
                      
            //tags
            [
                'title' => 'Tags',
                'description' => 'Permission of Add, Remove, Update, Delete Tags',
                'key' => 'inv.settings.tags',
                'parent_key' => 'inv',
            ],
            //brands
            [
                'title' => 'Brands',
                'description' => 'Permission of Add, Remove, Update, Delete Brands',
                'key' => 'inv.settings.brands',
                'parent_key' => 'inv',
            ],

            //Reports
            [
                'title' => 'Reports',
                'description' => 'Permission of Add, Remove, Update, Delete Reports',
                'key' => 'inv.reports',
                'parent_key' => 'inv',
            ],
            
             


            //location-manager
            [
                'title' => 'Location Manager',
                'description' => 'Permission of Add, Remove, Update, Delete Location Manager',
                'key' => 'location_manager',
            ],

            //divisions
            [
                'title' => 'Divisions',
                'description' => 'Permission of Add, Remove, Update, Delete Divisions',
                'key' => 'location_manager.divisions',
                'parent_key' => 'location_manager',
            ],
            //districts
            [
                'title' => 'Districts',
                'description' => 'Permission of Add, Remove, Update, Delete Districts',
                'key' => 'location_manager.districts',
                'parent_key' => 'location_manager',
            ],
            //thanas
            [
                'title' => 'Thanas',
                'description' => 'Permission of Add, Remove, Update, Delete Thanas',
                'key' => 'location_manager.thanas',
                'parent_key' => 'location_manager',
            ],

            //areas
            [
                'title' => 'Areas',
                'description' => 'Permission of Add, Remove, Update, Delete Areas',
                'key' => 'location_manager.areas',
                'parent_key' => 'location_manager',
            ],

            //location-types
            [
                'title' => 'Location Types',
                'description' => 'Permission of Add, Remove, Update, Delete Location Types',
                'key' => 'location_manager.location-types',
                'parent_key' => 'location_manager',
            ],

            // Licenses
            [
                'title' => 'Licenses',
                'description' => "Licenses of Add, Remove, Update, Delete Hrm & Payroll",
                'key' => 'licenses',
            ],
            //Dongle Or Serial Entries
            [
                'title' => 'Dongle Or Serial Entries',
                'description' => "Dongle Or Serial Entries of Add, Remove, Update, Delete Employee",
                'key' => 'licenses.dongle-or-serial-entries',
                'parent_key' => 'licenses',
            ],

            //usg-opg-license-requisitions
            [
                'title' => 'USG OPG License Requisitions',
                'description' => "USG OPG License Requisitions of Add, Remove, Update, Delete Employee",
                'key' => 'licenses.usg-opg-license-requisitions',
                'parent_key' => 'licenses',
            ],

            //cbc-license-requisitions
            [
                'title' => 'CBC License Requisitions',
                'description' => "CBC License Requisitions of Add, Remove, Update, Delete",
                'key' => 'licenses.cbc-license-requisitions',
                'parent_key' => 'licenses',
            ],
            //usg-opg-sms
            [
                'title' => 'USG OPG SMS',
                'description' => "USG OPG SMS of Add, Remove, Update, Delete",
                'key' => 'licenses.usg-opg-sms',
                'parent_key' => 'licenses',
            ],
            //cbc-sms
            [
                'title' => 'CBC SMS',
                'description' => "CBC SMS of Add, Remove, Update, Delete",
                'key' => 'licenses.cbc-sms',
                'parent_key' => 'licenses',
            ],
            //report
            [
                'title' => 'Report',
                'description' => "Report of Add, Remove, Update, Delete",
                'key' => 'licenses.reports',
                'parent_key' => 'licenses',
            ],


            //services
            [
                'title' => 'Services',
                'description' => "Services of Add, Remove, Update, Delete",
                'key' => 'services',
            ],

            //service
            [
                'title' => 'Service',
                'description' => "Service of Add, Remove, Update, Delete",
                'key' => 'services.service',
                'parent_key' => 'services',
            ],
            //service assign
            [
                'title' => 'Service Assign',
                'description' => "Service of Add, Remove, Update, Delete",
                'key' => 'services.service-assign',
                'parent_key' => 'services',
            ],

            //service my task
            [
                'title' => 'My Task',
                'description' => "Service of Add, Remove, Update, Delete",
                'key' => 'services.service-my-task',
                'parent_key' => 'services',
            ],

            //service settings
            [
                'title' => 'Service Settings',
                'description' => "Service Settings of Add, Remove, Update, Delete",
                'key' => 'services.settings',
                'parent_key' => 'services',
            ],

            [
                'title' => 'Service quotations',
                'description' => "Service quotations of Add, Remove, Update, Delete",
                'key' => 'services.quotations',
                'parent_key' => 'services',
            ],
            [
                'title' => 'Service Document Entries',
                'description' => "Service Document Entries of Add, Remove, Update, Delete",
                'key' => 'services.document-entries',
                'parent_key' => 'services',
            ],

            //service types
            [
                'title' => 'Service Types',
                'description' => "Service Types of Add, Remove, Update, Delete",
                'key' => 'services.settings.service-types',
                'parent_key' => 'services.settings',
            ],
            //service reports
            [
                'title' => 'Service Reports',
                'description' => "Service Reports view permission",
                'key' => 'services.reports',
                'parent_key' => 'services',
            ],


             //Legal
            [
                'title' => 'Legal',
                'description' => "Legal of Add, Remove, Update, Delete",
                'key' => 'legal',
            ],
            [
                'title' => 'Legal Entries',
                'description' => "Permission of Add, Remove, Update, Delete Legal Entries",
                'key' => 'legal.legal-entries',
                'parent_key' => 'legal',
            ],
            [
                'title' => 'Legal Bill Entries',
                'description' => "Permission of Add, Remove, Update, Delete Legal Bill Entries",
                'key' => 'legal.legal-bill-entries',
                'parent_key' => 'legal',
            ],

 
            //Accounts
            [
                'title' => 'Accounts',
                'description' => 'Permission of Add, Remove, Update, Delete Accounts',
                'key' => 'accounts',
            ],

            //Account Setup
            //Account Setup
            [
                'title' => 'Account Setup',
                'description' => 'Permission of Add, Remove, Update, Delete Account Setup',
                'key' => 'account.account-setup',
                'parent_key' => 'accounts',
            ],
            //Account groups
            [
                'title' => 'Account Groups',
                'description' => 'Permission of Add, Remove, Update, Delete Account Groups',
                'key' => 'account.account-setup.account-groups',
                'parent_key' => 'account.account-setup',
            ],

            //Account controls
            [
                'title' => 'Account Controls',
                'description' => 'Permission of Add, Remove, Update, Delete Account Controls',
                'key' => 'account.account-setup.account-controls',
                'parent_key' => 'account.account-setup',
            ],

            //Account subsidiaries
            [
                'title' => 'Account Subsidiaries',
                'description' => 'Permission of Add, Remove, Update, Delete Account Subsidiaries',
                'key' => 'account.account-setup.account-subsidiaries',
                'parent_key' => 'account.account-setup',
            ],

            //Accounts
            [
                'title' => 'Accounts',
                'description' => 'Permission of Add, Remove, Update, Delete Accounts',
                'key' => 'account.account-setup.accounts',
                'parent_key' => 'account.account-setup',
            ],
            //Account Opening Balances
            [
                'title' => 'Account Opening Balances',
                'description' => 'Permission of Add, Remove, Update, Delete Account Opening Balances',
                'key' => 'account.account-setup.account-opening-balances',
                'parent_key' => 'account.account-setup',
            ],
            //Bank Accounts
            [
                'title' => 'Bank Accounts',
                'description' => 'Permission of Add, Remove, Update, Delete Bank Accounts',
                'key' => 'account.account-setup.bank-accounts',
                'parent_key' => 'account.account-setup',
            ],
            //Bank Branches
            [
                'title' => 'Bank Branches',
                'description' => 'Permission of Add, Remove, Update, Delete Bank Branches',
                'key' => 'account.account-setup.bank-branches',
                'parent_key' => 'account.account-setup',
            ],
            //Banks
            [
                'title' => 'Banks',
                'description' => 'Permission of Add, Remove, Update, Delete Banks',
                'key' => 'account.account-setup.banks',
                'parent_key' => 'account.account-setup',
            ],
            [
                'title' => 'Cheque Verifications',
                'description' => 'Permission of Approve or Deny Cheque Verifications',
                'key' => 'account.cheque-verifications',
                'parent_key' => 'accounts',
            ],

            [
                'title' => 'Online Deposit Verifications',
                'description' => 'Permission of Approve or Deny Online Deposit Verifications',
                'key' => 'account.online-deposit-verifications',
                'parent_key' => 'accounts',
            ],

            [
                'title' => 'Mfs Verifications',
                'description' => 'Permission of Approve or Deny Mfs Verifications',
                'key' => 'account.mfs-verifications',
                'parent_key' => 'accounts',
            ],

            [
                'title' => 'EMI Entries',
                'description' => 'Permission of Add, Remove, Update, Delete EMI Entries',
                'key' => 'account.emi-entries',
                'parent_key' => 'accounts',
            ],
            [
                'title' => 'EMI Reports',
                'description' => 'Permission of Add, Remove, Update, Delete EMI Reports',
                'key' => 'account.emi-reports',
                'parent_key' => 'accounts',
            ],
            [
                'title' => 'Advance Cheque Entries',
                'description' => 'Permission of Add, Remove, Update, Delete Advance Cheque Entries',
                'key' => 'account.advance-cheque-entries',
                'parent_key' => 'accounts',
            ],


            //Collections
            [
                'title' => 'Collections',
                'description' => 'Permission of Add, Remove, Update, Delete Collections',
                'key' => 'account.collections',
                'parent_key' => 'accounts',
            ],
            //Collections

            [
                'title' => 'Collections',
                'description' => 'Permission of Add, Remove, Update, Delete Collections',
                'key' => 'account.collections.collections',
                'parent_key' => 'account.collections',
            ],
            //Invoice-wise Collections
            [
                'title' => 'Invoice-wise Collections',
                'description' => 'Permission of Add, Remove, Update, Delete Invoice-wise Collections',
                'key' => 'account.collections.invoice-wise-collections',
                'parent_key' => 'account.collections',
            ],

            //Loan Collections
            [
                'title' => 'Loan Collections',
                'description' => 'Permission of Add, Remove, Update, Delete Loan Collections',
                'key' => 'account.collections.loan-collections',
                'parent_key' => 'account.collections',
            ],

            //Default Payable & Receivables
            // [
            //     'title' => 'Default Payable & Receivables',
            //     'description' => 'Permission of Add, Remove, Update, Delete Default Payable & Receivables',
            //     'key' => 'account.account-settings.default-payable-receivables',
            //     'parent_key' => 'accounts',
            // ],

            //Payments
            [
                'title' => 'Payments',
                'description' => 'Permission of Add, Remove, Update, Delete Payments',
                'key' => 'account.payments',
                'parent_key' => 'accounts',
            ],

            //Customer Payments
            [
                'title' => 'Make Payments',
                'description' => 'Permission of Add, Remove, Update, Delete Make Payments',
                'key' => 'account.payments.make-payments',
                'parent_key' => 'account.payments',
            ],
            //invoice-wise-payments
            [
                'title' => 'Invoice-wise Payments',
                'description' => 'Permission of Add, Remove, Update, Delete Invoice-wise Payments',
                'key' => 'account.payments.invoice-wise-payments',
                'parent_key' => 'account.payments',
            ],


            //Broker Payments
            [
                'title' => 'Broker Payments',
                'description' => 'Permission of Add, Remove, Update, Delete Broker Payments',
                'key' => 'account.payments.broker-payments',
                'parent_key' => 'account.payments',
            ],

            //TA/DA Payments
            [
                'title' => 'TA/DA Payments',
                'description' => 'Permission of Add, Remove, Update, Delete TA/DA Payments',
                'key' => 'account.payments.petty-cash-payments',
                'parent_key' => 'account.payments',
            ],

            //Loan Payments
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

            //Sms Templates
            [
                'title' => 'SMS Templates',
                'description' => 'Permission of Add, Remove, Update, Delete SMS Templates',
                'key' => 'access_control.sms.templates',
                'parent_key' => 'access_control',
            ],

            //Sms Services
            [
                'title' => 'SMS Services',
                'description' => 'Permission of Add, Remove, Update, Delete SMS Services',
                'key' => 'access_control.sms.service_names',
                'parent_key' => 'access_control',
            ],

            //Sms Trigger 
            [
                'title' => 'SMS Trigger',
                'description' => 'Permission of Add, Remove, Update, Delete SMS Trigger',
                'key' => 'access_control.sms.trigger_names',
                'parent_key' => 'access_control',
            ],

            //branchs
            [
                'title' => 'Branches',
                'description' => 'Permission of Add, Remove, Update, Delete Branches',
                'key' => 'access_control.branchs',
                'parent_key' => 'access_control',
            ],
            //Branch Types
            [
                'title' => 'Branch Types',
                'description' => 'Permission of Add, Remove, Update, Delete Branch Types',
                'key' => 'access_control.branch-types',
                'parent_key' => 'access_control',
            ],
            [
                'title' => 'Global Setting',
                'description' => "Permission of Add, Remove, Update, Delete Global Setting",
                'key' => 'access_control.global-settings',
                'parent_key' => 'access_control',
            ],
            

             //Employee Leave Application
            [
                'title' => 'Employee Leave Application',
                'description' => "Permission of Add, Remove, Update, Delete Employee Leave Application",
                'key' => 'hrm.leave-application-employees',
                'parent_key' => 'hrm',
            ],

 
            //Document Report
            [
                'title' => 'Document Report',
                'description' => "Permission of Add, Remove, Update, Delete Document Report",
                'key' => 'cms.document-entries',
                'parent_key' => 'cms',
            ],


            //payment verifications
            [
                'title' => 'Payments Verifications',
                'description' => 'Permission of Add, Remove, Update, Delete Loan Payments Verifications',
                'key' => 'account.payments.payment-verifications',
                'parent_key' => 'account.payments',
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
                PermissionMaster::updateOrCreate(
                    ['key' => $value['key']],
                    [
                        'title' => $value['title'],
                        'description' => $value['description'],
                        'key' => $value['key'],
                        'parent_id' => $parent->id
                    ]
                );
            } else {
                PermissionMaster::updateOrCreate(
                    ['key' => $value['key']],
                    [
                        'title' => $value['title'],
                        'description' => $value['description'],
                        'key' => $value['key'],
                        'parent_id' => null
                    ]
                );
            }
        }
    }
}
