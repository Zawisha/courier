<?php

namespace Database\Seeders;

use App\Models\CarColors;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['color_ru' => 'Белый', 'color_eng' => 'White'],
            ['color_ru' => 'Желтый', 'color_eng' => 'Yellow'],
            ['color_ru' => 'Бежевый', 'color_eng' => 'Beige'],
            ['color_ru' => 'Черный', 'color_eng' => 'Black'],
            ['color_ru' => 'Голубой', 'color_eng' => 'Light Blue'],
            ['color_ru' => 'Серый', 'color_eng' => 'Grey'],
            ['color_ru' => 'Красный', 'color_eng' => 'Red'],
            ['color_ru' => 'Оранжевый', 'color_eng' => 'Orange'],
            ['color_ru' => 'Синий', 'color_eng' => 'Blue'],
            ['color_ru' => 'Зеленый', 'color_eng' => 'Green'],
            ['color_ru' => 'Коричневый', 'color_eng' => 'Brown'],
            ['color_ru' => 'Фиолетовый', 'color_eng' => 'Purple'],
            ['color_ru' => 'Розовый', 'color_eng' => 'Pink'],
        ];

        foreach ($posts as $post) {
            CarColors::create($post);
        }
    }
}
