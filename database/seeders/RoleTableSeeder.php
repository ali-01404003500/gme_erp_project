<?php

namespace Database\Seeders;

use App\Models\AccessControl\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'supper_admin',
                'is_default' => true
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'is_default' => true
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'is_default' => true
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'is_default' => true
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'slug' => $role['slug']
            ], $role);
        }

        $role = Role::where('slug', 'supper_admin')->first();
        $role->users()->sync([1]);
        foreach ($role->users as $user) {
            InvalidateAuthUserCashe($user->id);
        }
    }
}
