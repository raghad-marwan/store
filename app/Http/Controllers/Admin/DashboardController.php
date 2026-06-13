<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ إحصائيات حقيقية من قاعدة البيانات
        $stats = [
            'sales_today' => Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            'new_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'user')->count(),
            'avg_rating' => number_format(Review::avg('rating') ?? 0, 1),
        ];

        // ✅ المنتجات الأكثر مبيعاً
        $topProducts = Product::orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        // ✅ أحدث الطلبات
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // ✅ آخر التقييمات
        $recentReviews = Review::with('user', 'product')
            ->latest()
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'topProducts',
            'recentOrders',
            'recentReviews'
        ));
    }
}
