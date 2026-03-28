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

                OUT total_day INT,
                OUT weekend_day INT,
                OUT holiday INT,
                OUT absent_day INT,  
                OUT late_day INT,
                OUT leave_day INT,
                OUT working_day INT,
                OUT payment_method VARCHAR(10),  

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
                -- variable declare 
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
                DECLARE weekend_count INT DEFAULT 0; 
                DECLARE holiday_count INT DEFAULT 0; 
                DECLARE friday_holiday_count INT DEFAULT 0; 
                 


                -- set default value 0
                SET total_day = 0;
                SET weekend_day = 0;
                SET holiday = 0;
                SET absent_day = 0;
                SET late_day = 0;
                SET leave_day = 0;
                SET working_day = 0;
                SET payment_method = 'CASH';
                SET weekend_count = 0;


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

                -- get salary structure from salary structure table
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
                    tax,
                    payment_type
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
                    tax_deduction,
                    payment_method
                FROM employee_salaries
                WHERE employee_id = emp_id
                  AND status = 1
                  AND effective_date <= salary_month
                ORDER BY effective_date DESC
                LIMIT 1;

                -- set salary break down value
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
   

                -- get loan 
                SELECT IFNULL(monthly_reduction,0)
                INTO loan_deduction
                FROM loans
                WHERE employee_id = emp_id
                  AND start_month <= DATE_FORMAT(salary_month,'%Y-%m')
                  AND DATE_FORMAT(DATE_ADD(CONCAT(start_month,'-01'), INTERVAL duration MONTH), '%Y-%m') > DATE_FORMAT(salary_month,'%Y-%m')
                  AND status = 'paid'
                LIMIT 1;
 
                -- get employee joining date, terminatation date
                SELECT date_of_joining, date_of_termination INTO joiningDate, terminationDate FROM employement_details WHERE employee_id = emp_id LIMIT 1;

                -- get generate month theke joining date age naki pore seta ber kora hoyeche
                SELECT COUNT(*) INTO considerJoinDate FROM employement_details  WHERE employee_id = emp_id AND DATE_FORMAT(date_of_joining,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m');

                -- get employee present days
                SELECT COUNT(*) INTO present_days  FROM attendances WHERE employee_id=emp_id AND DATE_FORMAT(check_in_date,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m');   

                -- get employee weekend present days
                SELECT COUNT(*) INTO weekend_present_days FROM attendances WHERE employee_id=emp_id AND DATE_FORMAT(check_in_date,'%Y-%m') <= DATE_FORMAT(salary_month,'%Y-%m') AND DAYOFWEEK(check_in_date) = 6; 
                
                -- get employee leave days
                SELECT SUM(day_count) INTO leave_days FROM leave_applications WHERE employee_id=emp_id AND DATE_FORMAT(from_date,'%Y-%m')=DATE_FORMAT(salary_month,'%Y-%m') AND DATE_FORMAT(to_date,'%Y-%m')=  DATE_FORMAT(salary_month,'%Y-%m');

                -- joining: jodi joining date maser suru or majhe kina check kore absent days calculate kora hoyeche
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
 
                    -- termination date er por weekend calculate
                    SET @d := salary_month; 
                    WHILE @d <= joiningDate DO
                        IF DAYOFWEEK(@d) = 6 THEN
                            SET weekend_count = weekend_count + 1;
                        END IF;
                        SET @d = DATE_ADD(@d, INTERVAL 1 DAY);
                    END WHILE; 
                    SET weekend_day = weekend_days - weekend_count;

                    
                    -- termination date er por holiday calculate
                    WITH RECURSIVE all_holiday_days AS (
                        SELECT 
                            GREATEST(h.start_date, salary_month) AS dt, 
                            LEAST(h.end_date, joiningDate) AS e_date   
                        FROM holidays h
                        WHERE h.start_date <= joiningDate
                        AND h.end_date >= salary_month

                        UNION ALL

                        SELECT DATE_ADD(dt, INTERVAL 1 DAY), e_date
                        FROM all_holiday_days
                        WHERE dt < e_date
                    )
                    SELECT  COUNT(*), SUM(DAYOFWEEK(dt) = 6) INTO holiday_count,friday_holiday_count FROM all_holiday_days;
                    SET holidays = holidays - holiday_count; 

                END IF;

                -- termination: jodi empoyee maser majhe or seshe terminate hoy sei onujayi absent calculate kora hoyche
                IF(terminationDate != '' OR terminationDate IS NULL) THEN         
                    SELECT 
                        CASE 
                            WHEN terminationDate <= LAST_DAY(salary_month)
                            THEN DATEDIFF(LAST_DAY(salary_month), terminationDate) 
                            ELSE 0
                        END INTO absent_working_days_terminate_employee;
                    SET absent_days = absent_days + absent_working_days_terminate_employee;

                    -- termination date er por weekend calculate
                    SET @d := terminationDate; 
                    WHILE @d <= LAST_DAY(salary_month) DO
                        IF DAYOFWEEK(@d) = 6 THEN
                            SET weekend_count = weekend_count + 1;
                        END IF;
                        SET @d = DATE_ADD(@d, INTERVAL 1 DAY);
                    END WHILE;
  
                    SET weekend_day = weekend_days - weekend_count;

                    -- termination date er por holiday calculate
                    WITH RECURSIVE all_holiday_days AS (
                        SELECT 
                            GREATEST(h.start_date, terminationDate) AS dt,  -- termination date
                            LEAST(h.end_date, LAST_DAY(terminationDate)) AS e_date   -- মাসের শেষ দিন
                        FROM holidays h
                        WHERE h.start_date <= LAST_DAY(terminationDate)
                        AND h.end_date >= terminationDate

                        UNION ALL

                        SELECT DATE_ADD(dt, INTERVAL 1 DAY), e_date
                        FROM all_holiday_days
                        WHERE dt < e_date
                    ) 
 
                    SELECT  COUNT(*), SUM(DAYOFWEEK(dt) = 6) INTO holiday_count, friday_holiday_count FROM all_holiday_days;
                    SET holidays = holidays - holiday_count; 


                END IF;
 

                SELECT IF(consider_absent=0 AND deduct_from_gross=1,gross_salary,basic_salary) INTO absentDeductSalary  FROM absent_policies LIMIT 1;
                SET absent_deduction = (absentDeductSalary / total_days) * absent_days;

                
                -- delay buffer time calculate kora hoyeche
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


                -- extream delay buffer time calculate kora hoyeche
                SELECT COUNT(*) INTO exDelayCount FROM attendances WHERE employee_id = emp_id AND DAYOFWEEK(check_in_date) != 6 AND check_in_time>v_ex_delay_buffer; 
                SELECT IF(deduct_from_gross_salary=1,gross_salary,basic_salary),extreme_delay_limit,adjust_days,consider_extreme_delay INTO exDelayDeductSalary,exDelayLimit,exAdjustDays,considerExtremeDelay  FROM extreme_delay_policies LIMIT 1; 
                 
                IF(exDelayLimit > 0 AND considerExtremeDelay = 1 AND exAdjustDays > 0) THEN
                    SET late_deduction = late_deduction + ((exDelayDeductSalary / total_days) * (FLOOR(exDelayCount / exDelayLimit) * exAdjustDays));
                ELSE
                    SET late_deduction = late_deduction;
                END IF;

                -- salary payment sum kora hoyeche
                SET salary_payment = 
                    (basic_salary + house_rent + medical + conveyance + entertainment + leave_fare + utility + unkeep + others)
                    - (absent_deduction + late_deduction + leave_deduction + loan_deduction + advance_deduction + tax_deduction);

                SET total_day = total_days;
                SET weekend_day = weekend_days;
                SET holiday = holidays;
                SET absent_day = absent_days;
                SET late_day = delayCount + exDelayCount;
                SET leave_day = leave_days;
                SET working_day = working_days;
                SET payment_method = payment_method;

            END
        ");
    }
 
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_salary_gennerate_details");
    }
};
