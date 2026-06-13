<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        // بحث بالاسم أو الإيميل
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::where('role', 'user')->count(),
            'new_today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
            'with_orders' => User::where('role', 'user')->whereHas('orders')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load('orders', 'reviews');
        return view('admin.customers.show', compact('customer'));
    }
}
