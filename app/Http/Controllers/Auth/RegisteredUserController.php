<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\YandexApiController;
use App\Http\Requests\UserRegisterRequest;
use App\Models\CarBrand;
use App\Models\CarColors;
use App\Models\CarInfo;
use App\Models\CarModel;
use App\Models\CarTransmission;
use App\Models\CourierInfo;
use App\Models\ErrorsApiLog;
use App\Models\OldCar;
use App\Models\PermSendApi;
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
use App\Telegram\Handler;

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
    protected $carInfo;
    protected $permSendApi;
    protected $oldCar;

    public function __construct(StatusCourier $statusCourier, User $user, CourierInfo $courierInfo, YandexApiController $yandexApiController, WorkRule $workRule,
                                ErrorsApiLog $errorsApiLog, SuccessApiLog $successApiLog, CustomErrorsService $customErrorsService, CarColors $carColors, CarTransmission $carTransmission, CarBrand $carBrand, CarInfo $carInfo,
                                PermSendApi $permSendApi, OldCar $oldCar
    )
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
        $this->OldCar = $oldCar;
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
         //dd($request);
        // Очистка ошибок сессии перед обработкой формы
        session()->forget('errors');

        //разрешена ли отправка на АПИ
        $sendToApi=$this->permSendApi->getPermToSendApi();
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
            //если разрешена отправка по АПИ
            if($sendToApi)
            {
                $response =$this->yandexApiController->createWalkingCourier($dateOfBirth,$request->first_name,$request->surname,$request->patronymic,$phone,$workRule);
                // Если курьер успешно создан
                if ($response['status'] == 200) {
                    // Успешный ответ
                    $userInfo=  User::create([
                        'name' => $request->phone,
                        'email' =>$request->email,
                        'password' => Hash::make($request->password),
                    ]);
                    $this->courierInfo->createCourier($request,$userInfo,$roleId[0],$response['idempotency_token'],0,1);
                    $this->successApiLog->saveLog($userInfo,$response['data']['contractor_profile_id']);

                    event(new Registered($userInfo));
                    Auth::login($userInfo);
                    $handler = new Handler();
                    $handler->send_message($request->phone,$request->first_name,$request->surname,$request->patronymic, $userInfo->id);
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    $this->errorsApiLog->saveError($request,$roleId[0],$response);
                    $message=$this->customErrorsService->errorMessage($response);
                    return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
                }
            }
            //если не разрешена отправка по АПИ, просто сохраняем курьера
            else
            {
                $userInfo=  User::create([
                    'name' => $request->phone,
                    'email' =>$request->email,
                    'password' => Hash::make($request->password),
                ]);
                $this->courierInfo->createCourier($request,$userInfo,$roleId[0],null,0,0);
                event(new Registered($userInfo));
                Auth::login($userInfo);
                $handler = new Handler();
                $handler->send_message($request->phone,$request->first_name,$request->surname,$request->patronymic,$userInfo->id);
                return redirect(RouteServiceProvider::HOME);
            }
        }
        //создам авто курьера через АПИ
        if(($request->role=='moto')||($request->role=='avto')||($request->role=='gruz'))
        {
            //   dd($request);
            $phone=$this->TransformPhone($request->phone);
            $motoPhone = str_replace("+", "", $phone);
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
                $brandTS_id=0;
            }
            else
            {
                $brandTS=$this->transformBrand($request->brandTS);
                $brandTS_id=$request->brandTS;
            }
            //преобразую модель
            if($request->role=='moto')
            {
                $modelTS='Courier';
                $modelTS_id=0;
                $carManufactureYear=2022;
                $licencePlateNumber=$motoPhone;
                $registrationCertificate=$motoPhone;
            }
            else
            {
                $modelTS=$this->transformModel($request->modelTS);
                $modelTS_id=$request->modelTS;
                $carManufactureYear=$request->carManufactureYear;
                $licencePlateNumber=$request->licencePlateNumber;
                $registrationCertificate=$request->registrationCertificate;
            }
            //преобразуем роль
            $roleId=$this->statusCourier->getStatusId($request->role);
            //если разрешена отправка по АПИ
            if($sendToApi)
            {
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
                    $carManufactureYear,
                    $request->cargoHoldDimensionsHeight,
                    $request->cargoHoldDimensionsLength,
                    $request->cargoHoldDimensionsWidth,
                    $request->cargoLoaders,
                    $request->cargoCapacity,
                    $motoPhone

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
                        $this->successApiLog->saveLog($userInfo,$response['data']['contractor_profile_id']);
                        //сохранение машины
                        $creatadCarId=$this->carInfo->createCarInfo(
                            $carId,
                            0,
                            $licencePlateNumber,
                            $registrationCertificate,
                            $brandTS_id,
                            $modelTS_id,
                            $request->carColor,
                            $request->Transmission,
                            $request->vin,
                            $request->carManufactureYear,
                            $request->cargoHoldDimensionsHeight,
                            $request->cargoHoldDimensionsLength,
                            $request->cargoHoldDimensionsWidth,
                            $request->cargoLoaders,
                            $request->cargoCapacity,
                        );
                        $this->courierInfo->createCourier($request,$userInfo,$roleId[0],$response['idempotency_token'],$creatadCarId,1);
                        event(new Registered($userInfo));
                        Auth::login($userInfo);
                        $handler = new Handler();
                        $handler->send_message($request->phone,$request->first_name,$request->surname,$request->patronymic,$userInfo->id);
                        return redirect('/dashboard');
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
            //если не разрешена отправка по АПИ
            else
            {
                $creatadCarId=$this->carInfo->createCarInfo(
                    null,
                    0,
                    $licencePlateNumber,
                    $registrationCertificate,
                    $brandTS_id,
                    $modelTS_id,
                    $request->carColor,
                    $request->Transmission,
                    $request->vin,
                    $request->carManufactureYear,
                    $request->cargoHoldDimensionsHeight,
                    $request->cargoHoldDimensionsLength,
                    $request->cargoHoldDimensionsWidth,
                    $request->cargoLoaders,
                    $request->cargoCapacity,
                );
                $userInfo=  User::create([
                    'name' => $request->phone,
                    'email' =>$request->email,
                    'password' => Hash::make($request->password),
                ]);
                $this->courierInfo->createCourier($request,$userInfo,$roleId[0],null,$creatadCarId,0);
                event(new Registered($userInfo));
                Auth::login($userInfo);
                $handler = new Handler();
                $handler->send_message($request->phone,$request->first_name,$request->surname,$request->patronymic,$userInfo->id);
                return redirect(RouteServiceProvider::HOME);
            }
        }
    }

    //редактирование курьера локально
    public function editCourier(UserRegisterRequest $request)
    {
        // Очистка ошибок сессии перед обработкой формы
        session()->forget('errors');

        //преобразуем дату рождения
        $dateOfBirth=$this->TransformDataToApiView($request->date_of_birth);
        //преобразуем телефон
        $phone=$this->TransformPhone($request->phone);
        //преобразуем роль
        $roleId=$this->statusCourier->getStatusId($request->role);
        //преоразуем work_rule
        $workRule=$this->workRule->getWorkId($request->workRule);

        //редактирую user
        $this->user->updateUser($request->id,$request->phone,$request->email);
        //редактируем курьера
        $this->courierInfo->editCourierInfo($request->id,$roleId[0],$request);
        $carId=$this->courierInfo->getCarId($request->id);
        //если роль пеший то удаляем старую машину если она есть
        if(($request->role=='pesh')||($request->role=='velo'))
        {
            //если машина была
            if($carId!==0)
            {
                //удаляем у курьера
                $this->courierInfo->updateOneFieldCourier($request->id,'car_id',0);
                //заносим в таблицу старых машин
                $this->OldCar->createOldCar($request->id,$carId);
            }
        }
        //если роль авто то создаём или обновляем машину
        if(($request->role=='moto')||($request->role=='avto')||($request->role=='gruz')) {
            $phone=$this->TransformPhone($request->phone);
            $motoPhone = str_replace("+", "", $phone);
            //преобразую модель
            if($request->role=='moto')
            {
                $modelTS='Courier';
                $modelTS_id=0;
                $carManufactureYear=2022;
                $licencePlateNumber=$motoPhone;
                $registrationCertificate=$motoPhone;
                $brandTS='Bike';
                $brandTS_id=0;
            }
            else
            {
                $modelTS=$this->transformModel($request->modelTS);
                $modelTS_id=$request->modelTS;
                $carManufactureYear=$request->carManufactureYear;
                $licencePlateNumber=$request->licencePlateNumber;
                $registrationCertificate=$request->registrationCertificate;
                $brandTS=$this->transformBrand($request->brandTS);
                $brandTS_id=$request->brandTS;
            }
            //если машина была
            if($carId!==0)
            {
            //сохранение машины
            $creatadCarId = $this->carInfo->editCar(
                $carId,
                $licencePlateNumber,
                $registrationCertificate,
                $brandTS_id,
                $modelTS_id,
                $request->carColor,
                $request->Transmission,
                $request->vin,
                $request->carManufactureYear,
                $request->cargoHoldDimensionsHeight,
                $request->cargoHoldDimensionsLength,
                $request->cargoHoldDimensionsWidth,
                $request->cargoLoaders,
                $request->cargoCapacity,
            );
            }
            //если машины не было то создаём
            else
            {
                $creatadCarId=$this->carInfo->createCarInfo(
                    null,
                    0,
                    $licencePlateNumber,
                    $registrationCertificate,
                    $brandTS_id,
                    $modelTS_id,
                    $request->carColor,
                    $request->Transmission,
                    $request->vin,
                    $request->carManufactureYear,
                    $request->cargoHoldDimensionsHeight,
                    $request->cargoHoldDimensionsLength,
                    $request->cargoHoldDimensionsWidth,
                    $request->cargoLoaders,
                    $request->cargoCapacity,
                );
                $this->courierInfo->updateOneFieldCourier($request->id,'car_id',$creatadCarId);
            }
        }
//        $this->courierInfo->updateOneFieldCourier($request->id,'sended_to_yandex',0);
        return back()->with('success', 'Пользователь отредактирован');

    }
    // Метод для выхода из системы
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');  // Перенаправление на главную страницу после выхода
    }

    //отправка в яндекс ( и проверка есть ли там такой курьер ) без редактирования
    public function sendToYandex(Request $request, $id)
    {
        session()->forget('errors');
        //проверка есть ли такой курьер в Яндекс либо надо привязать нашего
        //проверка привязан ли наш курьер к яндексу
        $idempotency_token=$this->courierInfo->getToken($id);
        //получаем все данные про самого курьера
        $courierInfo= $this->user->getUserFullInfo($id);
       // dd($courierInfo);
        //если уже привязан к яндексу
        if($courierInfo->sended_to_yandex)
        {
            return back()->with('success', 'Данные пользователя УЖЕ были в Яндекс');
        }
        else
        {
            //преобразуем телефон
            $phone=$this->TransformPhone($courierInfo->name);
            if(($courierInfo->value_status=='pesh')||($courierInfo->value_status=='velo'))
            {

                //преобразуем дату рождения
                $dateOfBirth=$this->TransformDataToApiView($courierInfo->date_of_birth);

                //преобразуем роль ( тут не надо ) вместо этого $courierInfo->role_id
                //преобразуем work_rule
                $workRule=$this->workRule->getWorkId($courierInfo->work_rule_id);
                //сохраняем пешего курьера в Яндексе
                $response =$this->yandexApiController->createWalkingCourier($dateOfBirth,$courierInfo->first_name,$courierInfo->surname,$courierInfo->patronymic,$phone,$workRule, $courierInfo->idempotency_token);
                // Если курьер успешно создан
                if ($response['status'] == 200) {
                    //сохраняем токен и ставим флаг отправки в яндекс
                    $this->courierInfo->updateOneFieldCourier($id,'sended_to_yandex',1);
                    $this->courierInfo->updateOneFieldCourier($id,'idempotency_token',$response['idempotency_token']);
                    return back()->with('success', 'Данные пользователя отправлены в Яндекс');
                } else {
                    $message=$this->customErrorsService->errorMessage($response);
                    return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
                }
            }
            //если авто курьер
            if(($courierInfo->value_status=='moto')||($courierInfo->value_status=='avto')||($courierInfo->value_status=='gruz'))
            {
                //dd($courierInfo);
                $motoPhone = str_replace("+", "", $phone);
                //массив категорий
                $avtoCategories=$this->transformAvtoCategories($courierInfo->value_status);
                //преобразуем цвет
                $colorAvto=$this->transformColor($courierInfo->colorAvto_id);
                //преобразуем трансмиссию
                $transmission=$this->transformTransmission($courierInfo->transmission_id);

                //преобразую бренд
                if($courierInfo->value_status=='moto')
                {
                    $brandTS='Bike';
                    $brandTS_id=0;
                }
                else
                {
                    $brandTS=$this->transformBrand($courierInfo->brandTS_id);
                    $brandTS_id=$courierInfo->brandTS_id;
                }
                //преобразую модель
                if($request->role=='moto')
                {
                    $modelTS='Courier';
                    $modelTS_id=0;
                    $carManufactureYear=2022;
                    $licencePlateNumber=$motoPhone;
                    $registrationCertificate=$motoPhone;
                }
                else
                {
                    $modelTS=$this->transformModel($courierInfo->modelTS_id);
                    $modelTS_id=$courierInfo->modelTS_id;
                    $carManufactureYear=$courierInfo->carManufactureYear;
                    $licencePlateNumber=$courierInfo->licencePlateNumber;
                    $registrationCertificate=$courierInfo->registrationCertificate;
                }
                //преобразуем роль
                $roleId=$courierInfo->value_status;
                //создаю авто для этого курьера в яндекс
                $responseAvto =$this->yandexApiController->createCar(
                    $roleId,
                    $avtoCategories,
                    0,
                    $phone,
                    $licencePlateNumber,
                    $registrationCertificate,
                    $brandTS,
                    $modelTS,
                    $colorAvto,
                    $transmission,
                    $courierInfo->vin,
                    $carManufactureYear,
                    $courierInfo->cargoHoldDimensionsHeight,
                    $courierInfo->cargoHoldDimensionsLength,
                    $courierInfo->cargoHoldDimensionsWidth,
                    $courierInfo->cargoLoaders,
                    $courierInfo->cargoCapacity,
                    $motoPhone
                );
                //если авто усешно создано
                  if ($responseAvto['status'] == 200) {
                      $carId=$responseAvto['data']['vehicle_id'];
                      //перехожу к созданию самого авто курьера
                      //преобразуем дату рождения
                      $dateOfBirth=$courierInfo->date_of_birth;
                      //преоразуем work_rule
                      $workRule=$this->workRule->getWorkId($courierInfo->work_rule_id);
                      //преобразуем дату окончания действия водительского удостоверения
                      $license_expirated=$this->TransformDataToApiView($courierInfo->license_expirated);
                      $license_issue=$this->TransformDataToApiView($courierInfo->license_issue);
                      //получаем и преобразуем текущую дату
                      $date = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
                      // Форматируем дату в формате ISO 8601 YYYY-MM-DD
                      $hire_date = $date->format('Y-m-d');

                      $response =$this->yandexApiController->createAvtoCourier(
                          $dateOfBirth,
                          $courierInfo->first_name,
                          $courierInfo->surname,
                          $courierInfo->patronymic,
                          $phone,
                          $workRule,
                          $courierInfo->driverCountry,
                          $license_expirated,
                          $license_issue,
                          $courierInfo->licenceNumber,
                          $hire_date,
                          $carId
                      );
                      // Если курьер успешно создан
                      if ($response['status'] == 200) {
                          $this->courierInfo->updateOneFieldCourier($id,'idempotency_token',$response['idempotency_token']);
                          $this->courierInfo->updateOneFieldCourier($id,'sended_to_yandex',1);
                          return back()->with('success', 'Данные пользователя отправлены в Яндекс');
                      }
                      //ошибка при создании курьера
                      else
                      {
                          $message=$this->customErrorsService->errorMessage($response);
                          return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
                      }

                }
                //ошибка при создании авто
                else
                {
                    $message=$this->customErrorsService->errorMessageAvto($responseAvto);
                    return redirect()->back()->withInput()->withErrors(['custom_error' => $message]);
                }

            }

        }
        dd('отправлено');
    }

}
