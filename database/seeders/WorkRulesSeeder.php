<?php

namespace Database\Seeders;

use App\Models\WorkRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['work_id' => 'ca66ce7412b64b2c8fd56070f59dbf67', 'name' => '9%','descr_ru' => ' - с каждого заказа','descr_eng' => ' - from every order','id_enable' => '1'],
            ['work_id' => 'ad00a0a1648f4ab09ec93f66c169ca92', 'name' => '6%','descr_ru' => ' - с каждого заказа','descr_eng' => ' - from every order','id_enable' => '1'],
            ['work_id' => 'e26a3cf21acfe01198d50030487e046b', 'name' => '3%','descr_ru' => ' - с каждого заказа','descr_eng' => ' - from every order','id_enable' => '1'],
            ['work_id' => '180326e5a0284483b4952d535b0b1d7b', 'name' => '50','descr_ru' => 'руб в день, снимаются после первого заказа','descr_eng' => 'rub per day, removed after first order','id_enable' => '1'],
        ];

        foreach ($posts as $post) {
            WorkRule::create($post);
        }
    }
}
