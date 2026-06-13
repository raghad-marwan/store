<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

//  الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

//  مسارات المصادقة (Fortify)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');

//  إعادة تعيين كلمة المرور
Route::get('/reset-password/{token}', function (Request $request, string $token) {
    return view('auth.reset-password', ['token' => $token, 'request' => $request]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

// لوحة تحكم الأدمن
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('/reviews/bulk-delete', [AdminReviewController::class, 'bulkDelete'])->name('reviews.bulk-delete');
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
});

// 🛒 السلة
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

//  إتمام الشراء
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');


//  صفحة المنتجات
Route::get('/products', [ShopController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('products.show');

//  تقييم المنتجات

Route::post('/review', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');

//  العروض

Route::get('/offers', function () { return view('offers.index'); })->name('offers');


//  اتصل بنا

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


use App\Http\Controllers\ProfileController;

Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');



use App\Http\Controllers\WishlistController;

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index')->middleware('auth');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle')->middleware('auth');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy')->middleware('auth');
