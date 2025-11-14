<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRoles;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role === UserRoles::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tel' => [
                'required',
                'string',
                'max:20',
                'regex:/^0\d{0,10}$/',
                Rule::unique(Customer::class),
            ],
            'street' => 'required|string',
            'ward' => 'required|numeric|between:1,26',
            'role' => 'required|numeric|in:0,1',
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
            'name.required' => __('messages.nameRequired'),
            'name.string' => __('messages.nameString'),
            'name.max' => __('messages.nameMax'),
            'tel.required' => __('messages.telRequired'),
            'tel.string' => __('messages.telString'),
            'tel.max' => __('messages.telMax'),
            'tel.regex' => __('messages.telRegex'),
            'tel.unique' => __('messages.telUnique'),
            'street.required' => __('messages.streetRequired'),
            'street.string' => __('messages.streetString'),
            'ward.required' => __('messages.wardRequired'),
            'ward.numeric' => __('messages.wardNumeric'),
            'role.required' => __('messages.roleRequired'),
            'role.numeric' => __('messages.roleNumeric'),
            'role.in' => __('messages.roleIn'),
        ];
    }
}
