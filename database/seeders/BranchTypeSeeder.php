<?php
namespace Database\Seeders;

use App\Models\AccessControl\BranchType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $branchTypes = [
            [
                'id' => 2,
                'name' => 'Depot',
                'description' => 'Depot is a place where goods are stored.',
            ],
            [
                'id' => 3,
                'name' => 'Dealer',
                'description' => 'Dealer is a place where goods are sold.',
            ],
            [
                'id' => 4,
                'name' => 'Sub Dealer',
                'description' => 'Sub Dealer is a place where goods are sold.',
            ],
            [
                'id' => 5,
                'name' => 'Sales Representative',
                'description' => 'Sales Representative',
            ],
        ];
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('branch_types')->truncate();

        foreach ($branchTypes as $value) {
          
                BranchType::updateOrCreate(
                    ['id' => $value['id'],],
                    [
                        'name' => $value['name'],
                        'description' => $value['description'],
                    ]
                );
            }
       }
    
}
