<?php

namespace Database\Seeders;

use App\Models\CarTransmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarTransmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['transmission_ru' => 'неизвестно', 'transmission_eng' => 'unknown'],
            ['transmission_ru' => 'механическая', 'transmission_eng' => 'mechanical'],
            ['transmission_ru' => 'автомат', 'transmission_eng' => 'automatic'],
            ['transmission_ru' => 'роботизированная', 'transmission_eng' => 'robotic'],
            ['transmission_ru' => 'вариатор', 'transmission_eng' => 'variator'],
        ];

        foreach ($posts as $post) {
            CarTransmission::create($post);
        }
    }
}
