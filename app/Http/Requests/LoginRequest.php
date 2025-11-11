<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tel' => 'required|string|max:20|regex:/^0\d{0,10}$/',
            'password' => 'required',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tel.required' => __('messages.telRequired'),
            'tel.regex' => __('messages.telRegex'),
            'password.required' => __('messages.passwordRequired'),
        ];
    }
}
