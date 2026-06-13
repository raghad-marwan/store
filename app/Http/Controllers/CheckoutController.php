<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', Str::uuid());
        }
        return session()->get('cart_session_id');
    }

    // عرض صفحة إتمام الشراء
    public function index()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartItems = Cart::when($userId, function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        })
            ->when(!$userId, function ($q) use ($sessionId) {
                return $q->where('session_id', $sessionId);
            })
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة!');
        }

        $total = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    // تخزين الطلب
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $sessionId = $this->getSessionId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartItems = Cart::when($userId, function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        })
            ->when(!$userId, function ($q) use ($sessionId) {
                return $q->where('session_id', $sessionId);
            })
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة!');
        }

        $total = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        // إنشاء الطلب
        $order = Order::create([
            'user_id' => $userId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'total' => $total,
            'status' => 'pending',
        ]);

        // إضافة المنتجات للطلب
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'منتج',
                'price' => $item->product->price ?? 0,
                'quantity' => $item->quantity,
                'subtotal' => ($item->product->price ?? 0) * $item->quantity,
                'total' => ($item->product->price ?? 0) * $item->quantity,
            ]);
        }

        // تفريغ السلة
        Cart::when($userId, function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        })
            ->when(!$userId, function ($q) use ($sessionId) {
                return $q->where('session_id', $sessionId);
            })
            ->delete();

        return redirect()->route('checkout.success', $order->id)
            ->with('success', '🎉 تم تقديم الطلب بنجاح!');
    }

    // صفحة نجاح الطلب
    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
