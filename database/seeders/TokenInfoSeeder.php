<?php

namespace Database\Seeders;

use App\Models\TokenInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokenInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['idempotency_token' => 'c56fa6537e5a4adbbce6ef3593210fb9', 'client_id' => 'taxi/park/9d5bd73ca1f044fd8aeba739793870ab', 'api_key' => 'LvNTgRICgUDnytxRerDxcYOGRcjbZVyBWLkgZQL', 'park_id' => '9d5bd73ca1f044fd8aeba739793870ab'],
        ];

        foreach ($posts as $post) {
            TokenInfo::create($post);
        }
    }
}
