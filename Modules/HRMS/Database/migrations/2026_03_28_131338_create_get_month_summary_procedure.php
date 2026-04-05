 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS `GetMonthSummary`;
        ");

        DB::unprepared("
            CREATE PROCEDURE `GetMonthSummary`(IN p_month VARCHAR(12))
            BEGIN
                DECLARE start_date DATE;
                DECLARE end_date DATE;
                DECLARE total_days INT;
                DECLARE weekend_count INT DEFAULT 0;
                DECLARE holiday_count INT DEFAULT 0;
                DECLARE working_days INT;
                DECLARE holiday_weekend_count INT DEFAULT 0;

                -- Calculate start and end date of month
                SET start_date = STR_TO_DATE(p_month, '%Y-%m-%d');
                SET end_date = LAST_DAY(start_date);
                SET total_days = DAY(end_date);

                -- Count weekends (Friday as weekend)
                SET @d := start_date;
                SET weekend_count = 0;

                WHILE @d <= end_date DO
                    IF DAYOFWEEK(@d) = 6 THEN -- 6 = Friday
                        SET weekend_count = weekend_count + 1;
                    END IF;
                    SET @d = DATE_ADD(@d, INTERVAL 1 DAY);
                END WHILE;

                -- Count holidays (total days in holidays)
                SET holiday_count = 0;
                SET holiday_weekend_count = 0;

                SELECT IFNULL(SUM(
                    DATEDIFF(
                        LEAST(h.end_date, end_date),
                        GREATEST(h.start_date, start_date)
                    ) + 1
                ),0) INTO holiday_count
                FROM holidays h
                WHERE h.start_date <= end_date
                  AND h.end_date >= start_date;

                -- Count holidays that fall on weekends (Friday)
                WITH RECURSIVE all_holiday_days AS (
                    SELECT DATE(h.start_date) AS dt, DATE(h.end_date) AS e_date
                    FROM holidays h
                    WHERE h.start_date <= end_date
                      AND h.end_date >= start_date

                    UNION ALL

                    SELECT DATE_ADD(dt, INTERVAL 1 DAY), e_date
                    FROM all_holiday_days
                    WHERE dt < e_date
                )
                SELECT COUNT(*) INTO holiday_weekend_count
                FROM all_holiday_days
                WHERE DAYOFWEEK(dt) = 6;

                -- Calculate working days
                SET working_days = total_days - weekend_count - holiday_count + holiday_weekend_count;

                -- Return result
                SELECT 
                    total_days AS total_days,
                    weekend_count AS weekends,
                    (holiday_count - holiday_weekend_count) AS holidays,
                    working_days AS working_days,
                    holiday_weekend_count AS holiday_weekend;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS `GetMonthSummary`');
    }
};