<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'العالمية للأجهزة الذكية')</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Tajawal', sans-serif; }
        body { background: #f8fafc; }
        .navbar { background: white; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .logo { font-size: 24px; font-weight: bold; color: #1e293b; }
        .nav-links { display: flex; gap: 30px; align-items: center; }
        .nav-links a { text-decoration: none; color: #334155; font-weight: 500; }
        .nav-links a:hover { color: #3b82f6; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .footer { text-align: center; padding: 30px; color: #64748b; margin-top: 50px; border-top: 1px solid #e2e8f0; }

        /* أيقونة السلة */
        .cart-icon {
            position: relative;
            text-decoration: none;
            color: #334155;
            font-size: 20px;
            transition: 0.2s;
        }
        .cart-icon:hover { color: #3b82f6; }
        .cart-badge {
            position: absolute;
            top: -10px;
            right: -12px;
            background: #ef4444;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* ✅ تنسيق أزرار الترقيم - Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        .pagination li {
            display: inline-block;
        }
        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 8px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #334155;
        }
        .pagination li a:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .pagination li.active span {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            font-weight: bold;
        }
        .pagination li.disabled span {
            background: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }
        .pagination svg {
            width: 14px;
            height: 14px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">🏪 العالمية للأجهزة الذكية</div>
        <div class="nav-links">
            <a href="{{ route('home') }}">الرئيسية</a>
            <a href="{{ route('products.index') }}">المنتجات</a>
            <a href="{{ route('offers') }}">العروض</a>
            <a href="{{ route('contact') }}">اتصل بنا</a>

            {{-- ❤️ أيقونة المفضلة --}}
            @auth
            <a href="{{ route('wishlist.index') }}" class="cart-icon" style="font-size: 18px;">
                ❤️
            </a>
            @endauth

            {{-- أيقونة السلة --}}
            <a href="{{ route('cart.index') }}" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                @php
                    use App\Models\Cart;
                    $cartCount = Cart::count(
                        session()->get('cart_session_id'),
                        Auth::check() ? Auth::id() : null
                    );
                @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>

            @auth
                <a href="{{ route('profile') }}" style="color: #3b82f6;">👤 حسابي</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 500;">تسجيل خروج</button>
                </form>
            @else
                <a href="{{ route('login') }}">تسجيل دخول</a>
                <a href="{{ route('register') }}" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 12px;">حساب جديد</a>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>© 2025 العالمية للأجهزة الذكية - جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>
