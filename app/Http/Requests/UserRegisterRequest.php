<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use App\Rules\DateFormat;
class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100', 'min:2'],
            'surname' => ['required', 'string', 'max:100', 'min:2'],
            'patronymic' => ['nullable', 'string', 'max:100'], // Может быть пустым
            'role' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', new DateFormat('d-m-Y')],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telegram' => ['max:100'],
            'phone' => ['required', 'string', 'max:30', 'min:5','unique:users,name'],
        ];

        // Добавляем правила только если роль не pesh или velo
        if ($this->input('role') !== 'pesh' && $this->input('role') !== 'velo') {
            $rules['driverCountry'] = ['alpha', 'size:3'];
            $rules['licenceNumber'] = ['required', 'string', 'max:100', 'min:2'];
            $rules['license_issue'] = ['required', new DateFormat('d-m-Y')];
            $rules['license_expirated'] = ['required', new DateFormat('d-m-Y')];
            $rules['licencePlateNumber'] = ['required','string','max:9'];
            $rules['registrationCertificate'] = ['required','string'];
            $rules['modelTS'] = ['required', 'string'];
            $rules['brandTS'] = ['required', 'string'];
            $rules['carColor'] = ['required', 'numeric'];
            $rules['carManufactureYear'] = ['required', 'numeric', 'digits:4','between:1970,2025'];
            $rules['Transmission'] = ['required', 'numeric'];
            $rules['vin'] = ['required', 'string','size:17'];

        }
        // Добавляем правила только если роль грузовик
        if ($this->input('role') == 'gruz') {
            $rules['cargoHoldDimensionsHeight'] = ['required', 'numeric', 'between:90,250'];
            $rules['cargoHoldDimensionsLength'] = ['required', 'numeric', 'between:170,601'];
            $rules['cargoHoldDimensionsWidth'] = ['required', 'numeric', 'between:96,250'];
            $rules['cargoLoaders'] = ['required', 'numeric', 'between:0,2'];
            $rules['cargoCapacity'] = ['required'];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('custom.Login')]),
            'first_name.required' => __('validation.required', ['attribute' => __('custom.Name')]),
            'surname.required' => __('validation.required', ['attribute' => __('custom.Surname')]),
            'role.required' => __('validation.required', ['attribute' => __('custom.Role')]),
            'date_of_birth.required' => __('validation.required', ['attribute' => __('custom.Date of birth')]),
            'email.required' => __('validation.required', ['attribute' => __('custom.Email')]),
            'password.required' => __('validation.required', ['attribute' => __('custom.Password')]),
            'telegram.required' => __('validation.required', ['attribute' => __('custom.Telegram')]),
            'phone.required' => __('validation.required', ['attribute' => __('custom.Phone')]),
            'licenceNumber.required' => __('validation.required', ['attribute' => __('custom.licenceNumber')]),
            'license_issue.required' => __('validation.required', ['attribute' => __('custom.license_issue')]),
            'license_expirated.required' => __('validation.required', ['attribute' => __('custom.license_expirated')]),
            'cargoHoldDimensionsHeight.required' => __('validation.required', ['attribute' => __('custom.Height')]),
            'cargoHoldDimensionsLength.required' => __('validation.required', ['attribute' => __('custom.Length')]),
            'cargoHoldDimensionsWidth.required' => __('validation.required', ['attribute' => __('custom.Width')]),
            'cargoLoaders.required' => __('validation.required', ['attribute' => __('custom.cargoLoaders')]),
            'boosterCount.required' => __('validation.required', ['attribute' => __('custom.boosterCount')]),
            'licencePlateNumber.required' => __('validation.required', ['attribute' => __('custom.licencePlateNumber')]),
            'registrationCertificate.required' => __('validation.required', ['attribute' => __('custom.registrationCertificate')]),
            'modelTS.required' => __('validation.required', ['attribute' => __('custom.modelTS')]),
            'brandTS.required' => __('validation.required', ['attribute' => __('custom.brandTS')]),
            'carColor.required' => __('validation.required', ['attribute' => __('custom.carColor')]),
            'carManufactureYear.required' => __('validation.required', ['attribute' => __('custom.carManufactureYear')]),
            'Transmission.required' => __('validation.required', ['attribute' => __('custom.Transmission')]),
            'vin.required' => __('validation.required', ['attribute' => __('custom.vin')]),

            'phone.unique' => __('validation.unique', ['attribute' => __('custom.Phone')]),
            'registrationCertificate.size' => __('validation.size', ['attribute' => __('custom.registrationCertificate')]),
            'registrationCertificate.in' => __('validation.in', ['attribute' => __('custom.registrationCertificate')]),
            'carManufactureYear.digits' => __('validation.digits', ['attribute' => __('custom.carManufactureYear')]),
            'vin.size' => __('validation.size', ['attribute' => __('custom.vin')]),
            'carManufactureYear.between' => __('validation.between', ['attribute' => __('custom.carManufactureYear')]),


            'name.max' => __('validation.max.string', ['attribute' => __('custom.Login'), 'max' => ':max']),
            'first_name.max' => __('validation.max.string', ['attribute' => __('custom.first_name'), 'max' => ':max']),
            'surname.max' => __('validation.max.string', ['attribute' => __('custom.surname'), 'max' => ':max']),
            'patronymic.max' => __('validation.max.string', ['attribute' => __('custom.patronymic'), 'max' => ':max']),
            'email.max' => __('validation.max.string', ['attribute' => __('custom.email'), 'max' => ':max']),
            'password.max' => __('validation.max.string', ['attribute' => __('custom.password'), 'max' => ':max']),
            'telegram.max' => __('validation.max.string', ['attribute' => __('custom.telegram'), 'max' => ':max']),
            'phone.max' => __('validation.max.string', ['attribute' => __('custom.phone'), 'max' => ':max']),
            'licenceNumber.max' => __('validation.max.string', ['attribute' => __('custom.licenceNumber'), 'max' => ':max']),
            'registrationCertificate.max' => __('validation.max.string', ['attribute' => __('custom.registrationCertificate'), 'max' => ':max']),
            'modelTS.max' => __('validation.max.string', ['attribute' => __('custom.modelTS'), 'max' => ':max']),
            'brandTS.max' => __('validation.max.string', ['attribute' => __('custom.brandTS'), 'max' => ':max']),
            'licencePlateNumber.max' => __('validation.max.string', ['attribute' => __('custom.licencePlateNumber'), 'max' => ':max']),

            'name.min' => __('validation.min.string', ['attribute' => __('custom.Login'), 'min' => ':min']),
            'first_name.min' => __('validation.min.string', ['attribute' => __('custom.first_name'), 'min' => ':min']),
            'surname.min' => __('validation.min.string', ['attribute' => __('custom.surname'), 'min' => ':min']),
            'patronymic.min' => __('validation.min.string', ['attribute' => __('custom.patronymic'), 'min' => ':min']),
            'email.min' => __('validation.min.string', ['attribute' => __('custom.email'), 'min' => ':min']),
            'password.min' => __('validation.min.string', ['attribute' => __('custom.password'), 'min' => ':min']),
            'telegram.min' => __('validation.min.string', ['attribute' => __('custom.telegram'), 'min' => ':min']),
            'phone.min' => __('validation.min.string', ['attribute' => __('custom.phone'), 'min' => ':min']),
            'licenceNumber.min' => __('validation.min.string', ['attribute' => __('custom.licenceNumber'), 'min' => ':min']),
            'registrationCertificate.min' => __('validation.min.string', ['attribute' => __('custom.registrationCertificate'), 'min' => ':min']),
            'modelTS.min' => __('validation.min.string', ['attribute' => __('custom.modelTS'), 'min' => ':min']),
            'brandTS.min' => __('validation.min.string', ['attribute' => __('custom.brandTS'), 'min' => ':min']),

            'driverCountry.size' => __('validation.required', ['attribute' => __('custom.driverCountry')]),
            'cargoHoldDimensionsHeight.between' => __('validation.between', ['attribute' => __('custom.Height')]),
            'cargoHoldDimensionsLength.between' => __('validation.between', ['attribute' => __('custom.Length')]),
            'cargoHoldDimensionsWidth.between' => __('validation.between', ['attribute' => __('custom.Width')]),
            'cargoLoaders.between' => __('validation.between', ['attribute' => __('custom.cargoLoaders')]),
            'boosterCount.between' => __('validation.between', ['attribute' => __('custom.boosterCount')]),

        ];
    }

}
