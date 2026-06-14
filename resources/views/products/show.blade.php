@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container">
        <div class="row">
            {{-- صورة المنتج ومعلوماته --}}
            <div class="col-md-6" style="position: relative;">
                @if ($product->image)
                    @if (str_starts_with($product->image, 'http'))
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                            style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                    @else
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500';">
                    @endif
                @else
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500" alt="Default Product"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px;">
                @endif

                {{-- ❤️ زر المفضلة --}}
                @auth
                    @php
                        $isFavorited = \App\Models\Wishlist::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->exists();
                    @endphp
                    <button onclick="toggleWishlist({{ $product->id }}, this)"
                        style="position: absolute; top: 15px; left: 15px; background: white; border: none; cursor: pointer; font-size: 28px; z-index: 10; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                        <span id="heart{{ $product->id }}">{{ $isFavorited ? '❤️' : '🤍' }}</span>
                    </button>
                @endauth
            </div>

            <div class="col-md-6">
                <h1 style="font-size: 32px; margin-bottom: 10px;">{{ $product->name }}</h1>
                <span
                    style="background: #eff6ff; color: #3b82f6; padding: 6px 14px; border-radius: 20px; font-size: 14px;">{{ $product->category }}</span>

                {{-- ✅ السعر مع الخصم --}}
                @if ($product->discount > 0)
                    @php $newPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                    <p style="text-decoration: line-through; color: #94a3b8; font-size: 18px; margin: 15px 0 5px;">
                        ${{ number_format($product->price, 2) }}</p>
                    <h2 style="color: #ef4444; margin: 5px 0; font-size: 36px;">${{ number_format($newPrice, 2) }}</h2>
                    <span
                        style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 10px; font-size: 14px;">خصم
                        {{ $product->discount }}%</span>
                @else
                    <h2 style="color: #3b82f6; margin: 20px 0; font-size: 36px;">${{ number_format($product->price, 2) }}
                    </h2>
                @endif

                <p style="color: #64748b; font-size: 16px;">{{ $product->description }}</p>
                <p style="color: #10b981; font-weight: bold;">✅ متوفر: {{ $product->stock }} قطعة</p>

                {{-- 🎨 الألوان --}}
                @php
                    $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                @endphp
                @if (!empty($colors))
                    <div style="margin: 15px 0;">
                        <strong>الألوان المتاحة:</strong>
                        <div style="display: flex; gap: 8px; margin-top: 8px;">
                            @foreach ($colors as $color)
                                @php
                                    $colorMap = [
                                        'أسود' => '#000000',
                                        'أبيض' => '#FFFFFF',
                                        'ذهبي' => '#FFD700',
                                        'فضي' => '#C0C0C0',
                                        'رمادي' => '#808080',
                                        'أزرق' => '#3B82F6',
                                        'أزرق غامق' => '#1E3A5F',
                                        'أحمر' => '#EF4444',
                                        'أخضر' => '#10B981',
                                        'بنفسجي' => '#8B5CF6',
                                        'أصفر' => '#F59E0B',
                                        'برتقالي' => '#F97316',
                                        'وردي' => '#EC4899',
                                    ];
                                    $bgColor = $colorMap[$color] ?? '#E2E8F0';
                                    $borderColor = $color == 'أبيض' ? '#E2E8F0' : 'transparent';
                                @endphp
                                <span title="{{ $color }}"
                                    style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background: {{ $bgColor }}; border: 2px solid {{ $borderColor }}; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if ($product->discount > 0)
                        <input type="hidden" name="offer_price" value="{{ $newPrice }}">
                    @endif
                    <button type="submit"
                        style="background: #3b82f6; color: white; border: none; padding: 14px 30px; border-radius: 12px; font-size: 18px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                    </button>
                </form>
            </div>
        </div>

        {{-- ✅ فورم التقييم --}}
        <div id="reviews"
            style="background: white; padding: 30px; border-radius: 20px; margin-top: 40px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px;"><i class="fas fa-star" style="color: #f59e0b;"></i> تقييم المنتج</h3>

            @auth
                @if (session('error'))
                    <div style="background: #fee; color: #c00; padding: 12px; border-radius: 10px; margin-bottom: 15px;">
                        {{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 10px; margin-bottom: 15px;">
                        {{ session('success') }}</div>
                @endif

                <form action="{{ route('review.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- النجوم --}}
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 500;">تقييمك:</label>
                        <div id="starRating" style="font-size: 35px; cursor: pointer; margin-top: 5px;">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5">
                    </div>

                    {{-- التعليق --}}
                    <div style="margin-bottom: 15px;">
                        <textarea name="comment" rows="3" placeholder="اكتب تعليقك عن المنتج..." required
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;"></textarea>
                    </div>

                    <button type="submit"
                        style="background: #f59e0b; color: white; border: none; padding: 12px 30px; border-radius: 12px; cursor: pointer; font-weight: bold; font-size: 16px;">
                        <i class="fas fa-paper-plane"></i> إرسال التقييم
                    </button>
                </form>
            @else
                <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 12px;">
                    <p style="color: #64748b;">يجب <a href="{{ route('login') }}" style="color: #3b82f6;">تسجيل الدخول</a>
                        لتقييم المنتج</p>
                </div>
            @endauth
        </div>

        {{-- عرض التقييمات --}}
        <div
            style="background: white; padding: 30px; border-radius: 20px; margin-top: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px;">📝 تقييمات العملاء</h3>

            @php
                $reviews = \App\Models\Review::where('product_id', $product->id)->with('user')->latest()->get();
            @endphp

            @forelse($reviews as $review)
                <div style="padding: 15px; background: #f8fafc; border-radius: 12px; margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div
                            style="width: 40px; height: 40px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ mb_substr($review->user->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <strong>{{ $review->user->name ?? 'مستخدم' }}</strong>
                            <div style="color: #f59e0b;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <small
                            style="margin-right: auto; color: #94a3b8;">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <p style="margin-top: 10px; color: #475569;">{{ $review->comment }}</p>
                </div>
            @empty
                <p style="text-align: center; color: #64748b;">لا توجد تقييمات بعد. كن أول من يقيم!</p>
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
            }, 4000);
        </script>
    @endif

    {{-- JavaScript للنجوم --}}
    <script>
        const stars = document.querySelectorAll('#starRating .star');
        const ratingInput = document.getElementById('ratingValue');

        stars.forEach(star => {
            star.style.color = '#f59e0b';
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                ratingInput.value = value;

                stars.forEach(s => {
                    s.style.color = s.dataset.value <= value ? '#f59e0b' : '#e2e8f0';
                });
            });

            star.addEventListener('mouseover', function() {
                const value = this.dataset.value;
                stars.forEach(s => {
                    s.style.color = s.dataset.value <= value ? '#f59e0b' : '#e2e8f0';
                });
            });

            star.addEventListener('mouseout', function() {
                const value = ratingInput.value;
                stars.forEach(s => {
                    s.style.color = s.dataset.value <= value ? '#f59e0b' : '#e2e8f0';
                });
            });
        });

        // ✅ للمفضلة
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
