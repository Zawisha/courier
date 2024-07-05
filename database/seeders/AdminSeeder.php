<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание пользователей
        $admin1 = User::create([
            'name' => '+7 (968) 861-6277',
            'email' => 'admin1@example.com',
            'password' => bcrypt('couriermillioner'), // не забудьте изменить пароль
        ]);

        $admin2 = User::create([
            'name' => '+7 (777) 777-7777',
            'email' => 'admin2@example.com',
            'password' => bcrypt('lianali'), // не забудьте изменить пароль
        ]);

        // Назначение ролей администратора
        $adminRole = Role::where('name', 'admin')->first();

        $admin1->assignRole($adminRole);
        $admin2->assignRole($adminRole);
    }
}
