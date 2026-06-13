@extends('layouts.app')

@section('title', 'سلة التسوق')

@section('content')
    <div class="container">
        <h1 style="margin-bottom: 30px;">🛒 سلة التسوق</h1>

        @if (session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
                {{ session('success') }}
            </div>
        @endif

        @if (isset($cartItems) && $cartItems->count() > 0)
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

                {{-- جدول المنتجات --}}
                <div>
                    <table
                        style="width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="padding: 15px; text-align: right;">المنتج</th>
                                <th style="padding: 15px; text-align: center;">السعر</th>
                                <th style="padding: 15px; text-align: center;">الكمية</th>
                                <th style="padding: 15px; text-align: center;">المجموع</th>
                                <th style="padding: 15px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                @php
                                    // ✅ استخدم السعر المخفض إذا وجد، وإلا السعر الأصلي
                                    $itemPrice = $item->price ?? $item->product->price ?? 0;
                                @endphp
                                <tr>
                                    <td style="padding: 15px;">
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" width="60"
                                                    height="60" style="border-radius: 10px; object-fit: cover;">
                                            @else
                                                <div
                                                    style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name ?? 'منتج محذوف' }}</strong><br>
                                                <small style="color: #64748b;">{{ $item->product->category ?? '' }}</small>
                                                @if($item->price)
                                                    <br><span style="color: #ef4444; font-size: 12px;">🏷️ عرض خاص</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        ${{ number_format($itemPrice, 2) }}
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <form action="{{ route('cart.update', $item) }}" method="POST"
                                            style="display: flex; align-items: center; gap: 8px; justify-content: center;">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                min="1"
                                                style="width: 60px; text-align: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px;"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold;">
                                        ${{ number_format($itemPrice * $item->quantity, 2) }}
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 18px;"
                                                onclick="return confirm('حذف المنتج من السلة؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <a href="{{ route('home') }}" style="color: #3b82f6; text-decoration: none;">
                            <i class="fas fa-arrow-right"></i> متابعة التسوق
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;"
                                onclick="return confirm('تفريغ السلة بالكامل؟')">
                                <i class="fas fa-trash"></i> تفريغ السلة
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ملخص الطلب --}}
                <div>
                    <div
                        style="background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <h3 style="margin-bottom: 20px;">📋 ملخص الطلب</h3>
                        <div style="border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>عدد المنتجات:</span>
                                <span>{{ $cartItems->sum('quantity') }}</span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; margin-top: 15px; padding-top: 15px; border-top: 2px solid #e2e8f0;">
                                <span>الإجمالي:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                            style="display: block; background: #10b981; color: white; text-align: center; padding: 14px; border-radius: 12px; text-decoration: none; font-weight: bold; margin-top: 20px; font-size: 18px;">
                            <i class="fas fa-check"></i> إتمام الشراء
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 60px; background: white; border-radius: 20px;">
                <i class="fas fa-shopping-cart" style="font-size: 80px; color: #94a3b8;"></i>
                <h2 style="margin-top: 20px; color: #64748b;">السلة فارغة</h2>
                <p style="color: #94a3b8; margin-bottom: 25px;">لم تقم بإضافة أي منتج إلى السلة بعد</p>
                <a href="{{ route('home') }}"
                    style="background: #3b82f6; color: white; padding: 14px 30px; border-radius: 12px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-shopping-bag"></i> تصفح المنتجات
                </a>
            </div>
        @endif
    </div>
@endsection
