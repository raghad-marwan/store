@extends('layouts.app')

@section('title', 'حسابي - ' . Auth::user()->name)

@section('content')
<div class="container">
    <h1 style="margin-bottom: 30px;">👤 حسابي</h1>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 20px;">
                <h3>معلوماتي</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom: 15px;">
                        <label>الاسم</label>
                        <input type="text" name="name" value="{{ $user->name }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>البريد الإلكتروني</label>
                        <input type="email" value="{{ $user->email }}" disabled style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc;">
                    </div>
                    <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer;">تحديث البيانات</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3>طلباتي السابقة</h3>
                @forelse($orders as $order)
                <div style="padding: 12px; background: #f8fafc; border-radius: 10px; margin-bottom: 10px;">
                    <strong>#{{ $order->order_number }}</strong>
                    <span style="margin-right: 10px;">{{ $order->status }}</span>
                    <p>${{ number_format($order->total, 2) }}</p>
                    <small>{{ $order->created_at->format('Y-m-d') }}</small>
                </div>
                @empty
                <p style="color: #64748b;">لا توجد طلبات سابقة</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
