<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, [UserRoles::Admin]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|string|min:6|same:password',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => __('messages.passwordRequired'),
            'password.string' => __('messages.passwordString'),
            'password.min' => __('messages.passwordMin'),
            'confirmPassword.required' => __('messages.confirmPasswordRequired'),
            'confirmPassword.string' => __('messages.confirmPasswordString'),
            'confirmPassword.min' => __('messages.confirmPasswordMin'),
            'confirmPassword.same' => __('messages.confirmPasswordSame'),
        ];
    }
}
