<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\PaginateResource;
use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
}
