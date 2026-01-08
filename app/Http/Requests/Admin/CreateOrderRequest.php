<?php

namespace App\Http\Requests\admin;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === UserRoles::Admin;
    }

    public function rules(): array
    {
        return [
            'customer.name'   => 'required|string|max:255',
            'customer.tel'    => 'required|string|regex:/^0[1-9][0-9]{8}$/',
            'customer.street' => 'nullable|string|max:255',
            'customer.ward'   => 'required|numeric|between:1,26',

            'schedule.solarDate' => 'required|date|after_or_equal:' . now()->toDateString(),
            'schedule.lunarDay'  => 'nullable|integer|min:1|max:30',
            'schedule.lunarMonth'=> 'nullable|integer|min:1|max:12',
            'schedule.lunarYear' => 'nullable|integer|min:1900|max:2100',
            'schedule.session'   => ['required', Rule::in(['trưa', 'chiều'])],

            'soBanGia.soBan'   => 'required|integer|min:1|max:1000',
            'soBanGia.donGia'  => 'required|numeric|min:0',
            'soBanGia.tienCoc' => 'nullable|numeric|min:0',
            
            'note' => 'nullable|string',

            'dishData'        => 'required|array|min:1',
            'dishData.*.id'   => 'required|integer|exists:dishes,id',

            'voucherData'        => 'nullable|array',
            'voucherData.*.id' => [
                'required',
                'string',
                Rule::exists('vouchers', 'id'),
            ],

            'staffData'              => 'nullable|array',
            'staffData.*.id'         => 'required|integer|exists:staff,id',
            'staffData.*.session'    => ['nullable', Rule::in(['trưa', 'chiều'])],
        ];
    }

    public function messages(): array
    {
        return [
            'customer.name.required'      => __('messages.nameRequired'),
            'customer.tel.required'       => __('messages.telRequired'),
            'customer.tel.regex'          => __('messages.telRegex'),

            'schedule.solarDate.required' => __('messages.scheduleSolarDateRequired'),
            'schedule.solarDate.after_or_equal' => __('messages.scheduleSolarDateRafter_or_equal'),
            'schedule.session.required'   => __('messages.scheduleSessionRequired'),

            'soBanGia.soBan.required'     => __('messages.soBanRequired'),
            'soBanGia.soBan.min'          => __('messages.soBanMin'),
            'soBanGia.donGia.required'    => __('messages.donGiaRequired'),

            'dishData.required'           => __('messages.dishDataRequired'),
            'dishData.min'                => __('messages.dishDataMin'),

            'staffData.*.id.required'     => __('messages.staffIdRequired'),
            'staffData.*.id.exists'       => __('messages.staffIdExists'),
        ];
    }
}