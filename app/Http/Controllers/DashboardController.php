<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardSummary()
    {
        $totalCustomer = Customer::count();
        $totalRevenue = Order::sum('total_amount');
        $totalTable = Order::sum('table_count');

        return $this->okRes([
            'total_customer' => $totalCustomer,
            'total_revenue' => $totalRevenue,
            'total_table' => $totalTable
        ]);
    }
}
