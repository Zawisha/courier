<?php

namespace Database\Seeders;

use App\Models\PermSendApi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiPermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perm_send_apis')->insert([
            [
                'accessApi' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
