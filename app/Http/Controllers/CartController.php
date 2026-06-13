<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', Str::uuid());
        }
        return session()->get('cart_session_id');
    }

    // عرض السلة
  /*  public function index()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::check() ? Auth::id() : null; // ✅ آمنة

        // جلب عناصر السلة
        if ($userId) {
            $cartItems = Cart::where('user_id', $userId)
                ->with('product')
                ->get();
        } else {
            $cartItems = Cart::where('session_id', $sessionId)
                ->with('product')
                ->get();
        }

        // حساب الإجمالي
        $total = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }
*/


public function index()
{
    $sessionId = $this->getSessionId();
    $userId = Auth::check() ? Auth::id() : null;

    if ($userId) {
        $cartItems = Cart::where('user_id', $userId)
            ->with('product')
            ->get();
    } else {
        $cartItems = Cart::where('session_id', $sessionId)
            ->with('product')
            ->get();
    }

    $total = $cartItems->sum(function ($item) {
        return $item->product ? $item->product->price * $item->quantity : 0;
    });

    return view('cart.index', compact('cartItems', 'total'));
}
    // إضافة منتج للسلة
    /*public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $sessionId = $this->getSessionId();
        $userId = Auth::check() ? Auth::id() : null; // ✅ آمنة
        $quantity = $request->quantity ?? 1;

        // البحث عن المنتج في السلة
        if ($userId) {
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->first();
        } else {
            $cartItem = Cart::where('session_id', $sessionId)
                ->where('product_id', $product->id)
                ->first();
        }

        if ($cartItem) {
            // تحديث الكمية
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
            ]);
        } else {
            // إضافة جديد
            Cart::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()->with('success', '✅ تمت الإضافة إلى السلة');
    }*/

   /* public function add(Request $request)
{
   //dd('hi' , session()->all());
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'nullable|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);
    $sessionId = $this->getSessionId();
    $userId = Auth::check() ? Auth::id() : null;
    $quantity = $request->quantity ?? 1;

    // البحث عن المنتج في السلة
    if ($userId) {
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();
    } else {
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();
    }

    if ($cartItem) {
        $cartItem->update([
            'quantity' => $cartItem->quantity + $quantity,
        ]);
    } else {
        Cart::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    // عدد العناصر في السلة
    $cartCount = Cart::count($sessionId, $userId);

    // ✅ إذا كان الطلب AJAX، أرجع JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => '✅ تمت الإضافة إلى السلة',
            'cart_count' => $cartCount,
        ]);
    }

    // إذا كان طلب عادي، أرجع redirect
   // return redirect()->back()->with('success', '✅ تمت الإضافة إلى السلة');
   return redirect(url()->previous() . '#toast-trigger')->with('success', '✅ تمت الإضافة إلى السلة بنجاح');
}*/

public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'nullable|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);
    $sessionId = $this->getSessionId();
    $userId = Auth::check() ? Auth::id() : null;
    $quantity = $request->quantity ?? 1;

    // ✅ السعر: إذا فيه offer_price استخدمه، غير ذلك null (بيستخدم السعر الأصلي)
    $price = $request->offer_price ?? null;

    // البحث عن المنتج في السلة
    if ($userId) {
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();
    } else {
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();
    }

    if ($cartItem) {
        $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
    } else {
        Cart::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price, // ✅ يخزن السعر المخفض إذا وجد
        ]);
    }

    return redirect()->back()->with('success', '✅ تمت الإضافة إلى السلة بنجاح');
}

    // تحديث الكمية
    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index')->with('success', '✅ تم تحديث السلة');
    }

    // حذف منتج
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success', '🗑️ تم حذف المنتج');
    }

    // تفريغ السلة
    public function clear()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::check() ? Auth::id() : null; // ✅ آمنة

        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } else {
            Cart::where('session_id', $sessionId)->delete();
        }

        return redirect()->route('cart.index')->with('success', '🗑️ تم تفريغ السلة');
    }
}
