<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание права на просмотр страницы
        Permission::create(['name' => 'view page']);

        // Создание ролей и назначение права
        $role = Role::create(['name' => 'user']);

        $role = Role::create(['name' => 'redaktor']);
        $role->givePermissionTo('view page');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('view page');
    }
}
