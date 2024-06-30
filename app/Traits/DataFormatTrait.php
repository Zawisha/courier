<?php
namespace App\Traits;
use App\Models\CarBrand;
use App\Models\CarColors;
use App\Models\CarModel;
use App\Models\CarTransmission;
use App\Models\StatusCourier;

trait DataFormatTrait
{

    protected $statusCourier;

    public function __construct(StatusCourier $statusCourier)
    {
        $this->statusCourier = $statusCourier;
    }

    public function mainTransform($request)
    {
       $date_of_birth=$this->TransformDataToApiView($request->date_of_birth);
       $phone=$this->TransformPhone($request->phone);
       $role=$this->TransformRole($request->role);

    }

    public function TransformDataToApiView($date)
    {
        $regex = '/(\d{1,2})-(\d{1,2})-(\d{4})/';
        preg_match($regex, $date,$matches);
        return $matches[3] . "-" . $matches[2] . "-" . $matches[1];
    }
    public function TransformPhone($phone)
    {
        $regex = '/[\s()-]/';
        $newPhoneNumber = preg_replace($regex, '', $phone);
        return $newPhoneNumber;
    }
    public function TransformRole($role)
    {
        $roleId=$this->statusCourier->getStatusId($role);
        return $roleId[0];
    }
    public function transformAvtoCategories($role)
    {
        $arr=[];
        if(($role=='moto')||($role=='avto'))
        {
            return $arr=['express'];
        }
        if($role=='gruz')
        {
            return $arr=['express','cargo'];
        }
    }
    public function transformColor($color)
    {
        return CarColors::where('id', $color)->value('color_ru');
    }
    public function transformTransmission($transmission)
    {
        return CarTransmission::where('id',$transmission)->value('transmission_eng');;
    }
    public function transformBrand($brand)
    {
        return CarBrand::where('id',$brand)->value('car_brand');;
    }
    public function transformModel($carModel)
    {
        return CarModel::where('id',$carModel)->value('car_model');;
    }
}
