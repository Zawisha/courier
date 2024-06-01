<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\YandexApiController;
use App\Http\Requests\UserRegisterRequest;
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
                                ErrorsApiLog $errorsApiLog, SuccessApiLog $successApiLog, CustomErrorsService $customErrorsService)
    {
        $this->statusCourier = $statusCourier;
        $this->user = $user;
        $this->courierInfo = $courierInfo;
        $this->yandexApiController = $yandexApiController;
        $this->workRule = $workRule;
        $this->errorsApiLog = $errorsApiLog;
        $this->successApiLog = $successApiLog;
        $this->customErrorsService = $customErrorsService;
    }

    public function create(): View
    {
        $statusCourier=$this->statusCourier->getAllCourierStatus();
        $workRules=$this->workRule->getEnableWorkRules();
        return view('auth.register', ['statusCourier' => $statusCourier, 'workRules' =>$workRules]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
      public function store(UserRegisterRequest $request): RedirectResponse
    {
        // Очистка ошибок сессии перед обработкой формы
        session()->forget('errors');
        //создаём пешего курьера через АПИ
        //$this->mainTransform($request);
        //преобразуем дату рождения
        $dateOfBirth=$this->TransformDataToApiView($request->date_of_birth);
        //преобразуем телефон
        $phone=$this->TransformPhone($request->phone);
        //преобразуем роль
        $roleId=$this->statusCourier->getStatusId($request->role);
        $response =$this->yandexApiController->createWalkingCourier($dateOfBirth,$request->first_name,$request->surname,$request->patronymic,$phone);
        // Если курьер успешно создан
        if ($response['status'] == 200) {
            // Успешный ответ
            $userInfo=  User::create([
                'name' => $request->name,
                'email' =>$request->email,
                'password' => Hash::make($request->password),
            ]);
            $this->courierInfo->createCourier($request,$userInfo,$roleId[0]);
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

    //        $license_issue=$this->TransformDataToApiView($request->license_issue);
//        $license_expirated=$this->TransformDataToApiView($request->license_expirated);

}