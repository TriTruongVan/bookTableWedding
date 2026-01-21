<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === UserRoles::Admin;
    }

    public function rules(): array
    {
        return [
            'customer'               => 'required|array',
            'customer.name'          => 'required|string|max:255',
            'customer.tel'           => 'required|string|regex:/^0[1-9][0-9]{8}$/',
            'customer.ward'          => 'required|integer|between:1,26',
            'customer.street'        => 'nullable|string|max:255',

            'schedule'               => 'required|array',
            'schedule.solarDate'     => 'required|date',
            'schedule.session'       => ['required', Rule::in(['trua', 'chieu'])],

            'soBanGia'               => 'required|array',
            'soBanGia.soBan'         => 'required|integer|min:1|max:1000',
            'soBanGia.donGia'        => 'required|numeric|min:0',
            'soBanGia.tienCoc'       => 'nullable|numeric|min:0',

            'note'                   => 'nullable|string|max:1000',

            'dishData'               => 'nullable|array',
            'dishData.*.id'          => 'required_with:dishData|integer|exists:dishes,id',

            'voucherData'            => 'nullable|array',
            'voucherData.*.id'       => 'nullable|string|exists:vouchers,id',


            'staffData'              => 'nullable|array',
            'staffData.*.id'         => 'required_with:staffData|integer|exists:staff,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer.name.required'      => __('messages.nameRequired'),
            'customer.tel.required'       => __('messages.telRequired'),
            'customer.tel.regex'          => __('messages.telRegex'),

            'schedule.solarDate.required' => __('messages.scheduleSolarDateRequired'),
            'schedule.session.required'   => __('messages.scheduleSessionRequired'),
            'schedule.session.in'         => __('messages.scheduleSessionInvalid'),

            'soBanGia.soBan.required'     => __('messages.soBanRequired'),
            'soBanGia.soBan.min'          => __('messages.soBanMin'),
            'soBanGia.donGia.required'    => __('messages.donGiaRequired'),

            'dishData.min'                => __('messages.dishDataMin'),

            'staffData.*.id.required'     => __('messages.staffIdRequired'),
            'staffData.*.id.exists'       => __('messages.staffIdExists'),
        ];
    }
}
