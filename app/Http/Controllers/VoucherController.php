<?php

namespace App\Http\Controllers;

use App\Enums\VoucherStatus;
use App\Http\Requests\Admin\createVoucherRequest;
use App\Http\Requests\admin\updateVoucherRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function getAllVoucher(Request $request)
    {
        $vouchers = Voucher::where('is_active', VoucherStatus::ACTIVE)->get();

        return $this->okRes(['items' => VoucherResource::collection($vouchers)]);
    }

    public function get(Request $request)
    {
        $search = $request->search;
        $query = Voucher::withCount(['orders as usageCount'])
            ->where('code', 'LIKE', "%$search%")
            ->orWhere('name', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%")
            ->orWhere('conditions', 'LIKE', "%$search%");

        if (in_array($request->is_active, [VoucherStatus::ACTIVE->value, VoucherStatus::INACTIVE->value])) {
            $query->where('is_active', $request->is_active);
        }

        $items = $query->paginate($request->size);

        return $this->okRes(PaginateResource::make($items, VoucherResource::class));
    }

    public function createVoucher(createVoucherRequest $request)
    {
        try {
            DB::beginTransaction();

            $voucher = Voucher::create([
                'code' => $request->code,
                'name' => $request->name,
                'conditions' => $request->conditions,
                'description' => $request->description,
                'is_active' => VoucherStatus::tryFrom($request->is_active)
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Create Customer Lỗi:', ['exception' => $th->getMessage()]);

            return $this->badRequestRes();
        }
    }

    public function getById(Voucher $voucher)
    {
        return $this->okRes(VoucherResource::make($voucher));
    }

    public function updateVoucher(updateVoucherRequest $request, Voucher $voucher)
    {
        try {
            DB::beginTransaction();
            $voucher->update([
                'code' => $request->code,
                'name' => $request->name,
                'conditions' => $request->conditions,
                'description' => $request->description,
                'is_active' => VoucherStatus::tryFrom($request->is_active)
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update Voucher Lỗi:', ['exception' => $th->getMessage()]);

            return $this->badRequestRes();
        }
    }
}
