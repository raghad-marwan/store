<?php

namespace App\Http\Responses;

use App\Models\Cart;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // ✅ نقل السلة من session إلى user
        $this->mergeCart($request);

        // لو المستخدم أدمن → لوحة التحكم
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // لو مستخدم عادي → الصفحة الرئيسية
        return redirect()->route('home');
    }

    private function mergeCart($request)
    {
        $sessionId = session()->get('cart_session_id');
        $userId = $request->user()->id;

        if ($sessionId) {
            // جلب عناصر السلة القديمة للمستخدم
            $userCartItems = Cart::where('user_id', $userId)->get();

            // جلب عناصر السلة من الجلسة
            $sessionCartItems = Cart::where('session_id', $sessionId)->get();

            foreach ($sessionCartItems as $sessionItem) {
                // التأكد إذا المنتج موجود مسبقاً في سلة المستخدم
                $existingItem = $userCartItems->where('product_id', $sessionItem->product_id)->first();

                if ($existingItem) {
                    // تحديث الكمية
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $sessionItem->quantity,
                    ]);
                    // حذف العنصر من جلسة
                    $sessionItem->delete();
                } else {
                    // نقل العنصر للمستخدم
                    $sessionItem->update([
                        'user_id' => $userId,
                        'session_id' => null,
                    ]);
                }
            }
        }
    }
}
