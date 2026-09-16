<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'ilike', "%{$term}%")
                ->orWhere('email', 'ilike', "%{$term}%"));
        }

        $customers = $query->orderBy('name')->paginate(20);

        return view('admin.customers.index', ['customers' => $customers]);
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        $orders = $user->orders()->orderByDesc('order_date')->paginate(10);

        return view('admin.customers.show', compact('user', 'orders'));
    }
}
