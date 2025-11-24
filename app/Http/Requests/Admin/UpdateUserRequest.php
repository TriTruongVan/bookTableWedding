<?php

namespace App\Http\Requests\admin;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == UserRoles::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = optional($this->route('user'))->id ?? auth()->id();
        return [
            'name' => 'required|string|max:255',
            'tel' => [
                'required',
                'string',
                'max:20',
                'regex:/^0\d{0,10}$/',
                Rule::unique(User::class)->ignore($userId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.nameRequired'),
            'name.string' => __('messages.nameString'),
            'name.max' => __('messages.nameMax'),
            'tel.required' => __('messages.telRequired'),
            'tel.string' => __('messages.telString'),
            'tel.max' => __('messages.telMax'),
            'tel.regex' => __('messages.telRegex'),
            'tel.unique' => __('messages.telUnique')
        ];
    }
}


