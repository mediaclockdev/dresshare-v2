<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\BaseApiRequest;


class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'            => ['required', 'string', 'max:255'],
            'last_name'             => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile'                => ['required', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            // password_confirmation is validated by "confirmed"
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email address is already registered with us.',
        ];
    }
}
