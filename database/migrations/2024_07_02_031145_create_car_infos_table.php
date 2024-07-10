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
        Schema::create('car_infos', function (Blueprint $table) {
            $table->id();
            $table->text('vehicle_id')->nullable();
            $table->integer('boosterCount')->default(0);
            $table->text('licencePlateNumber')->nullable();
            $table->text('registrationCertificate')->nullable();
            $table->integer('brandTS_id')->references('id')->on('car_models')->onDelete('cascade')->default(0);
            $table->integer('modelTS_id')->references('id')->on('car_brands')->onDelete('cascade')->default(0);
            $table->integer('colorAvto_id')->references('id')->on('car_colors')->onDelete('cascade')->default(0);
            $table->integer('transmission_id')->references('id')->on('car_transmissions')->onDelete('cascade')->default(0);
            $table->text('vin')->nullable();
            $table->integer('carManufactureYear')->default(1970);
            $table->integer('cargoHoldDimensionsHeight')->default(0);
            $table->integer('cargoHoldDimensionsLength')->default(0);
            $table->integer('cargoHoldDimensionsWidth')->default(0);
            $table->integer('cargoLoaders')->default(0);
            $table->integer('cargoCapacity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_infos');
    }
};
