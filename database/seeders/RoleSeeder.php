<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Sukurti roles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $worker = Role::create(['name' => 'worker']);
        $customer = Role::create(['name' => 'customer']);

        // (Papildomai galima sukurti permission'us čia, jei reikia)
    }
}
