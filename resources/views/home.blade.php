@extends('layouts.app')

@section('title', 'الرئيسية - العالمية للأجهزة الذكية')

@section('content')
    <div class="container">
        {{-- الهيدر --}}
        <div
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px; border-radius: 30px; color: white; margin-bottom: 40px;">
            <h1 style="font-size: 48px; margin-bottom: 20px;">🛍️ مرحباً بك في العالمية للأجهزة الذكية</h1>
            <p style="font-size: 20px;">أفضل الأجهزة الذكية والموبايلات بأفضل الأسعار</p>
            <a href="{{ route('products.index') }}"
                style="display: inline-block; margin-top: 30px; background: white; color: #667eea; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: bold;">تسوق
                الآن</a>
        </div>

        {{-- 🔥 المنتجات الأكثر مبيعاً --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>🔥 الأكثر مبيعاً</h2>
            <a href="{{ route('products.index') }}" style="color: #3b82f6; text-decoration: none;">عرض الكل ←</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
            @forelse($featuredProducts as $product)
                <div
                    style="background: white; padding: 20px; border-radius: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative;">
                    {{-- وسم الأكثر مبيعاً --}}
                    <span
                        style="position: absolute; top: 10px; right: 10px; background: #ef4444; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px;">🔥
                        الأكثر مبيعاً</span>

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

                    {{-- الصورة والاسم كروابط --}}
                    <a href="{{ route('products.show', $product->slug) }}" style="text-decoration: none; color: inherit;">
                        @if ($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                        @else
                            <div
                                style="width: 150px; height: 150px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fas fa-box" style="font-size: 50px; color: #94a3b8;"></i>
                            </div>
                        @endif
                        <h3 style="font-size: 18px; margin-bottom: 10px; cursor: pointer;">{{ $product->name }}</h3>
                    </a>

                    {{-- الفئة --}}
                    <span
                        style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 13px; display: inline-block; margin-bottom: 10px;">
                        @php
                            $categories = [
                                'smartphones' => '📱 جوالات',
                                'tablets' => '📟 أجهزة لوحية',
                                'laptops' => '💻 لابتوب',
                                'smart-home' => '🏠 أجهزة منزلية',
                                'accessories' => '🎧 سماعات واكسسوارات',
                            ];
                        @endphp
                        {{ $categories[$product->category] ?? $product->category }}
                    </span>

                    {{-- السعر مع الخصم --}}
                    @if ($product->discount > 0)
                        @php $newPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                        <p style="text-decoration: line-through; color: #94a3b8; font-size: 14px; margin: 5px 0;">
                            ${{ number_format($product->price, 2) }}</p>
                        <p style="font-size: 22px; font-weight: bold; color: #ef4444; margin: 5px 0;">
                            ${{ number_format($newPrice, 2) }}</p>
                        <span
                            style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">-{{ $product->discount }}%</span>
                    @else
                        <p style="font-size: 22px; font-weight: bold; color: #1e293b; margin-bottom: 15px;">
                            ${{ number_format($product->price, 2) }}</p>
                    @endif

                    {{-- عدد المبيعات --}}
                    <p style="color: #64748b; font-size: 13px; margin-bottom: 10px;">
                        <i class="fas fa-shopping-bag"></i> تم البيع: {{ $product->sales_count }} مرة
                    </p>

                    {{-- ⭐ رابط تقييم المنتج --}}
                    <a href="{{ route('products.show', $product->slug) }}#reviews"
                        style="display: block; color: #f59e0b; text-decoration: none; margin-bottom: 10px; font-size: 14px;">
                        ⭐ تقييم المنتج
                    </a>

                    {{-- زر أضف إلى السلة --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        @if ($product->discount > 0)
                            <input type="hidden" name="offer_price" value="{{ $newPrice }}">
                        @endif
                        <button type="submit"
                            style="background: #3b82f6; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 500;">
                            <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                        </button>
                    </form>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <i class="fas fa-box-open" style="font-size: 60px; color: #94a3b8;"></i>
                    <p style="color: #64748b; font-size: 18px; margin-top: 15px;">لا توجد منتجات متاحة حالياً</p>
                </div>
            @endforelse
        </div>

        {{-- 🎯 مقترحة لك --}}
        @if (isset($suggestedProducts) && $suggestedProducts->count() > 0)
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; margin-top: 50px;">
                <h2>🎯 مقترحة لك</h2>
                <a href="{{ route('products.index') }}" style="color: #3b82f6; text-decoration: none;">عرض الكل ←</a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
                @foreach ($suggestedProducts as $product)
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

                        <a href="{{ route('products.show', $product->slug) }}"
                            style="text-decoration: none; color: inherit;">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                            @else
                                <div
                                    style="width: 150px; height: 150px; background: #f1f5f9; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box" style="font-size: 50px; color: #94a3b8;"></i>
                                </div>
                            @endif
                            <h3 style="font-size: 16px;">{{ $product->name }}</h3>
                        </a>

                        <span
                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            @php
                                $categories = [
                                    'smartphones' => '📱 جوالات',
                                    'tablets' => '📟 أجهزة لوحية',
                                    'laptops' => '💻 لابتوب',
                                    'smart-home' => '🏠 أجهزة منزلية',
                                    'accessories' => '🎧 سماعات واكسسوارات',
                                ];
                            @endphp
                            {{ $categories[$product->category] ?? $product->category }}
                        </span>

                        @if ($product->discount > 0)
                            @php $newPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                            <p style="text-decoration: line-through; color: #94a3b8; font-size: 14px;">
                                ${{ number_format($product->price, 2) }}</p>
                            <p style="font-size: 20px; font-weight: bold; color: #ef4444;">
                                ${{ number_format($newPrice, 2) }}</p>
                            <span
                                style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">-{{ $product->discount }}%</span>
                        @else
                            <p style="font-size: 20px; font-weight: bold; margin: 10px 0;">
                                ${{ number_format($product->price, 2) }}</p>
                        @endif

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
                @endforeach
            </div>
        @endif
    </div>

    {{-- Toast Notification --}}
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

@endsection
