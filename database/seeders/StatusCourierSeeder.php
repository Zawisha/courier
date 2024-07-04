<?php

namespace Database\Seeders;

use App\Models\StatusCourier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusCourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['value_status' => 'pesh', 'status' => 'Пеший курьер', 'status_eng' => 'Walking courier'],
            ['value_status' => 'velo', 'status' => 'Вело курьер', 'status_eng' => 'Bicycle courier'],
            ['value_status' => 'moto', 'status' => 'Мото курьер', 'status_eng' => 'Motorcycle courier'],
            ['value_status' => 'avto', 'status' => 'Авто курьер', 'status_eng' => 'Car courier'],
            ['value_status' => 'gruz', 'status' => 'Грузовой курьер', 'status_eng' => 'Cargo courier'],
        ];

        foreach ($posts as $post) {
            StatusCourier::create($post);
        }
    }
}
