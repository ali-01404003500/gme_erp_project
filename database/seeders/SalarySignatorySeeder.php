<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalarySignatorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['id' => '1', 'employee_id' => '1135', 'signatory_tag' => 'Dept', 'approver_level' => '1', 'status' => 'active', 'description' => 'Prepared By Department Head', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '2', 'employee_id' => '1135', 'signatory_tag' => 'Hr', 'approver_level' => '2', 'status' => 'active', 'description' => 'Checked by HR Head', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '3', 'employee_id' => '1135', 'signatory_tag' => 'Admin', 'approver_level' => '3', 'status' => 'active', 'description' => 'Verified by Admin Head', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '4', 'employee_id' => '1135', 'signatory_tag' => 'Accounts', 'approver_level' => '4', 'status' => 'active', 'description' => 'Verified by Accounts Head', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '5', 'employee_id' => '1135', 'signatory_tag' => 'CEO', 'approver_level' => '5', 'status' => 'active', 'description' => 'Approved by Chief Executive Officer(CEO)', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '6', 'employee_id' => '1135', 'signatory_tag' => 'MD', 'approver_level' => '6', 'status' => 'active', 'description' => 'Final  Approved by Managining Director', 'created_by' => '1135', 'created_at' => now()],
            ['id' => '7', 'employee_id' => '1135', 'signatory_tag' => 'Chairman', 'approver_level' => '7', 'status' => 'active', 'description' => 'Approved by Chairman', 'created_by' => '1135', 'created_at' => now()],
         
        ]; 
        DB::table('salary_signatories')->truncate();
        DB::table('salary_signatories')->insert($data);
    }
}
