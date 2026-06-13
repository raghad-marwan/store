@extends('layouts.app')

@section('title', 'تم الطلب بنجاح')

@section('content')
<div class="container">
    <div style="text-align: center; padding: 60px; background: white; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <i class="fas fa-check-circle" style="font-size: 80px; color: #10b981;"></i>
        <h1 style="margin-top: 20px; color: #1e293b;">🎉 تم تقديم الطلب بنجاح!</h1>
        <p style="color: #64748b; font-size: 18px; margin-top: 10px;">رقم طلبك: <strong>#{{ $order->id }}</strong></p>
        <p style="color: #64748b;">الإجمالي: <strong>${{ number_format($order->total, 2) }}</strong></p>
        <p style="color: #94a3b8; margin-top: 20px;">سيتم التواصل معك قريباً لتأكيد الطلب</p>

        <div style="margin-top: 30px;">
            <a href="{{ route('home') }}" style="background: #3b82f6; color: white; padding: 14px 30px; border-radius: 12px; text-decoration: none; font-weight: bold;">
                <i class="fas fa-home"></i> العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
