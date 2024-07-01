<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\YandexApiController;
use App\Http\Requests\UserRegisterRequest;
use App\Models\CarBrand;
use App\Models\CarColors;
use App\Models\CarModel;
use App\Models\CarTransmission;
use App\Models\CourierInfo;
use App\Models\ErrorsApiLog;
use App\Models\StatusCourier;
use App\Models\SuccessApiLog;
use App\Models\User;
use App\Models\WorkRule;
use App\Providers\RouteServiceProvider;
use App\Services\CustomErrorsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use stdClass;
use App\Traits\DataFormatTrait;
class RegisteredUserController extends Controller
{
    use DataFormatTrait;
    protected $statusCourier;
    protected $user;
    protected $courierInfo;
    protected $yandexApiController;
    protected $workRule;
    protected $errorsApiLog;
    protected $successApiLog;
    protected $customErrorsService;

    public function __construct(StatusCourier $statusCourier, User $user, CourierInfo $courierInfo, YandexApiController $yandexApiController, WorkRule $workRule,
                                ErrorsApiLog $errorsApiLog, SuccessApiLog $successApiLog, CustomErrorsService $customErrorsService, CarColors $carColors, CarTransmission $carTransmission, CarBrand $carBrand)
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
    }

    public function create(): View
    {
        $statusCourier=$this->statusCourier->getAllCourierStatus();
        $workRules=$this->workRule->getEnableWorkRules();
        $carColors=$this->carColors->getAllCarColors();
        $carTransmission=$this->carTransmission->getAllCarTransmission();
        $carBrand=$this->carBrand->getAllBrandWithModel();
        $currentYear = date('Y');
        $yearsManuf = range(1970, $currentYear);
        return view('auth.register', ['statusCourier' => $statusCourier, 'workRules' =>$workRules, 'carColors' =>$carColors, 'carTransmission' =>$carTransmission, 'carBrand'=>$carBrand,'yearsManuf'=>$yearsManuf]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
      public function store(UserRegisterRequest $request): RedirectResponse
    {
       // dd($request);
        // Очистка ошибок сессии перед обработкой формы
        session()->forget('errors');

        //создаём пешего курьера через АПИ
        if(($request->role=='pesh')||($request->role=='velo'))
        {
            //преобразуем дату рождения
            $dateOfBirth=$this->TransformDataToApiView($request->date_of_birth);
            //преобразуем телефон
            $phone=$this->TransformPhone($request->phone);
            //преобразуем роль
            $roleId=$this->statusCourier->getStatusId($request->role);
            //преоразуем work_rule
            $workRule=$this->workRule->getWorkId($request->workRule);
            $response =$this->yandexApiController->createWalkingCourier($dateOfBirth,$request->first_name,$request->surname,$request->patronymic,$phone,$workRule);
            // Если курьер успешно создан
            if ($response['status'] == 200) {
                // Успешный ответ
                $userInfo=  User::create([
                    'name' => $request->phone,
                    'email' =>$request->email,
                    'password' => Hash::make($request->password),
                ]);
                $this->courierInfo->createCourier($request,$userInfo,$roleId[0],$response['idempotency_token']);
                $this->successApiLog->saveLog($userInfo,$response['data']['contractor_profile_id']);

                event(new Registered($userInfo));
                Auth::login($userInfo);
                return redirect(RouteServiceProvider::HOME);
            } else {
                $this->errorsApiLog->saveError($request,$roleId[0],$response);
                $message=$this->customErrorsService->errorMessage($response);
                return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
            }
        }
        //создам авто курьера через АПИ
         if(($request->role=='moto')||($request->role=='avto')||($request->role=='gruz'))
        {
         //   dd($request);
            $phone=$this->TransformPhone($request->phone);
            //массив категорий
            $avtoCategories=$this->transformAvtoCategories($request->role);
            //преобразуем цвет
            $colorAvto=$this->transformColor($request->carColor);
            //преобразуем трансмиссию
            $transmission=$this->transformTransmission($request->Transmission);
            //преобразую бренд
            if($request->role=='moto')
            {
                $brandTS='Bike';
            }
            else
            {
                $brandTS=$this->transformBrand($request->brandTS);
            }
            //преобразую модель
            if($request->role=='moto')
            {
                $modelTS='Courier';
            }
            else
            {
                $modelTS=$this->transformModel($request->modelTS);
            }
            //преобразуем роль
            $roleId=$this->statusCourier->getStatusId($request->role);
            //создаю авто для этого курьера
            $responseAvto =$this->yandexApiController->createCar(
                $request->role,
                $avtoCategories,
                $request->boosterCount,
                $phone,
                $request->licencePlateNumber,
                $request->registrationCertificate,
                $brandTS,
                $modelTS,
                $colorAvto,
                $transmission,
                $request->vin,
                $request->carManufactureYear,
                $request->cargoHoldDimensionsHeight,
                $request->cargoHoldDimensionsLength,
                $request->cargoHoldDimensionsWidth,
                $request->cargoLoaders,
                $request->cargoCapacity,

            );
          //  dd($responseAvto);
            //если авто усешно создано
            if ($responseAvto['status'] == 200) {
               // dd($responseAvto);
                $carId=$responseAvto['data']['vehicle_id'];
                //перехожу к созданию самого авто курьера
                //преобразуем дату рождения
                $dateOfBirth=$this->TransformDataToApiView($request->date_of_birth);
                //преоразуем work_rule
                $workRule=$this->workRule->getWorkId($request->workRule);
                //преобразуем дату окончания действия водительского удостоверения
                $license_expirated=$this->TransformDataToApiView($request->license_expirated);
                $license_issue=$this->TransformDataToApiView($request->license_issue);
                //получаем и преобразуем текущую дату
                $date = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
                // Форматируем дату в формате ISO 8601 YYYY-MM-DD
                $hire_date = $date->format('Y-m-d');
                $response =$this->yandexApiController->createAvtoCourier(
                    $dateOfBirth,
                    $request->first_name,
                    $request->surname,
                    $request->patronymic,
                    $phone,
                    $workRule,
                    $request->driverCountry,
                    $license_expirated,
                    $license_issue,
                    $request->licenceNumber,
                    $hire_date,
                    $carId
                );
                // Если курьер успешно создан
                if ($response['status'] == 200) {
                    // Успешный ответ
                    $userInfo=  User::create([
                        'name' => $request->phone,
                        'email' =>$request->email,
                        'password' => Hash::make($request->password),
                    ]);
                    $this->courierInfo->createCourier($request,$userInfo,$roleId[0],$response['idempotency_token']);
                    $this->successApiLog->saveLog($userInfo,$response['data']['contractor_profile_id']);
                    //сохранение машины добавить

                    event(new Registered($userInfo));
                    Auth::login($userInfo);
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    //ошибка создания курьера
                    $this->errorsApiLog->saveError($request,$roleId[0],$response);
                    $message=$this->customErrorsService->errorMessage($response);
                    return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
                }
            } else {
               // dd($responseAvto);

                //ошибка создания авто
                $this->errorsApiLog->saveError($request,$roleId[0],$responseAvto);
                $message=$this->customErrorsService->errorMessageAvto($responseAvto);
                return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
            }
        }
    }


}
