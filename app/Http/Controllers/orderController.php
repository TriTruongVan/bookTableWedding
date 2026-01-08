<?php

namespace App\Http\Controllers;

use App\Enums\City;
use App\Http\Requests\admin\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaginateResource;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class orderController extends Controller
{
    public function createOrder(CreateOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $city = City::DA_NANG->value;

            $tel = $request->input('customer.tel');
            $name = $request->input('customer.name');
            $street = $request->input('customer.street');
            $ward = $request->input('customer.ward');

            $customer = Customer::updateOrCreate([
                'tel' => $tel,
            ],[
                'name' => $name,
                'street' => $street,
                'ward' => $ward,
                'city' => $city,
            ]);

            $tableCount = $request->input('soBanGia.soBan');
            $pricePerTable = $request->input('soBanGia.donGia');
            $totalAmount = (int) $tableCount * (int) $pricePerTable;

            $order = Order::create([
                'customer_id'     => $customer->id,
                'event_date'      => $request->input('schedule.solarDate'),
                'lunar_day'       => $request->input('schedule.lunarDay'),
                'lunar_month'     => $request->input('schedule.lunarMonth'),
                'lunar_year'      => $request->input('schedule.lunarYear'),
                'session'         => $request->input('schedule.session'),
                'table_count'     => $tableCount,
                'price_per_table' => $pricePerTable,
                'deposit_amount'  => $request->input('soBanGia.tienCoc') ?? 0,
                'total_amount'    => $totalAmount,
                'note'            => $request->input('note'),
            ]);
            
            $dishIds = collect($request->input('dishData', []))
                ->pluck('id')
                ->filter()
                ->toArray();

            if (!empty($dishIds)) {
                $order->dishes()->sync($dishIds);
            }
            $voucherIds = collect($request->input('voucherData', []))
                ->pluck('id')
                ->filter()
                ->toArray();

            if (!empty($voucherIds)) {
                $order->vouchers()->sync($voucherIds);
            }

            $staffSync = [];
            foreach ($request->input('staffData', []) as $item) {
                if (!isset($item['id'])) {
                    throw new \Exception('Staff item missing id');
                }

                $staffSync[$item['id']] = [
                    'event_date' => $order->event_date,
                    'session'    => $item['session'] ?? $order->session,
                ];
            }
            if (!empty($staffSync)) {
                $order->staffs()->sync($staffSync);
            }
            DB::commit();
            return $this->okRes();
        }  catch (\Throwable $th){
            DB::rollBack();
            Log::error('Create Order Error:', ['exception' => $th->getMessage()]);
            return $this->badRequestRes();
        }
    }

    public function getOrder(Request $request)
    {
        $search = $request->search;

        $query = Order::with([
            'customer',
            'dishes',
            'staffs'
        ]);
        if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%$search%")
                ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%$search%"))
                ->orWhereDate('event_date', $search);
            });
        }
        $query->orderBy('created_at', 'desc');
        $items = $query->paginate($request->size);
        return $this->okRes(PaginateResource::make($items, OrderResource::class));
    }
}
