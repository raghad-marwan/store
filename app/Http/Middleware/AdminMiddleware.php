<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. لو المستخدم مش مسجل دخول → نحوله لصفحة تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // 2. لو مسجل دخول لكن دوره مش admin → نرجعه للصفحة الرئيسية
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالدخول إلى لوحة التحكم');
        }

        // 3. لو أدمن → نسمح له بالمرور
        return $next($request);
    }
}
