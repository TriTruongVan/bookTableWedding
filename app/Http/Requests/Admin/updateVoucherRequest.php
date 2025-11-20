<?php

namespace App\Http\Requests\admin;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateVoucherRequest extends FormRequest
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
            'code' => 'required|string|max:50|regex:/^[A-Z0-9_]+$/',
            'description' => 'nullable|string',
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'conditions' => 'required|array',
            'conditions.min_tables' => 'required|integer|min:1',

            'is_active' => [
                'required',
            ],
        ];
    }

    public function messages():array
    {
        return [
            'code.required' => __('messages.codeRequest'),
            'code.regex' => __('messages.codeRegex'),
            
            'name.required' => __('messages.nameRequired'),

            'conditions.required' => __('messages.conditionsRequired'),
            'conditions.min_tables.required' => __('messages.conditionsMinTableRequired'),
            'conditions.min_tables.integer' => __('messages.conditionsMinTableInteger'),
            'conditions.min_tables.min' => __('messages.conditionsMinTableMin'),

            'is_active.required' => __('messages.isActiveRequired'),
        ];
    }
}
