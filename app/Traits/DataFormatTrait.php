<?php
namespace App\Traits;
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
}
