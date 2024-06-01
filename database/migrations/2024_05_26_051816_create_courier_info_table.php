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
        Schema::create('courier_info', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('role_id');
            $table->text('first_name')->nullable();
            $table->text('surname')->nullable();
            $table->text('patronymic')->nullable();
            $table->text('date_of_birth')->nullable();
            $table->text('licenceNumber')->nullable();
            $table->text('license_issue')->nullable();
            $table->text('license_expirated')->nullable();
            $table->text('telegram')->nullable();
            $table->text('contractor_profile_id')->nullable();
            $table->integer('sended_to_yandex')->default(0);
            $table->integer('work_rule_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_info');
    }
};
