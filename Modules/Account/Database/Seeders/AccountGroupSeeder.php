<?php
namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountControl;
use Modules\Account\Models\AccountGroup;
use Modules\Account\Models\AccountSubsidiary;

class AccountGroupSeeder extends Seeder
{
    public function run()
    {


        $accountGroup = [
            [
                'id' => 1,
                'name' => 'Assets',
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2,
                'name' => 'Liabilities',
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 3,
                'name' => 'Equity',
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4,
                'name' => 'Income',
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5,
                'name' => 'Expenses',
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
        ];

        foreach ($accountGroup as $group) {
            AccountGroup::updateOrCreate(
                ['id' => $group['id']],
                $group
            );
        }

        /**
         ACCOUNT_CATEGORY = [
            # ASSET -> CURRENT_ASSET
            ('CASH', 'Cash'),
            ('BANK', 'Bank'),
            ('PAYMENT_CLEARING', 'Payment Clearing'),
            ('INPUT_TAX', 'Input Tax'),
            ('ACCOUNTS_RECEIVABLE', 'Accounts Receivable'),
            ('VENDOR_ADVANCE', 'Vendor Advance'),
            # ASSET -> FIXED_ASSET
            ('STOCK', 'Stock'),
            ('INTANGIBLE_ASSETS', 'Intangible Assets'),
            ('OTHER_ASSET', 'Other Asset'),
            # LIABILITY -> CURRENT_LIABILITY
            ('ACCOUNTS_PAYABLE', 'Accounts Payable'),
            ('ACCRUED_LIABILITIES', 'Accrued Liabilities'),
            ('CUSTOMER_ADVANCE', 'Customer Advance'),
            ('SHIPPING_ADVANCE', 'Shipping Advance'),
            ('UNEARNED_REVENUE', 'Unearned Revenue'),
            # LIABILITY -> LONG_TERM_LIABILITY
            ('CREDIT_CARD', 'Credit Card'),
            ('TAX_PAYABLE', 'Tax Payable'),
            ('OVERSEAS_TAX_PAYABLE', 'Overseas Tax Payable'),
            ('OTHER_LIABILITY', 'Other Liability'),
            # EQUITY -> OWNER_EQUITY
            ('RETAINED_EARNINGS', 'Retained Earnings'),
            ('COMMON_STOCK', 'Common Stock'),
            ('DIVIDENDS', 'Dividends'),
            ('OTHER_EQUITY', 'Other Equity'),
            # INCOME -> SALES_INCOME
            ('SALES_REVENUE', 'Sales Revenue'),
            # INCOME -> SERVICE_INCOME
            ('SERVICE_REVENUE', 'Service Revenue'),
            # INCOME -> OTHER_INCOME
            ('INTEREST_INCOME', 'Interest Income'),
            ('RENTAL_INCOME', 'Rental Income'),
            ('ADJUSTMENT_INCOME', 'Adjustment Income'),
            ('OTHER_INCOME_SOURCE', 'Other Income Source'),
            # EXPENSE -> GENERAL_EXPENSE
            ('OFFICE_SUPPLIES', 'Office Supplies'),
            ('UTILITIES', 'Utilities'),
            ('INTERNET_BILL', 'Internet Bill'),
            ('T_T_AND_MOBILE_BILL', 'T&T and Mobile Bill'),
            ('PRINTING_STATIONERY', 'Printing & Stationery'),
            ('BANK_CHARGES', 'Bank Charges'),
            ('ADJUSTMENT_EXPENSE', 'Adjustment Expense'),
            ('OTHER_EXPENSES', 'Other Expenses'),
            # EXPENSE -> COST_OF_GOODS_SOLD
            ('DIRECT_EXPENSE', 'Direct Expense'),
            ('INDIRECT_EXPENSE', 'Indirect Expense'),
            ('PACKING_MATERIALS', 'Packing Materials'),
            ('CLEANING_MATERIALS', 'Cleaning Materials'),
            # EXPENSE -> PAYROLL_EXPENSE
            ('SALARIES_WAGES', 'Salaries and Wages'),
            ('BENEFITS', 'Employee Benefits (Medical, Welfare)'),
            ('ALLOWANCES', 'Allowances'),
            ('BONUSES', 'Bonuses (Eid Bonus, Tips)'),
            ('STAFF_MEALS', 'Staff Meals'),
            ('STAFF_WELFARE', 'Staff Welfare'),
            ('INCENTIVES', 'Incentives'),
            ('CONVEYANCE', 'Conveyance'),
            # EXPENSE -> DEPRECIATION
            ('ACCUMULATED_DEPRECIATION', 'Accumulated Depreciation'),
            # EXPENSE -> OPERATIONAL_EXPENSE
            ('RENT', 'Rent'),
            ('REPAIR_MAINTENANCE', 'Repair & Maintenance'),
            ('SOFTWARE_MAINTENANCE', 'Software Maintenance'),
            ('INSURANCE_PREMIUM', 'Insurance Premium'),
            ('SECURITY_CHARGES', 'Security Charges'),
            ('PEST_CONTROL', 'Pest Control'),
            ('PROMOTIONAL_EXPENSES', 'Promotional Expenses'),
            ('MARKETING_EXPENSES', 'Marketing Expenses'),
            ('TOUR_TRAVELS', 'Tour & Travels'),
            ('MEETING_EXPENSES', 'Meeting Expenses'),
            ('ROYALTY_FEE', 'Royalty Fee'),
            ('DELIVERY_EXPENSE', 'Delivery Expense'),
            ('TRAINING_EXPENSES', 'Training Expenses'),
            ('GARDENING_EXPENSE', 'Gardening Expense'),
            ('CHANGE_COMMISSION', 'Change Commission'),
            ('LUNCH_FOR_TRAINER', 'Lunch for Trainer'),
            ('COMPLEMENTARY_BILL', 'Complementary Bill'),
            ('OTHERS', 'Others'),
        ]
         */

        $accountControl = [
            [
                'id' => 1000,
                'name' => 'Current Assets',
                'account_group_id' => 1,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1050,
                'name' => 'Fixed Assets',
                'account_group_id' => 1,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1070,
                'name' => 'Investments',
                'account_group_id' => 1,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1090,
                'name' => 'Misc. Expenses(Assets)',
                'account_group_id' => 1,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            //Liabilities
            [
                'id' => 2000,
                'name' => 'Current Liabilities',
                'account_group_id' => 2,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2010,
                'name' => 'Long Term Liabilities',
                'account_group_id' => 2,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2020,
                'name' => 'Capital Account',
                'account_group_id' => 2,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2030,
                'name' => 'Loans Liabilities',
                'account_group_id' => 2,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            // Equity
            [
                'id' => 3000,
                'name' => 'Owner Equity',
                'account_group_id' => 3,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            // Income
            [
                'id' => 4000,
                'name' => 'Sales Income',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4010,
                'name' => 'Service Income',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4020,
                'name' => 'Other Income',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4030,
                'name' => 'Direct Income',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4040,
                'name' => 'Indirect Income',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4050,
                'name' => 'Sales Accounts',
                'account_group_id' => 4,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],

            // Expense
            [
                'id' => 5000,
                'name' => 'General Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5010,
                'name' => 'Cost of Goods Sold',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5020,
                'name' => 'Payroll Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5030,
                'name' => 'Depreciation',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5040,
                'name' => 'Administration Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5050,
                'name' => 'Direct Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5060,
                'name' => 'Financial Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5070,
                'name' => 'Indirect Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5080,
                'name' => 'Selling & Distribution Expense',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5090,
                'name' => 'Service Overhead',
                'account_group_id' => 5,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ]
        ];

        foreach ($accountControl as $key => $value) {
            AccountControl::updateOrCreate(
                ['id' => $value['id']],
                $value
            );
        }

        // AccountControl::updateOrInsert($accountControl);

        $accountSubsidiarys = [
            //Current Asset
            [
                'id' => 1001,
                'name' => 'Cash',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1002,
                'name' => 'Bank Accounts',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1003,
                'name' => 'Payment Clearing',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1004,
                'name' => 'Input Tax',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            [
                'id' => 1005,
                'name' => 'Accounts Receivable',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1006,
                'name' => 'Vendors Advances',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1007,
                'name' => 'Inventory',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 1008,
                'name' => 'Advance to Employee',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1009,
                'name' => 'Advance to Others',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1010,
                'name' => 'Bank Gurantee',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],

            [
                'id' => 1011,
                'name' => 'Security Deposit for Tender',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1012,
                'name' => 'Loan to Employee',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1013,
                'name' => 'Inventories (L/C)',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1014,
                'name' => 'Deposits (Asset)',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1015,
                'name' => 'Loans & Advances (Asset)',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1016,
                'name' => 'Stock-in-Hand',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1017,
                'name' => 'Stock In Transit',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1018,
                'name' => 'L/C',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1019,
                'name' => 'Mobile Wallet',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1020,
                'name' => 'Card Gateway',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1021,
                'name' => 'Staff Loan',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1022,
                'name' => 'Staff IOU',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1023,
                'name' => 'Tax Receivable',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],


            //Fixed Asset
            [
                'id' => 1051,
                'name' => 'Stock',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1052,
                'name' => 'Intangible Assets',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 1053,
                'name' => 'Other Assets',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //current Liabilities
            [
                'id' => 2001,
                'name' => 'Accounts Payable',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2002,
                'name' => 'Accrued Expense',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2003,
                'name' => 'Customer Advances',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2004,
                'name' => 'Shipping Advance',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2005,
                'name' => 'Unearned Advance',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2006,
                'name' => 'Provisions',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2007,
                'name' => 'Expenses Payable',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2009,
                'name' => 'Employee Cash',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2015,
                'name' => 'Petty Cash Payable',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //Long Term Liabilities
            [
                'id' => 2011,
                'name' => 'Credit Card',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2012,
                'name' => 'Tax Payable',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2013,
                'name' => 'Overseas Tax Payable',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2014,
                'name' => 'Other Liability',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            //Capital Account(liabilities)
            [
                'id' => 2021,
                'name' => 'Rserve and Surplus',
                'account_group_id' => 2,
                'account_control_id' => 2020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            //Loan liabilities
            [
                'id' => 2031,
                'name' => 'Bank OD A/c',
                'account_group_id' => 2,
                'account_control_id' => 2030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 2032,
                'name' => 'Loan from Others',
                'account_group_id' => 2,
                'account_control_id' => 2030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 2033,
                'name' => 'Secured Loans',
                'account_group_id' => 2,
                'account_control_id' => 2030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => 2034,
                'name' => 'Unsecured Loans',
                'account_group_id' => 2,
                'account_control_id' => 2030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],

            //Owner Equity
            [
                'id' => 3001,
                'name' => 'Retained Earnings',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 3002,
                'name' => 'Common Stock',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 3003,
                'name' => 'Dividends',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 3004,
                'name' => 'Other Equity',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            //Sales Income
            [
                'id' => 4001,
                'name' => 'Sales Income',
                'account_group_id' => 4,
                'account_control_id' => 4000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],


            //Sales Discounts
            [
                'id' => 4002,
                'name' => 'Sales Discounts',
                'account_group_id' => 4,
                'account_control_id' => 4000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            //Sales Returns & Allowances
            [
                'id' => 4003,
                'name' => 'Sales Returns & Allowances',
                'account_group_id' => 4,
                'account_control_id' => 4000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //Service Income
            [
                'id' => 4011,
                'name' => 'Service Revenue',
                'account_group_id' => 4,
                'account_control_id' => 4010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //OTHER_INCOME
            [
                'id' => 4021,
                'name' => 'Interest Income',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4022,
                'name' => 'Rental Income',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4023,
                'name' => 'Other Income Source',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4024,
                'name' => 'Adjustment Income',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //DIRECT_INCOME
            [
                'id' => 4031,
                'name' => 'Other direct Income',
                'account_group_id' => 4,
                'account_control_id' => 4030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //INDIRECT_INCOME
            [
                'id' => 4041,
                'name' => 'Other indirect Income',
                'account_group_id' => 4,
                'account_control_id' => 4040,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4042,
                'name' => 'Discount',
                'account_group_id' => 4,
                'account_control_id' => 4040,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            // Sales Accounts
            [
                'id' => 4051,
                'name' => 'Sales(Faith)',
                'account_group_id' => 4,
                'account_control_id' => 4050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4052,
                'name' => 'Sales(Kanghui)',
                'account_group_id' => 4,
                'account_control_id' => 4050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 4053,
                'name' => 'Sales(Medtronic)',
                'account_group_id' => 4,
                'account_control_id' => 4050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],

            // GENERAL EXPENSE
            [
                'id' => 5001,
                'name' => 'OFFICE_SUPPLIES',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5002,
                'name' => 'UTILITIES',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5003,
                'name' => 'OTHER_EXPENSES',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5004,
                'name' => 'Adjustment Expense',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5005,
                'name' => 'Bank Charges',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],



            //'COST OF GOODS SOLD'
            [
                'id' => 5011,
                'name' => 'DIRECT EXPENSE',
                'account_group_id' => 5,
                'account_control_id' => 5010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5010,
                'name' => 'INDIRECT EXPENSE',
                'account_group_id' => 5,
                'account_control_id' => 5010,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            //Payroll Expense
            [
                'id' => 5021,
                'name' => 'SALARIES_WAGES',
                'account_group_id' => 5,
                'account_control_id' => 5020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5022,
                'name' => 'BENEFITS',
                'account_group_id' => 5,
                'account_control_id' => 5020,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 5023,
                'name' => ' Commission Expense ',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],

            //Depreciation
            [
                'id' => 5031,
                'name' => 'ACCUMULATED DEPRECIATION',
                'account_group_id' => 5,
                'account_control_id' => 5030,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            //Administration Expense
            [
                'id' => 5041,
                'name' => 'Salaries and Allowance Expense',
                'account_group_id' => 5,
                'account_control_id' => 5040,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            //Direct Expense
            [
                'id' => 5051,
                'name' => 'Purchase Accounts',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5052,
                'name' => 'Discount Allowed',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5053,
                'name' => 'Others Expense',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5054,
                'name' => 'Operating Expense',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1
            ],
            //Financial Expense
            [
                'id' => 5061,
                'name' => 'Interest on LTR',
                'account_group_id' => 5,
                'account_control_id' => 5060,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5062,
                'name' => 'Interest on OD',
                'account_group_id' => 5,
                'account_control_id' => 5060,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            //Indirect Expense
            [
                'id' => 5071,
                'name' => 'Entertainment',
                'account_group_id' => 5,
                'account_control_id' => 5070,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5072,
                'name' => 'Rent , Rate Taxes',
                'account_group_id' => 5,
                'account_control_id' => 5070,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            //Selling & Distribution Expense
            [
                'id' => 5081,
                'name' => 'Distribution Expenses',
                'account_group_id' => 5,
                'account_control_id' => 5080,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            [
                'id' => 5082,
                'name' => 'Marketing Expenses',
                'account_group_id' => 5,
                'account_control_id' => 5080,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ],
            //Service Overhead
            [
                'id' => 5091,
                'name' => 'Service Overhead',
                'account_group_id' => 5,
                'account_control_id' => 5090,
                'branch_id' => 1,
                'status' => 1,
                'is_deletable' => 0,
                'created_by' => 1,
                'updated_by' => 1

            ]


        ];

        foreach ($accountSubsidiarys as $key => $accountSubsidiary) {

            AccountSubsidiary::updateOrInsert(['id' => $accountSubsidiary['id']], $accountSubsidiary);

        }

        // AccountSubsidiary::updateOrInsert($accountSubsidiary);

        //accounts

        /**
         * 
         * 
         * 
         * 
         * default_accounts = [
            # Assets
            {'name': 'Cash', 'code': '1000', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'CASH', 'is_system_account': True},
            {'name': 'Bank', 'code': '1100', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'BANK', 'is_system_account': True},
            {'name': 'Payment Clearing', 'code': '1200', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'PAYMENT_CLEARING', 'is_system_account': True},
            {'name': 'Input Tax', 'code': '1300', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'INPUT_TAX', 'is_system_account': True},
            {'name': 'Accounts Receivable', 'code': '1400', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'ACCOUNTS_RECEIVABLE', 'is_system_account': True},
            {'name': 'Vendor Advance Payment', 'code': '1800', 'type': 'ASSET', 'subtype': 'CURRENT_ASSET', 'category': 'VENDOR_ADVANCE', 'is_system_account': True},
            {'name': 'Stock', 'code': '1500', 'type': 'ASSET', 'subtype': 'FIXED_ASSET', 'category': 'STOCK', 'is_system_account': True},
            {'name': 'Intangible Assets', 'code': '1600', 'type': 'ASSET', 'subtype': 'FIXED_ASSET', 'category': 'INTANGIBLE_ASSETS', 'is_system_account': True},
            {'name': 'Other Asset', 'code': '1700', 'type': 'ASSET', 'subtype': 'FIXED_ASSET', 'category': 'OTHER_ASSET', 'is_system_account': True},
            # Liabilities
            {'name': 'Accounts Payable', 'code': '2000', 'type': 'LIABILITY', 'subtype': 'CURRENT_LIABILITY', 'category': 'ACCOUNTS_PAYABLE', 'is_system_account': True},
            {'name': 'Accrued Liabilities', 'code': '2100', 'type': 'LIABILITY', 'subtype': 'CURRENT_LIABILITY', 'category': 'ACCRUED_LIABILITIES', 'is_system_account': True},
            {'name': 'Accrued Liabilities', 'code': '2100', 'type': 'LIABILITY', 'subtype': 'CURRENT_LIABILITY', 'category': 'ACCRUED_LIABILITIES', 'is_system_account': True},
            {'name': 'Customer Advance Payment', 'code': '2600', 'type': 'LIABILITY', 'subtype': 'CURRENT_LIABILITY', 'category': 'CUSTOMER_ADVANCE', 'is_system_account': True},
            {'name': 'Credit Card', 'code': '2200', 'type': 'LIABILITY', 'subtype': 'LONG_TERM_LIABILITY', 'category': 'CREDIT_CARD', 'is_system_account': True},
            {'name': 'Tax Payable', 'code': '2300', 'type': 'LIABILITY', 'subtype': 'LONG_TERM_LIABILITY', 'category': 'TAX_PAYABLE', 'is_system_account': True},
            {'name': 'Overseas Tax Payable', 'code': '2400', 'type': 'LIABILITY', 'subtype': 'LONG_TERM_LIABILITY', 'category': 'OVERSEAS_TAX_PAYABLE', 'is_system_account': True},
            {'name': 'Other Liability', 'code': '2500', 'type': 'LIABILITY', 'subtype': 'LONG_TERM_LIABILITY', 'category': 'OTHER_LIABILITY', 'is_system_account': True},
            # Equity
            {'name': 'Retained Earnings', 'code': '3000', 'type': 'EQUITY', 'subtype': 'OWNER_EQUITY', 'category': 'RETAINED_EARNINGS', 'is_system_account': True},
            {'name': 'Common Stock', 'code': '3100', 'type': 'EQUITY', 'subtype': 'OWNER_EQUITY', 'category': 'COMMON_STOCK', 'is_system_account': True},
            {'name': 'Dividends', 'code': '3200', 'type': 'EQUITY', 'subtype': 'OWNER_EQUITY', 'category': 'DIVIDENDS', 'is_system_account': True},
            {'name': 'Other Equity', 'code': '3300', 'type': 'EQUITY', 'subtype': 'OWNER_EQUITY', 'category': 'OTHER_EQUITY', 'is_system_account': True},
            # Income
            {'name': 'Sales Revenue', 'code': '4000', 'type': 'INCOME', 'subtype': 'SALES_AND_SERVICE', 'category': 'SALES_REVENUE', 'is_system_account': True},
            {'name': 'Service Revenue', 'code': '4100', 'type': 'INCOME', 'subtype': 'SALES_AND_SERVICE', 'category': 'SERVICE_REVENUE', 'is_system_account': True},
            {'name': 'Interest Income', 'code': '4200', 'type': 'INCOME', 'subtype': 'OTHER_INCOME', 'category': 'INTEREST_INCOME', 'is_system_account': True},
            {'name': 'Rental Income', 'code': '4300', 'type': 'INCOME', 'subtype': 'OTHER_INCOME', 'category': 'RENTAL_INCOME', 'is_system_account': True},
            {'name': 'Other Income Source', 'code': '4400', 'type': 'INCOME', 'subtype': 'OTHER_INCOME', 'category': 'OTHER_INCOME_SOURCE', 'is_system_account': True},
            # Expenses
            {'name': 'Office Supplies', 'code': '5000', 'type': 'EXPENSE', 'subtype': 'GENERAL_EXPENSE', 'category': 'OFFICE_SUPPLIES', 'is_system_account': True},
            {'name': 'Utilities', 'code': '5100', 'type': 'EXPENSE', 'subtype': 'GENERAL_EXPENSE', 'category': 'UTILITIES', 'is_system_account': True},
            {'name': 'Other Expenses', 'code': '5200', 'type': 'EXPENSE', 'subtype': 'GENERAL_EXPENSE', 'category': 'OTHER_EXPENSES', 'is_system_account': True},
            {'name': 'Direct Expense', 'code': '5300', 'type': 'EXPENSE', 'subtype': 'COST_OF_GOODS_SOLD', 'category': 'DIRECT_EXPENSE', 'is_system_account': True},
            {'name': 'Indirect Expense', 'code': '5400', 'type': 'EXPENSE', 'subtype': 'COST_OF_GOODS_SOLD', 'category': 'INDIRECT_EXPENSE', 'is_system_account': True},
            {'name': 'Salaries and Wages', 'code': '5500', 'type': 'EXPENSE', 'subtype': 'PAYROLL_EXPENSE', 'category': 'SALARIES_WAGES', 'is_system_account': True},
            {'name': 'Employee Benefits', 'code': '5600', 'type': 'EXPENSE', 'subtype': 'PAYROLL_EXPENSE', 'category': 'BENEFITS', 'is_system_account': True},
            {'name': 'Accumulated Depreciation', 'code': '5700', 'type': 'EXPENSE', 'subtype': 'DEPRECIATION', 'category': 'ACCUMULATED_DEPRECIATION', 'is_system_account': True},
        ]
        *
        *
        *
         */

        $accounts = [
            // Assets
            [
                'name' => 'Cash-in-Hand',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1001,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1000',
            ],
            [
                'name' => 'Bank',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1002,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1100',
            ],
            [
                'name' => 'Payment Clearing',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1003,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1200',
            ],
            [
                'name' => 'Input Tax',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1004,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1300',
            ],
            [
                'name' => 'Accounts Receivable',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1005,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1400',
            ],
            [
                'name' => 'Stock',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'account_subsidiary_id' => 1051,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1500',
            ],
            [
                'name' => 'Intangible Assets',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'account_subsidiary_id' => 1052,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1600',
            ],
            [
                'name' => 'Other Asset',
                'account_group_id' => 1,
                'account_control_id' => 1050,
                'account_subsidiary_id' => 1053,
                'branch_id' => 1,
                ' is_deletable' => 0,
                'account_number' => '1700',
            ],
            [
                'name' => 'Mobile Financial Services',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1006,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '1800',
            ],
            [
                'name' => 'AIT Receivable',
                'account_group_id' => 1,
                'account_control_id' => 1000,
                'account_subsidiary_id' => 1023,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '102301',
            ],

            // Liabilities
            [
                'name' => 'Accounts Payable',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'account_subsidiary_id' => 2001,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2000',
            ],
            [
                'name' => 'Accrued Liabilities',
                'account_group_id' => 2,
                'account_control_id' => 2000,
                'account_subsidiary_id' => 2002,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2100',
            ],
            [
                'name' => 'Credit Card',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'account_subsidiary_id' => 2011,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2200',
            ],
            [
                'name' => 'AIT Payable',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'account_subsidiary_id' => 2012,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '201201',
            ],
            [
                'name' => 'Tax Payable',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'account_subsidiary_id' => 2012,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2300',
            ],
            [
                'name' => 'Overseas Tax Payable',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'account_subsidiary_id' => 2013,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2400',
            ],
            [
                'name' => 'Other Liability',
                'account_group_id' => 2,
                'account_control_id' => 2010,
                'account_subsidiary_id' => 2014,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '2500',
            ],

            // Equity
            [
                'name' => 'Retained Earnings',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'account_subsidiary_id' => 3001,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '3000',
            ],
            [
                'name' => 'Common Stock',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'account_subsidiary_id' => 3002,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '3100',
            ],
            [
                'name' => 'Dividends',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'account_subsidiary_id' => 3003,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '3200',
            ],
            [
                'name' => 'Other Equity',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'account_subsidiary_id' => 3004,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '3300',
            ],
            [
                'name' => 'Opening Balance Adjustment',
                'account_group_id' => 3,
                'account_control_id' => 3000,
                'account_subsidiary_id' => 3004,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '3400',
            ],
            // Income
            [
                'name' => 'Sales Revenue',
                'account_group_id' => 4,
                'account_control_id' => 4000,
                'account_subsidiary_id' => 4001,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '4000',
            ],
            [
                'name' => 'Service Revenue',
                'account_group_id' => 4,
                'account_control_id' => 4010,
                'account_subsidiary_id' => 4011,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '4100',
            ],
            [
                'name' => 'Interest Income',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'account_subsidiary_id' => 4021,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '4200',
            ],
            [
                'name' => 'Rental Income',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'account_subsidiary_id' => 4022,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '4300',
            ],
            [
                'name' => 'Other Income Source',
                'account_group_id' => 4,
                'account_control_id' => 4020,
                'account_subsidiary_id' => 4023,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '4400',
            ],

            // Expenses
            [
                'name' => 'Office Supplies',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'account_subsidiary_id' => 5001,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5000',
            ],
            [
                'name' => 'Utilities',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'account_subsidiary_id' => 5002,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5100',
            ],
            [
                'name' => 'Other Expenses',
                'account_group_id' => 5,
                'account_control_id' => 5000,
                'account_subsidiary_id' => 5003,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5200',
            ],
            [
                'name' => 'Direct Expense',
                'account_group_id' => 5,
                'account_control_id' => 5010,
                'account_subsidiary_id' => 5011,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5300',
            ],
            [
                'name' => 'Indirect Expense',
                'account_group_id' => 5,
                'account_control_id' => 5010,
                'account_subsidiary_id' => 5010,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5400',
            ],
            [
                'name' => 'Salaries and Wages',
                'account_group_id' => 5,
                'account_control_id' => 5020,
                'account_subsidiary_id' => 5021,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5500',
            ],
            [
                'name' => 'Employee Benefits',
                'account_group_id' => 5,
                'account_control_id' => 5020,
                'account_subsidiary_id' => 5022,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5600',
            ],
            [
                'name' => 'Accumulated Depreciation',
                'account_group_id' => 5,
                'account_control_id' => 5030,
                'account_subsidiary_id' => 5031,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5700',
            ],
            [
                'name' => 'Salaries and Allowance Expense',
                'account_group_id' => 5,
                'account_control_id' => 5040,
                'account_subsidiary_id' => 5041,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '5800',
            ],
            [
                'name' => 'Waiver',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'account_subsidiary_id' => 5053,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '505301',
            ],
            [
                'name' => 'Bad Debt Expense',
                'account_group_id' => 5,
                'account_control_id' => 5050,
                'account_subsidiary_id' => 5054,
                'branch_id' => 1,
                'is_deletable' => 0,
                'account_number' => '505401',
            ]
        ];

        foreach ($accounts as $key => $account) {
            Account::updateOrCreate([
                'account_group_id' => $account['account_group_id'],
                'account_control_id' => $account['account_control_id'],
                'account_subsidiary_id' => $account['account_subsidiary_id'],
                'account_number' => $account['account_number'],
            ], [
                'name' => $account['name'],
                'branch_id' => $account['branch_id'],
                'is_deletable' => isset($account['is_deletable']) ? $account['is_deletable'] : 0, // Default to 0 if not set
            ]);
        }



    }
}