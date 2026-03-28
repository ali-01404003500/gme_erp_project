<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration {
    
    public function up()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS calculate_salary_gennerate_details;

            CREATE PROCEDURE calculate_salary_gennerate_details(
                IN emp_id INT,
                IN salary_month DATE,
                
                OUT gross_salary DECIMAL(10,2),
                OUT basic_salary DECIMAL(10,2),  
                OUT house_rent DECIMAL(10,2),
                OUT medical DECIMAL(10,2),
                OUT conveyance DECIMAL(10,2),
                OUT entertainment DECIMAL(10,2),
                OUT leave_fare DECIMAL(10,2),
                OUT utility DECIMAL(10,2),
                OUT unkeep DECIMAL(10,2),
                OUT others DECIMAL(10,2),
                
                OUT absent_deduction DECIMAL(10,2),
                OUT late_deduction DECIMAL(10,2),
                OUT leave_deduction DECIMAL(10,2),
                OUT loan_deduction DECIMAL(10,2),
                OUT advance_deduction DECIMAL(10,2),
                OUT tax_deduction DECIMAL(10,2),
                OUT salary_payment DECIMAL(10,2)
            )
            BEGIN

                SET gross_salary = 0;
                SET basic_salary = 0;
                SET house_rent = 0;
                SET medical = 0;
                SET conveyance = 0;
                SET entertainment = 0;
                SET leave_fare = 0;
                SET utility = 0;
                SET unkeep = 0;
                SET others = 0;

                SET absent_deduction = 0;
                SET late_deduction = 0;
                SET leave_deduction = 0;
                SET loan_deduction = 0;
                SET advance_deduction = 0;
                SET tax_deduction = 0;

                SET salary_payment = 0;

                SELECT 
                    gross,
                    basic + increase_basic,
                    house_rent + increase_house_rent,
                    medical + increase_medical,
                    conveyance + increase_conveyance,
                    entertainment + increase_entertainment,
                    leave_fare + increase_leave_fare,
                    utility + increase_utility,
                    unkeep + increase_unkeep,
                    others + increase_others,
                    tax
                INTO 
                    gross_salary,
                    basic_salary,
                    house_rent,
                    medical,
                    conveyance,
                    entertainment,
                    leave_fare,
                    utility,
                    unkeep,
                    others,
                    tax_deduction
                FROM employee_salaries
                WHERE employee_id = emp_id
                  AND status = 1
                  AND effective_date <= salary_month
                ORDER BY effective_date DESC
                LIMIT 1;

                SET gross_salary = IFNULL(gross_salary, 0);
                SET basic_salary = IFNULL(basic_salary, 0);
                SET house_rent = IFNULL(house_rent, 0);
                SET medical = IFNULL(medical, 0);
                SET conveyance = IFNULL(conveyance, 0);
                SET entertainment = IFNULL(entertainment, 0);
                SET leave_fare = IFNULL(leave_fare, 0);
                SET utility = IFNULL(utility, 0);
                SET unkeep = IFNULL(unkeep, 0);
                SET others = IFNULL(others, 0);
                SET tax_deduction = IFNULL(tax_deduction, 0);

                SELECT IFNULL(monthly_reduction,0)
                INTO loan_deduction
                FROM loans
                WHERE employee_id = emp_id
                  AND start_month <= DATE_FORMAT(salary_month,'%Y-%m')
                  AND DATE_FORMAT(DATE_ADD(CONCAT(start_month,'-01'), INTERVAL duration MONTH), '%Y-%m') > DATE_FORMAT(salary_month,'%Y-%m')
                  AND status = 'paid'
                LIMIT 1;

                SET salary_payment = 
                    (basic_salary + house_rent + medical + conveyance + entertainment + leave_fare + utility + unkeep + others)
                    - (absent_deduction + late_deduction + leave_deduction + loan_deduction + advance_deduction + tax_deduction);

            END
        ");
    }
 
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_salary_gennerate_details");
    }
};
