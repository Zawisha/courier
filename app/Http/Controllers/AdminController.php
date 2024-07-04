<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listUsers()
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
       // dd($couriers);
        return view('admin.userList', ['couriers' => $couriers]);
    }
}
