<?php

namespace App\Http\Controllers;

use App\Enums\City;
use App\Http\Requests\Admin\CreateStaffRequest;
use App\Http\Requests\Admin\UpdateStaffRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function getAll (Request $request)
    {
        $query = Staff::query();

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
        return $this->okRes(PaginateResource::make($customer, StaffResource::class));
    }

    public function searchStaff(Request $request)
    {
        $search = $request->search;

        $query = Staff::query();
        $query->where(function ($subQuery) use ($search){
            $subQuery->where("tel", "LIKE", "%{$search}%")
                ->orWhere("name", "LIKE", "%{$search}%");
        });
        $items = $query->get(["id", "tel", "name", "street", "ward", "city"]);
        return $this->okRes(compact("items"));
    }
    
    public function createStaff(CreateStaffRequest $request)
    {
        try {
            DB::beginTransaction();

            $staff = Staff::create([
                'name' => $request->name,
                'street' => $request->street,
                'ward' => $request->ward,
                'city' => City::DA_NANG->value,
                'tel' => $request->tel,
                'status' => $request->status,
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Create Customer Error:', ['exception' => $th->getMessage()]);

            return $this->badRequestRes();
        }
    }

    public function getById(Staff $staff)
    {
        return $this->okRes(StaffResource::make($staff));
    }

    public function updateStaff(UpdateStaffRequest $request, Staff $staff)
    {
        try {
            DB::beginTransaction();
            $staff->update([
                'name' => $request->name,
                'street' => $request->street,
                'ward' => $request->ward,
                'tel' => $request->tel,
                'status' => $request->status,
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update staff Error:', ['exception' => $th->getMessage()]);
            return $this->badRequestRes();
        }
    }
}
