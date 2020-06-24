<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'role' => 'student'],
            ['id' => 2, 'role' => 'educator'],
            ['id' => 3, 'role' => 'dephead'],
            ['id' => 4, 'role' => 'orgadmin'],
            ['id' => 5, 'role' => 'new'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
