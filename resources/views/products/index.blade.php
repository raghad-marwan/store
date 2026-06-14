@extends('layouts.app')

@section('title', 'المنتجات - العالمية للأجهزة الذكية')

@section('content')
    <div class="container">
        <h1 style="margin-bottom: 30px;">📱 جميع المنتجات</h1>

        {{-- أقسام الفئات --}}
        <div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
            <a href="{{ route('products.index') }}"
                style="padding: 12px 24px; border-radius: 30px; text-decoration: none; {{ !request('category') ? 'background: #3b82f6; color: white;' : 'background: white; color: #334155; border: 1px solid #e2e8f0;' }}">
                الكل
            </a>
            @foreach ($categories as $key => $label)
                <a href="{{ route('products.index', ['category' => $key]) }}"
                    style="padding: 12px 24px; border-radius: 30px; text-decoration: none; {{ request('category') == $key ? 'background: #3b82f6; color: white;' : 'background: white; color: #334155; border: 1px solid #e2e8f0;' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- فلترة وبحث --}}
        <div
            style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; background: white; padding: 20px; border-radius: 16px;">
            <form action="{{ route('products.index') }}" method="GET"
                style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%; align-items: center;">
                <input type="text" name="search" placeholder="🔍 بحث..." value="{{ request('search') }}"
                    style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; flex: 1;">
                <input type="number" name="min_price" placeholder="السعر من" value="{{ request('min_price') }}"
                    style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; width: 120px;">
                <input type="number" name="max_price" placeholder="السعر إلى" value="{{ request('max_price') }}"
                    style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; width: 120px;">
                <select name="sort" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر مبيعاً</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: منخفض لمرتفع
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: مرتفع لمنخفض
                    </option>
                </select>
                <button type="submit"
                    style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 10px; cursor: pointer;">تصفية</button>
            </form>
        </div>

        {{-- المنتجات --}}
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
            @forelse($products as $product)
                <div
                    style="background: white; padding: 20px; border-radius: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative;">

                    {{-- ❤️ زر المفضلة --}}
                    @auth
                        @php
                            $isFavorited = \App\Models\Wishlist::where('user_id', Auth::id())
                                ->where('product_id', $product->id)
                                ->exists();
                        @endphp
                        <button onclick="toggleWishlist({{ $product->id }}, this)"
                            style="position: absolute; top: 10px; left: 10px; background: white; border: none; cursor: pointer; font-size: 22px; z-index: 10; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <span id="heart{{ $product->id }}">{{ $isFavorited ? '❤️' : '🤍' }}</span>
                        </button>
                    @endauth

                    {{-- ✅ الصورة والاسم روابط لصفحة التفاصيل --}}
                    <a href="{{ route('products.show', $product->slug) }}" style="text-decoration: none; color: inherit;">
                        @if ($product->image)
                            @if (str_starts_with($product->image, 'http'))
                                @php
                                    // تنظيف اسم المنتج لاستخدامه في البحث داخل Unsplash
                                    $keyword = urlencode($product->name);
                                    $dynamicImage =
                                        'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80'; // رابط احتياطي أساسي

                                    // تحديد تصنيف الصورة بناءً على الكلمات المفتاحية
                                    if (
                                        str_contains(strtolower($product->name), 'laptop') ||
                                        str_contains($product->name, 'لابتوب')
                                    ) {
                                        $dynamicImage =
                                            'https://images.unsplash.com/photo-1496181130204-755241524eab?w=500&q=80';
                                    } elseif (
                                        str_contains(strtolower($product->name), 'iphone') ||
                                        str_contains(strtolower($product->name), 'pixel') ||
                                        str_contains(strtolower($product->name), 'galaxy') ||
                                        str_contains($product->name, 'جوال')
                                    ) {
                                        // استخدام روابط صور هواتف ذكية مختلفة لتجنب التكرار التام
                                        $phoneImages = [
                                            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500',
                                            'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500',
                                            'https://images.unsplash.com/photo-1565849511593-ed3de330144f?w=500',
                                            'https://images.unsplash.com/photo-1573148195900-7845dcb9b127?w=500',
                                        ];
                                        // اختيار صورة شبه عشوائية ثابتة لكل منتج بناءً على طول اسمه لضمان عدم تغيرها عند تحديث الصفحة
                                        $dynamicImage = $phoneImages[strlen($product->name) % count($phoneImages)];
                                    }
                                @endphp
                                <img src="{{ $dynamicImage }}" alt="{{ $product->name }}"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500';">
                            @endif
                        @else
                            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500"
                                alt="Default Product"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                        @endif
                        <h3 style="font-size: 16px; cursor: pointer;">{{ $product->name }}</h3>
                    </a>

                    <span
                        style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 12px;">{{ $categories[$product->category] ?? $product->category }}</span>

                    {{-- ✅ السعر مع الخصم --}}
                    @if ($product->discount > 0)
                        @php $newPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                        <p style="text-decoration: line-through; color: #94a3b8; font-size: 14px; margin: 5px 0;">
                            ${{ number_format($product->price, 2) }}</p>
                        <p style="font-size: 20px; font-weight: bold; color: #ef4444; margin: 5px 0;">
                            ${{ number_format($newPrice, 2) }}</p>
                        <span
                            style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">-{{ $product->discount }}%</span>
                    @else
                        <p style="font-size: 20px; font-weight: bold; margin: 10px 0;">
                            ${{ number_format($product->price, 2) }}</p>
                    @endif

                    {{-- ⭐ رابط تقييم المنتج --}}
                    <a href="{{ route('products.show', $product->slug) }}#reviews"
                        style="display: block; color: #f59e0b; text-decoration: none; margin-bottom: 10px; font-size: 14px;">
                        ⭐ تقييم المنتج
                    </a>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        @if ($product->discount > 0)
                            <input type="hidden" name="offer_price" value="{{ $newPrice }}">
                        @endif
                        <button type="submit"
                            style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; width: 100%;">
                            <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                        </button>
                    </form>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <i class="fas fa-search" style="font-size: 60px; color: #94a3b8;"></i>
                    <p style="color: #64748b; font-size: 18px; margin-top: 15px;">لا توجد منتجات تطابق البحث</p>
                </div>
            @endforelse
        </div>

        {{-- الترقيم --}}
        <div style="margin-top: 30px;">
            @if ($products->hasPages())
                <div
                    style="display: flex; justify-content: center; align-items: center; gap: 6px; margin: 30px 0; flex-wrap: wrap;">

                    @if ($products->onFirstPage())
                        <span
                            style="padding: 6px 12px; background: #f1f5f9; color: #94a3b8; border-radius: 8px; font-size: 13px;">«
                            السابق</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                            style="padding: 6px 12px; background: white; color: #3b82f6; border-radius: 8px; text-decoration: none; font-size: 13px; border: 1px solid #e2e8f0;">«
                            السابق</a>
                    @endif

                    @php
                        $start = max(1, $products->currentPage() - 2);
                        $end = min($products->lastPage(), $products->currentPage() + 2);
                    @endphp

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $products->currentPage())
                            <span
                                style="width: 32px; height: 32px; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 13px; font-weight: bold;">{{ $page }}</span>
                        @else
                            <a href="{{ $products->url($page) }}"
                                style="width: 32px; height: 32px; background: white; color: #334155; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; font-size: 13px; border: 1px solid #e2e8f0;">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                            style="padding: 6px 12px; background: white; color: #3b82f6; border-radius: 8px; text-decoration: none; font-size: 13px; border: 1px solid #e2e8f0;">التالي
                            »</a>
                    @else
                        <span
                            style="padding: 6px 12px; background: #f1f5f9; color: #94a3b8; border-radius: 8px; font-size: 13px;">التالي
                            »</span>
                    @endif

                </div>
                <div style="text-align: center; color: #64748b; font-size: 12px; margin-top: 5px;">
                    عرض {{ $products->firstItem() }}–{{ $products->lastItem() }} من أصل {{ $products->total() }} منتج
                </div>
            @endif
        </div>
    </div>

    {{-- ✅ Toast Notification --}}
    @if (session('success'))
        <div id="toast"
            style="position: fixed; bottom: 30px; left: 30px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 12px; font-weight: 500; z-index: 9999; opacity: 1; transform: translateY(0); transition: all 0.3s ease;">
            <i class="fas fa-check-circle" style="font-size: 22px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('toast');
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(100px)';
                }
            }, 3000);
        </script>
    @endif

    {{-- ✅ JavaScript للمفضلة --}}
    <script>
        function toggleWishlist(productId, btn) {
            fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const heart = document.getElementById('heart' + productId);
                    if (data.status === 'added') {
                        heart.textContent = '❤️';
                    } else {
                        heart.textContent = '🤍';
                    }
                });
        }
    </script>

@endsection
