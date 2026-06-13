<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إذا كانت قاعدة البيانات في الذاكرة وجدول المنتجات غير موجود، قم ببنائه فوراً
        if (config('database.default') === 'sqlite' && config('database.connections.sqlite.database') === ':memory:') {
            if (!Schema::hasTable('products')) {
                Artisan::call('migrate:fresh', ['--seed' => true]);
            }
        }
    }
}
