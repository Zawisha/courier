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
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'first_name' => ['required', 'string', 'max:100', 'min:2'],
            'surname' => ['required', 'string', 'max:100', 'min:2'],
            'patronymic' => ['nullable', 'string', 'max:100'], // Может быть пустым
            'role' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', new DateFormat('d-m-Y')],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telegram' => ['required', 'string', 'max:100', 'min:2'],
            'phone' => ['required', 'string', 'max:30', 'min:5'],
        ];

        // Добавляем правила только если роль не pesh или velo
        if ($this->input('role') !== 'pesh' && $this->input('role') !== 'velo') {
            $rules['licenceNumber'] = ['required', 'string', 'max:100', 'min:2'];
            $rules['license_issue'] = ['required', new DateFormat('d-m-Y')];
            $rules['license_expirated'] = ['required', new DateFormat('d-m-Y')];
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

            'name.max' => __('validation.max.string', ['attribute' => __('custom.Login'), 'max' => ':max']),
            'first_name.max' => __('validation.max.string', ['attribute' => __('custom.first_name'), 'max' => ':max']),
            'surname.max' => __('validation.max.string', ['attribute' => __('custom.surname'), 'max' => ':max']),
            'patronymic.max' => __('validation.max.string', ['attribute' => __('custom.patronymic'), 'max' => ':max']),
            'email.max' => __('validation.max.string', ['attribute' => __('custom.email'), 'max' => ':max']),
            'password.max' => __('validation.max.string', ['attribute' => __('custom.password'), 'max' => ':max']),
            'telegram.max' => __('validation.max.string', ['attribute' => __('custom.telegram'), 'max' => ':max']),
            'phone.max' => __('validation.max.string', ['attribute' => __('custom.phone'), 'max' => ':max']),
            'licenceNumber.max' => __('validation.max.string', ['attribute' => __('custom.licenceNumber'), 'max' => ':max']),

            'name.min' => __('validation.min.string', ['attribute' => __('custom.Login'), 'min' => ':min']),
            'first_name.min' => __('validation.min.string', ['attribute' => __('custom.first_name'), 'min' => ':min']),
            'surname.min' => __('validation.min.string', ['attribute' => __('custom.surname'), 'min' => ':min']),
            'patronymic.min' => __('validation.min.string', ['attribute' => __('custom.patronymic'), 'min' => ':min']),
            'email.min' => __('validation.min.string', ['attribute' => __('custom.email'), 'min' => ':min']),
            'password.min' => __('validation.min.string', ['attribute' => __('custom.password'), 'min' => ':min']),
            'telegram.min' => __('validation.min.string', ['attribute' => __('custom.telegram'), 'min' => ':min']),
            'phone.min' => __('validation.min.string', ['attribute' => __('custom.phone'), 'min' => ':min']),
            'licenceNumber.min' => __('validation.min.string', ['attribute' => __('custom.licenceNumber'), 'min' => ':min']),


        ];
    }

}
