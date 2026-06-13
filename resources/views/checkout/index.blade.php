
@extends('layouts.app')

@section('title', 'إتمام الشراء')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 30px;">📋 إتمام الشراء</h1>

    @if(session('error'))
        <div style="background: #fee; color: #c00; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- عرض أخطاء التحقق --}}
    @if($errors->any())
        <div style="background: #fee; color: #c00; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

        {{-- فورم معلومات العميل --}}
        <div>
            <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px;">👤 معلومات التوصيل</h3>

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">الاسم الكامل <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', Auth::user()->name ?? '') }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;">
                        @error('customer_name')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">البريد الإلكتروني <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', Auth::user()->email ?? '') }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;">
                        @error('customer_email')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">رقم الجوال <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;">
                        @error('phone')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">العنوان <span style="color: #ef4444;">*</span></label>
                        <textarea name="address" rows="3" required
                                  style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;">{{ old('address') }}</textarea>
                        @error('address')
                            <small style="color: #ef4444;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">ملاحظات</label>
                        <textarea name="notes" rows="2"
                                  style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: 'Tajawal', sans-serif;">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            style="width: 100%; background: #10b981; color: white; padding: 14px; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: 0.3s;">
                        <i class="fas fa-check"></i> تأكيد الطلب
                    </button>
                </form>
            </div>
        </div>

        {{-- ملخص السلة --}}
        <div>
            <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px;">🛒 ملخص الطلب</h3>

                @forelse($cartItems as $item)
                <div style="display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px solid #f1f5f9;">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" width="50" height="50" style="border-radius: 8px; object-fit: cover;">
                    @else
                        <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-box"></i>
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <strong>{{ $item->product->name ?? 'منتج محذوف' }}</strong><br>
                        <small style="color: #64748b;">الكمية: {{ $item->quantity }}</small>
                    </div>
                    <div style="font-weight: bold; color: #1e293b;">
                        ${{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}
                    </div>
                </div>
                @empty
                <p style="text-align: center; color: #64748b;">السلة فارغة</p>
                @endforelse

                <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; margin-top: 20px; padding-top: 15px; border-top: 2px solid #e2e8f0;">
                    <span>الإجمالي:</span>
                    <span style="color: #10b981;">${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('cart.index') }}" style="display: block; text-align: center; color: #3b82f6; margin-top: 15px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i> العودة للسلة
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
