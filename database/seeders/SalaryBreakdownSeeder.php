<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryBreakdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => '1', 'type' => 'Basic', 'value' => 'basic', 'status' => '1', 'created_at' => now()],
            ['id' => '2', 'type' => 'House Rent', 'value' => 'house_rent', 'status' => '1', 'created_at' => now()],
            ['id' => '3', 'type' => 'Conveyance', 'value' => 'conveyance', 'status' => '1', 'created_at' => now()],
            ['id' => '4', 'type' => 'Medical', 'value' => 'medical', 'status' => '1', 'created_at' => now()],
            ['id' => '5', 'type' => 'Entertainment', 'value' => 'entertainment', 'status' => '1', 'created_at' => now()],
            ['id' => '6', 'type' => 'Leave Fare', 'value' => 'leave_fare', 'status' => '1', 'created_at' => now()],
            ['id' => '7', 'type' => 'Utility', 'value' => 'utility', 'status' => '1', 'created_at' => now()],
            ['id' => '8', 'type' => 'Unkeep', 'value' => 'unkeep', 'status' => '1', 'created_at' => now()], 
            ['id' => '9', 'type' => 'Unkeep', 'value' => 'others', 'status' => '1', 'created_at' => now()],
         
        ]; 
        DB::table('salary_breakdowns')->truncate();
        DB::table('salary_breakdowns')->insert($data);
    }
}
