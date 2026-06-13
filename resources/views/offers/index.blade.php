@extends('layouts.app')

@section('title', 'العروض - متجر الأجهزة')

@section('content')
    <div class="container">
        <div
            style="background: linear-gradient(135deg, #ef4444, #f97316); padding: 60px; border-radius: 30px; color: white; margin-bottom: 40px; text-align: center;">
            <h1 style="font-size: 48px;">🏷️ العروض والتخفيضات</h1>
            <p style="font-size: 20px; margin-top: 10px;">خصومات حقيقية على الأجهزة الذكية</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;">
            @php
                // ✅ جلب المنتجات اللي عليها خصم فقط (ثابت من قاعدة البيانات)
                $offerProducts = \App\Models\Product::where('is_active', true)
                    ->where('discount', '>', 0)
                    ->latest()
                    ->take(6)
                    ->get();
            @endphp

            @forelse($offerProducts as $product)
                @php
                    // ✅ استخدام نسبة الخصم من قاعدة البيانات
                    $newPrice = $product->price - ($product->price * $product->discount / 100);
                @endphp
                <div
                    style="background: white; padding: 20px; border-radius: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative;">
                    {{-- وسم الخصم --}}
                    <span
                        style="position: absolute; top: 10px; right: 10px; background: #ef4444; color: white; padding: 6px 14px; border-radius: 20px; font-weight: bold;">
                        خصم {{ $product->discount }}%
                    </span>

                    {{-- صورة المنتج --}}
                    <a href="{{ route('products.show', $product->slug) }}" style="text-decoration: none; color: inherit;">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                        @else
                            <div
                                style="width: 150px; height: 150px; background: #f1f5f9; border-radius: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box" style="font-size: 50px; color: #94a3b8;"></i>
                            </div>
                        @endif
                        <h3 style="font-size: 16px; margin-bottom: 10px;">{{ $product->name }}</h3>
                    </a>

                    {{-- الأسعار --}}
                    <p style="text-decoration: line-through; color: #94a3b8; font-size: 16px;">
                        ${{ number_format($product->price, 2) }}</p>
                    <p style="font-size: 24px; font-weight: bold; color: #ef4444; margin: 8px 0;">
                        ${{ number_format($newPrice, 2) }}</p>
                    <p style="color: #10b981; font-size: 14px;">وفر ${{ number_format($product->price - $newPrice, 2) }}</p>

                    {{-- زر أضف إلى السلة --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="offer_price" value="{{ $newPrice }}">
                        <button type="submit"
                            style="background: #ef4444; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 500;">
                            <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                        </button>
                    </form>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <i class="fas fa-tags" style="font-size: 60px; color: #94a3b8;"></i>
                    <p style="color: #64748b; font-size: 18px; margin-top: 15px;">لا توجد عروض حالياً</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Toast --}}
    @if (session('success'))
        <div id="toast"
            style="position: fixed; bottom: 30px; left: 30px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; z-index: 9999; opacity: 1;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                var t = document.getElementById('toast');
                if (t) {
                    t.style.opacity = '0';
                }
            }, 3000);
        </script>
    @endif

@endsection
