<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // عرض كل الطلبات
    public function index(Request $request)
    {
        $query = Order::with('user');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // بحث برقم الطلب أو اسم العميل
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $statuses = Order::statuses();

        // إحصائيات سريعة
        $stats = [
            'total' => Order::count(),
            'pending' => Order::pending()->count(),
            'processing' => Order::processing()->count(),
            'today' => Order::today()->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statuses', 'stats'));
    }

    // عرض تفاصيل طلب
    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    // تحديث حالة الطلب
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        return back()->with('success', "تم تغيير الحالة من '{$order->statusLabel()}' بنجاح");
    }

    // حذف طلب
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', '🗑️ تم حذف الطلب بنجاح');
    }
}
