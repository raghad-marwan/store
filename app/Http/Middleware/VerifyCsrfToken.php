<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'logout',   // ✅ استثناء مسار تسجيل الخروج
        'login',    // ✅ استثناء مسار تسجيل الدخول (للاختبار)
        'register', // ✅ استثناء مسار التسجيل
        'admin/*',  // ✅ استثناء كل مسارات الأدمن (للاختبار)
    ];
}
