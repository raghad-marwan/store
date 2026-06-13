<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);

        $this->app->singleton(RegisterResponseContract::class, function () {
            return new class implements RegisterResponseContract {
                public function toResponse($request)
                {
                    return redirect()->route('home');
                }
            };
        });
    }

    public function boot(): void
    {
        // صفحة تسجيل الدخول
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // صفحة التسجيل
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ✅ صفحة نسيت كلمة المرور
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // ✅ صفحة إعادة تعيين كلمة المرور
        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        // إنشاء المستخدمين
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}
