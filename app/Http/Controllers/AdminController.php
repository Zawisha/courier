<?php

namespace App\Http\Controllers;

use App\Models\CarBrand;
use App\Models\CarColors;
use App\Models\CarInfo;
use App\Models\CarTransmission;
use App\Models\CourierInfo;
use App\Models\ErrorsApiLog;
use App\Models\PermSendApi;
use App\Models\StatusCourier;
use App\Models\SuccessApiLog;
use App\Models\User;
use App\Models\WorkRule;
use App\Services\CustomErrorsService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    protected $permSendApi;

    public function __construct(StatusCourier $statusCourier, User $user, CourierInfo $courierInfo, YandexApiController $yandexApiController, WorkRule $workRule,
                                ErrorsApiLog $errorsApiLog, SuccessApiLog $successApiLog, CustomErrorsService $customErrorsService, CarColors $carColors, CarTransmission $carTransmission, CarBrand $carBrand, CarInfo $carInfo,
                                PermSendApi $permSendApi)
    {
        $this->statusCourier = $statusCourier;
        $this->user = $user;
        $this->courierInfo = $courierInfo;
        $this->yandexApiController = $yandexApiController;
        $this->workRule = $workRule;
        $this->errorsApiLog = $errorsApiLog;
        $this->successApiLog = $successApiLog;
        $this->customErrorsService = $customErrorsService;
        $this->carColors = $carColors;
        $this->carTransmission = $carTransmission;
        $this->carBrand = $carBrand;
        $this->carInfo = $carInfo;
        $this->permSendApi = $permSendApi;

    }

    public function usersList()
    {
        $couriers = User::join('courier_info', 'users.id', '=', 'courier_info.user_id')
            ->join('status_couriers', 'courier_info.role_id', '=', 'status_couriers.id')
            ->join('work_rules', 'courier_info.work_rule_id', '=', 'work_rules.id')
            ->leftJoin('car_infos', function ($join) {
                $join->on('courier_info.car_id', '=', 'car_infos.id')
                    ->where('courier_info.car_id', '<>', 0); // условие, если car_id не равен 0
            })
            ->leftJoin('car_brands', 'car_infos.brandTS_id', '=', 'car_brands.id') // добавляем join с таблицей car_brands
            ->select(
                'users.id as user_id',
                'users.created_at as created_data',
                'users.*', 'status_couriers.status as role_status','work_rules.name as work_rule_name', 'car_infos.*',
        'courier_info.first_name', 'courier_info.surname', 'courier_info.telegram','courier_info.sended_to_yandex',
                'car_brands.car_brand'
            )
            ->orderBy('users.created_at', 'desc') // добавляем сортировку по дате регистрации в обратном порядке
            ->paginate(10);
        $isChecked=$this->permSendApi->getPermToSendApi();
        //dd($couriers);
        return view('admin.userList', ['couriers' => $couriers, 'isChecked'=>$isChecked]);
    }

    public function showUser($id)
    {
        //общие настройки
        $statusCourier=$this->statusCourier->getAllCourierStatus();
        $workRules=$this->workRule->getEnableWorkRules();
        $carColors=$this->carColors->getAllCarColors();
        $carTransmission=$this->carTransmission->getAllCarTransmission();
        $carBrand=$this->carBrand->getAllBrandWithModel();
        $currentYear = date('Y');
        $yearsManuf = range(1970, $currentYear);
        //данные курьера
        $user = $this->user->getUserFullInfo($id);
        //dd($user);
        return view('admin.editUser', ['id' => $id,'user'=>$user,'statusCourier' => $statusCourier, 'workRules' =>$workRules, 'carColors' =>$carColors, 'carTransmission' =>$carTransmission, 'carBrand'=>$carBrand,'yearsManuf'=>$yearsManuf]);
    }
    public function send_to_yandex_change(Request $request)
    {
        $isChecked = $request->input('isChecked');
        $this->permSendApi->setAccessApiStatus($isChecked);
        return response()->json(['message' => 'Checkbox value updated successfully', 'checked' => $isChecked]);
    }


}
