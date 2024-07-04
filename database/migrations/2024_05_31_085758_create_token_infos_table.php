<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('token_infos', function (Blueprint $table) {
            $table->id();
            $table->text('idempotency_token')->default(null);
            $table->text('client_id')->default(null);
            $table->text('api_key')->default(null);
            $table->text('park_id')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_infos');
    }
};
