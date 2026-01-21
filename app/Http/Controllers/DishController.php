<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\DishRequest;
use App\Http\Resources\DishResource;
use App\Models\Dish;
use App\Models\DishGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DishController extends Controller
{
    public function getAllDishGroup()
    {
        $items = DishGroup::get(['id', 'name']);

        return $this->okRes(compact('items'));
    }

    public function getDish(Request $request) 
    {
        $search = $request->search;
        $query = Dish::query()->with('dishGroup');
        if (!empty($search)) {
            $query->where('name', 'LIKE', "%$search%");
        }
        $dish = $query->get();
        return $this->okRes(DishResource::collection($dish));
    }

    public function getById(Dish $dish)
    {
        return $this->okRes(DishResource::make($dish));
    }

    public function updateDish(DishRequest $request, Dish $dish)
    {
        try {
            DB::beginTransaction();
            $dish->update([
                'name' => $request->name,
                'description' => $request->name,
                'dish_group_id' => $request->dish_group_id,
            ]);
            DB::commit();
            return $this->okRes();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update dish Error:', ['exception' => $th->getMessage()]);
            return $this->badRequestRes();
        }
    }
}
