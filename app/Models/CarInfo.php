<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarInfo extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function carColor()
    {
        return $this->belongsTo(CarColors::class, 'colorAvto_id');
    }

    public function carTransmission()
    {
        return $this->belongsTo(CarTransmission::class, 'transmission_id');
    }

    public function createCarInfo($carId,$boosterCount,$licencePlateNumber,$registrationCertificate,$brandTS_id,$modelTS_id,$carColor,$Transmission,$vin,$carManufactureYear,$cargoHoldDimensionsHeight,$cargoHoldDimensionsLength,$cargoHoldDimensionsWidth,$cargoLoaders,$cargoCapacity)
    {
        //$brandTS_id == 0 это bike
        $carInfo =  CarInfo::create([
            'vehicle_id' => $carId,
            'boosterCount' => $boosterCount,
            'licencePlateNumber' => $licencePlateNumber,
            'registrationCertificate' => $registrationCertificate,
            'brandTS_id' => $brandTS_id,
            'modelTS_id' => $modelTS_id,
            'colorAvto_id' => $carColor,
            'transmission_id' => $Transmission,
            'vin' => $vin,
            'carManufactureYear' =>$carManufactureYear ,
            'cargoHoldDimensionsHeight' =>$cargoHoldDimensionsHeight ,
            'cargoHoldDimensionsLength' =>$cargoHoldDimensionsLength ,
            'cargoHoldDimensionsWidth' => $cargoHoldDimensionsWidth,
            'cargoLoaders' =>$cargoLoaders ,
            'cargoCapacity' => $cargoCapacity,
        ]);
        $newCarInfoId = $carInfo->id;
        return $newCarInfoId;
    }

    public function editCar(
        $id,
        $licencePlateNumber,
        $registrationCertificate,
        $brandTS_id,
        $modelTS_id,
        $carColor,
        $Transmission,
        $vin,
        $carManufactureYear,
        $cargoHoldDimensionsHeight,
        $cargoHoldDimensionsLength,
        $cargoHoldDimensionsWidth,
        $cargoLoaders,
        $cargoCapacity
    )
    {
        CarInfo::where('id', $id)->update([
            'licencePlateNumber' => $licencePlateNumber,
            'registrationCertificate' => $registrationCertificate,
            'brandTS_id' => $brandTS_id,
            'modelTS_id' => $modelTS_id,
            'colorAvto_id' => $carColor,
            'transmission_id' => $Transmission,
            'vin' => $vin,
            'carManufactureYear' =>$carManufactureYear ,
            'cargoHoldDimensionsHeight' =>$cargoHoldDimensionsHeight ,
            'cargoHoldDimensionsLength' =>$cargoHoldDimensionsLength ,
            'cargoHoldDimensionsWidth' => $cargoHoldDimensionsWidth,
            'cargoLoaders' =>$cargoLoaders ,
            'cargoCapacity' => $cargoCapacity,
        ]);
    }


}
