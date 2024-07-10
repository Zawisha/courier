<?php

namespace App\Http\Controllers;

use App\Models\PermSendApi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    protected $permSendApi;

    public function __construct(PermSendApi $permSendApi)
    {
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
        'courier_info.first_name', 'courier_info.surname', 'courier_info.telegram',
                'car_brands.car_brand'
            ) // перечислите все столбцы, кроме id
            ->paginate(10);
        $isChecked=$this->permSendApi->getPermToSendApi();
        //dd($perm);
        return view('admin.userList', ['couriers' => $couriers, 'isChecked'=>$isChecked]);
    }

    public function showUser($id)
    {

        return view('admin.editUser', ['id' => $id]);
    }
    public function send_to_yandex_change(Request $request)
    {
        $isChecked = $request->input('isChecked');
        $this->permSendApi->setAccessApiStatus($isChecked);
        return response()->json(['message' => 'Checkbox value updated successfully', 'checked' => $isChecked]);
    }

}
