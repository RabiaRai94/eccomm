<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Sample data, replace with actual queries from your database
        $data = [
            'totalRevenue' => 25000,
            'orders' => 320,
            'totalProducts' => 150,
            'customers' => 2000,
            'recentOrders' => [
                ['id' => '001', 'customer' => 'John Doe', 'amount' => 120, 'status' => 'Completed', 'date' => '2023-01-15'],
                ['id' => '002', 'customer' => 'Jane Smith', 'amount' => 80, 'status' => 'Pending', 'date' => '2023-01-17'],
                ['id' => '003', 'customer' => 'Mike Johnson', 'amount' => 60, 'status' => 'Cancelled', 'date' => '2023-01-20'],
            ],
        ];

        return view('dashboard.home', compact('data'));
    }
}
