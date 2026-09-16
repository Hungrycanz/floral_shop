<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::active()->count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'placed')->count(),
            'totalUsers' => User::where('role', 'customer')->count(),
            'recentOrders' => Order::with('user')->orderByDesc('order_date')->limit(10)->get(),
        ]);
    }
}
