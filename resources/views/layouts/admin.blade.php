<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') | العالمية للأجهزة الذكية</title>

    {{-- Bootstrap RTL --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Tajawal', sans-serif; }
        body { background: #f8fafc; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #1e293b;
            min-height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .logo {
            padding: 25px 20px;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .logo i {
            font-size: 28px;
            color: #3b82f6;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar .logo span {
            color: white;
            font-size: 20px;
            font-weight: 800;
        }

        .sidebar-nav {
            padding: 20px 15px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            color: #94a3b8;
            border-radius: 12px;
            text-decoration: none;
            margin-bottom: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .sidebar-nav .nav-link.active {
            background: #3b82f6;
            color: white;
        }

        .sidebar-nav .nav-link i {
            width: 22px;
            text-align: center;
        }

        .sidebar-nav .badge {
            margin-right: auto;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: 260px;
            padding: 30px;
        }

        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1e293b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                right: -260px;
                transition: 0.3s;
            }
            .sidebar.open {
                right: 0;
            }
            .main-content {
                margin-right: 0;
            }
            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 999;
            background: #1e293b;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    {{-- زر الـ Mobile Toggle --}}
    <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-mobile-alt"></i>
            <span>العالمية للأجهزة الذكية </span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> لوحة التحكم
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> المنتجات
                <span class="badge bg-primary">{{ App\Models\Product::count() }}</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> الطلبات
                @php $pendingCount = App\Models\Order::pending()->count(); @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger">{{ $pendingCount }} جديدة</span>
                @endif
            </a>

            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> العملاء
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> التقييمات
            </a>

            <div style="border-top: 1px solid #334155; margin: 15px 0;"></div>

            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="fas fa-store"></i> عرض المتجر
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; cursor: pointer; color: #ef4444;">
                    <i class="fas fa-sign-out-alt"></i> تسجيل خروج
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="top-bar">
            <div>
                <h5 style="margin: 0;">@yield('page-header', 'لوحة التحكم')</h5>
                <small class="text-muted">{{ date('Y-m-d') }}</small>
            </div>
            <div class="user-info">
                <i class="fas fa-bell text-muted"></i>
                <span>{{ Auth::user()->name }}</span>
                <div class="user-avatar">{{ mb_substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </div>

        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
