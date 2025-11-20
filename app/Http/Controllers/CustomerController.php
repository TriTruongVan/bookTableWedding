<?php

namespace App\Http\Controllers;

use App\Enums\City;
use App\Enums\Ward;
use App\Http\Requests\Admin\CreateCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\PaginateResource;
use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    public function getAllCustomer(Request $request){
        $query = Customer::query();

        $sortBy = $request->input('sortBy', 'created_at');
        $sortDir = strtoupper($request->input('sortDir', 'DESC'));
        if (!in_array($sortDir, ['ASC', 'DESC'])) {
            $sortDir = 'DESC';
        }

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortDir);
                break;
            case 'tel':
                $query->orderBy('tel', $sortDir);
                break;
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }

        $customer = $query->paginate($request->size);
        return $this->okRes(PaginateResource::make($customer, CustomerResource::class));
    }

    public function searchCustomer(Request $request) 
    {
        $search = $request->search;

        $query = Customer::query();
        $query->where(function ($subQuery) use ($search){
            $subQuery->where("tel", "LIKE", "%{$search}%")
                ->orWhere("name", "LIKE", "%{$search}%");
        });
        $items = $query->get(["id", "tel", "name", "street", "ward", "city"]);
        return $this->okRes(compact("items"));
    }

    public function createCustomer(CreateCustomerRequest $request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'name' => $request->name,
                'street' => $request->street,
                'ward' => $request->ward,
                'city' => City::DA_NANG->value,
                'tel' => $request->tel,
                'role' => $request->role,
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Create Customer Error:', ['exception' => $th->getMessage()]);

            return $this->badRequestRes();
        }
    }

    public function getAllWards()
    {
        $wards = collect(Ward::cases())->map(function ($ward) {
            return [
                'id' => $ward->value,
                'label' => $ward->getLabel()
            ];
        });
        return $this->okRes(compact('wards'));
    }

    public function getById(Customer $customer)
    {
        return $this->okRes(CustomerResource::make($customer));
    }

    public function updateCustomer(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            DB::beginTransaction();
            $customer->update([
                'name' => $request->name,
                'street' => $request->street,
                'ward' => $request->ward,
                'tel' => $request->tel,
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update Customer Error:', ['exception' => $th->getMessage()]);
            return $this->badRequestRes();
        }
    }
}
