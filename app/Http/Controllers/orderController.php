<?php

namespace App\Http\Controllers;

use App\Enums\City;
use App\Http\Requests\Admin\CreateOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaginateResource;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ], [
                'name' => $name,
                'street' => $street,
                'ward' => $ward,
                'city' => $city,
            ]);

            $tableCount     = (int) $request->input('soBanGia.soBan');
            $pricePerTable  = (int) $request->input('soBanGia.donGia');
            $depositAmount  = (int) ($request->input('soBanGia.tienCoc') ?? 0);

            $subtotal       = $tableCount * $pricePerTable;
            $depositAmount  = min($depositAmount, $subtotal);
            $totalAmount    = max(0, $subtotal - $depositAmount);


            $order = Order::create([
                'customer_id'     => $customer->id,
                'event_date'      => $request->input('schedule.solarDate'),
                'lunar_day'       => $request->input('schedule.lunarDay'),
                'lunar_month'     => $request->input('schedule.lunarMonth'),
                'lunar_year'      => $request->input('schedule.lunarYear'),
                'session'         => $request->input('schedule.session'),
                'isLeapMonth'     => $request->input('schedule.isLeapMonth'),
                'table_count'     => $tableCount,
                'price_per_table' => $pricePerTable,
                'deposit_amount'  => $depositAmount,
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
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Create Order Error:', ['exception' => $th->getMessage()]);
            return $this->badRequestRes();
        }
    }

    public function getOrder(Request $request)
    {
        $search = $request->search;
        $staffId = $request->staff_id;

        $query = Order::with([
            'vouchers',
            'customer',
            'dishes',
            'staffs'
        ]);
        if ($staffId) {
            $query->whereHas('staffs', function ($q) use ($staffId) {
                $q->where('staff_id', $staffId);
            });
        }
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

    public function getById(Order $order)
    {
        return $this->okRes(OrderResource::make($order));
    }

    public function updateOrder(UpdateOrderRequest $request, Order $order)
    {
        Log::info('UPDATE ORDER PAYLOAD', $request->all());
        try {
            DB::beginTransaction();
            $city = City::DA_NANG->value;

            $customer = Customer::updateOrCreate(
                ['tel' => $request->input('customer.tel')],
                [
                    'name'   => $request->input('customer.name'),
                    'street' => $request->input('customer.street'),
                    'ward'   => $request->input('customer.ward'),
                    'city'   => $city,
                ]
            );

            $order->orderAddresses()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'street' => $customer->street,
                    'ward'   => $customer->ward,
                    'city'   => $city,
                ]
            );

            $tableCount     = (int) $request->input('soBanGia.soBan');
            $pricePerTable  = (int) $request->input('soBanGia.donGia');
            $depositAmount  = (int) ($request->input('soBanGia.tienCoc') ?? 0);

            $subtotal    = $tableCount * $pricePerTable;
            $depositAmount = min($depositAmount, $subtotal);
            $totalAmount = max(0, $subtotal - $depositAmount);

            $order->update([
                'customer_id'     => $customer->id,
                'event_date'      => $request->input('schedule.solarDate'),
                'lunar_day'       => $request->input('schedule.lunarDay'),
                'lunar_month'     => $request->input('schedule.lunarMonth'),
                'lunar_year'      => $request->input('schedule.lunarYear'),
                'session'         => $request->input('schedule.session'),
                'isLeapMonth'     => $request->input('schedule.isLeapMonth'),
                'table_count'     => $tableCount,
                'price_per_table' => $pricePerTable,
                'deposit_amount'  => $depositAmount,
                'total_amount'    => $totalAmount,
                'note'            => $request->input('note'),
            ]);

            /** ======================
             *  DISH
             *  ====================== */
            if ($request->has('dishData')) {
                $dishIds = collect($request->dishData)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                if (!empty($dishIds)) {
                    $order->dishes()->sync($dishIds);
                }
            }

            /** ======================
             *  VOUCHER
             *  ====================== */
            if ($request->has('voucherData')) {
                $voucherIds = collect($request->voucherData)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $order->vouchers()->sync($voucherIds);
            }

            /** ======================
             *  STAFF (PIVOT)
             *  ====================== */
            if ($request->has('staffData')) {
                $staffSync = [];
                foreach ($request->staffData as $item) {
                    if (!isset($item['id'])) continue;

                    $staffSync[$item['id']] = [
                        'event_date' => $order->event_date,
                        'session'    => $item['session'] ?? $order->session,
                    ];
                }
                if (!empty($staffSync)) {
                    $order->staffs()->sync($staffSync);
                }
            }
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update Order Error:', [
                'order_id' => $order->id,
                'error'    => $th->getMessage()
            ]);
            return $this->badRequestRes();
        }
    }
}
