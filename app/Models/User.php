<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function updateUser($id,$name,$email)
    {
        User::where('id', $id)->update([
            'name' =>$name,
            'email' =>$email,
        ]);
    }

    public function getUserFullInfo($id)
    {
        return User::leftJoin('courier_info', 'users.id', '=', 'courier_info.user_id')
            ->leftJoin('car_infos', 'courier_info.car_id', '=', 'car_infos.id')
            ->leftJoin('status_couriers', 'courier_info.role_id', '=', 'status_couriers.id')
            ->where('users.id', $id)
            ->select(
                'users.*',
                'courier_info.first_name',
                'courier_info.surname',
                'courier_info.patronymic',
                'courier_info.role_id',
                'courier_info.work_rule_id',
                'courier_info.date_of_birth',
                'courier_info.sended_to_yandex',
                'car_infos.licencePlateNumber',
                'car_infos.registrationCertificate',
                'status_couriers.value_status',
                'courier_info.licenceNumber',
                'courier_info.license_issue',
                'courier_info.license_expirated',
                'courier_info.driverCountry',
                'courier_info.telegram',
                'car_infos.brandTS_id',
                'car_infos.modelTS_id',
                'car_infos.colorAvto_id',
                'car_infos.carManufactureYear',
                'car_infos.transmission_id',
                'car_infos.vin',
                'car_infos.cargoHoldDimensionsHeight',
                'car_infos.cargoHoldDimensionsLength',
                'car_infos.cargoHoldDimensionsWidth',
                'car_infos.cargoCapacity',
                'car_infos.cargoLoaders',

            )
            ->first();
    }

    public function getSuperUser()
    {
        return User::where('super',1)->get();
    }

}
