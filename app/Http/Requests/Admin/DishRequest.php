<?php

namespace App\Http\Requests\admin;

use App\Enums\UserRoles;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DishRequest extends FormRequest
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
        $dishId = optional($this->route('dish'))->id;
        return [
            'name' => 'required|string|max:255', Rule::unique('dishes', 'name')->ignore($dishId),
            'description' => 'required|string',
            'dish_group_id' => 'required|integer', Rule::exists('dish_groups', 'id'),
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
            'name.unique' => __('messages.nameUnique'),

            'description.required' => __('messages.descriptionRequired'),
            'description.string' => __('messages.descriptionString'),

            'dish_group_id.required' =>  __('messages.dishGroupIdRequired'),
            'dish_group_id.integer'  => __('messages.dishGroupIdInteger'),
            'dish_group_id.exists'   => __('messages.dishGroupIdExists'),
        ];
    }
}
