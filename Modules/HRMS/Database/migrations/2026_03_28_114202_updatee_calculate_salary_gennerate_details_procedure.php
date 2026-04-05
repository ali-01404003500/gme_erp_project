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

                IN total_days INT,
                IN weekend_days INT,
                IN holidays INT,
                IN working_days INT,  

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
                DECLARE present_days INT DEFAULT 0;
                DECLARE weekend_present_days INT DEFAULT 0;
                DECLARE absent_days INT DEFAULT 0;
                DECLARE absentDeductSalary DECIMAL(10,2) DEFAULT 0.00;
                DECLARE leave_days INT DEFAULT 0;

                DECLARE v_delay_buffer TIME;
                DECLARE v_ex_delay_buffer TIME;
                DECLARE v_early_out_time TIME;

                DECLARE delayCount INT DEFAULT 0;
                DECLARE delayDeductSalary INT DEFAULT 0;
                DECLARE delayLimit INT DEFAULT 0;
                DECLARE adjustDays INT DEFAULT 0;
                DECLARE considerDelay INT DEFAULT 0;

                DECLARE exDelayCount INT DEFAULT 0;
                DECLARE exDelayDeductSalary INT DEFAULT 0;
                DECLARE exDelayLimit INT DEFAULT 0;
                DECLARE exAdjustDays INT DEFAULT 0;
                DECLARE considerExtremeDelay INT DEFAULT 0;
  
                DECLARE considerJoinDate INT DEFAULT 0; 
                DECLARE joiningDate date;
                DECLARE terminationDate date;
                DECLARE absent_working_days_terminate_employee INT DEFAULT 0;
                

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
 
 
                SELECT date_of_joining, date_of_termination INTO joiningDate, terminationDate FROM employement_details WHERE employee_id = emp_id LIMIT 1;

              
                SELECT COUNT(*) INTO considerJoinDate FROM employement_details  WHERE employee_id = emp_id AND DATE_FORMAT(date_of_joining,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m');

                
                SELECT COUNT(*) INTO present_days  FROM attendances WHERE employee_id=emp_id AND DATE_FORMAT(check_in_date,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m');   
                SELECT COUNT(*) INTO weekend_present_days FROM attendances WHERE employee_id=emp_id AND DATE_FORMAT(check_in_date,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m') AND DAYOFWEEK(check_in_date) = 6; 
                
                SELECT SUM(day_count) INTO leave_days FROM leave_applications WHERE employee_id=emp_id AND DATE_FORMAT(from_date,'%Y-%m')=DATE_FORMAT(salary_month,'%Y-%m') AND DATE_FORMAT(to_date,'%Y-%m')=  DATE_FORMAT(salary_month,'%Y-%m');

                IF(considerJoinDate > 0) THEN
                    SET absent_days = working_days - (present_days - weekend_present_days) - leave_days;
                ELSE
                    SELECT 
                        CASE 
                            WHEN joiningDate > salary_month 
                            THEN DATEDIFF(LAST_DAY(salary_month), joiningDate) + 1
                            ELSE DAY(LAST_DAY(salary_month))
                        END INTO working_days;
                    SET absent_days = working_days - (present_days - weekend_present_days) - leave_days;
                END IF;


                IF(terminationDate != '' OR terminationDate IS NULL) THEN         
                    SELECT 
                        CASE 
                            WHEN terminationDate <= LAST_DAY(salary_month)
                            THEN DATEDIFF(LAST_DAY(salary_month), terminationDate) 
                            ELSE 0
                        END INTO absent_working_days_terminate_employee;
                    SET absent_days = absent_days + absent_working_days_terminate_employee;
                END IF;
 

                SELECT IF(consider_absent=0 AND deduct_from_gross=1,gross_salary,basic_salary) INTO absentDeductSalary  FROM absent_policies LIMIT 1;
                SET absent_deduction = (absentDeductSalary / total_days) * absent_days;

                

                SELECT TIME_FORMAT(ADDTIME(in_time, SEC_TO_TIME(delay_buffer * 60)), '%H:%i:%s'),
                TIME_FORMAT(ADDTIME(in_time, SEC_TO_TIME(ex_delay_buffer * 60)), '%H:%i:%s'),
                TIME_FORMAT(early_out_time, '%H:%i:%s')
                INTO v_delay_buffer, v_ex_delay_buffer,  v_early_out_time
                FROM attendance_policies WHERE status = 1 LIMIT 1;
 

                SELECT COUNT(*) INTO delayCount FROM attendances WHERE employee_id = emp_id AND DAYOFWEEK(check_in_date) != 6 AND check_in_time>v_delay_buffer; 
                SELECT IF(deduct_from_gross_salary=1,gross_salary,basic_salary),delay_limit,adjust_days,consider_delay INTO delayDeductSalary,delayLimit,adjustDays,considerDelay  FROM delay_policies LIMIT 1;
               
                IF(delayLimit > 0 AND considerDelay = 1 AND adjustDays > 0) THEN
                    SET late_deduction = (delayDeductSalary / total_days) * (FLOOR(delayCount / delayLimit) * adjustDays);
                ELSE 
                    SET late_deduction = 0;
                END IF;


                SELECT COUNT(*) INTO exDelayCount FROM attendances WHERE employee_id = emp_id AND DAYOFWEEK(check_in_date) != 6 AND check_in_time>v_ex_delay_buffer; 
                SELECT IF(deduct_from_gross_salary=1,gross_salary,basic_salary),extreme_delay_limit,adjust_days,consider_extreme_delay INTO exDelayDeductSalary,exDelayLimit,exAdjustDays,considerExtremeDelay  FROM extreme_delay_policies LIMIT 1; 
                 
                IF(exDelayLimit > 0 AND considerExtremeDelay = 1 AND exAdjustDays > 0) THEN
                    SET late_deduction = late_deduction + ((exDelayDeductSalary / total_days) * (FLOOR(exDelayCount / exDelayLimit) * exAdjustDays));
                ELSE
                    SET late_deduction = late_deduction;
                END IF;

    
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
