@extends('layouts.admin')

@section('title', 'تفاصيل العميل')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👤 تفاصيل العميل</h2>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">← العودة</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div style="width: 80px; height: 80px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 32px; margin: 0 auto;">
                        {{ mb_substr($customer->name, 0, 1) }}
                    </div>
                    <h4 class="mt-3">{{ $customer->name }}</h4>
                    <p class="text-muted">{{ $customer->email }}</p>
                    <p>📅 مسجل منذ: {{ $customer->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- طلبات العميل --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>🛒 طلبات العميل ({{ $customer->orders->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($customer->orders as $order)
                    <div class="d-flex justify-content-between align-items-center p-3 mb-2" style="background: #f8fafc; border-radius: 12px;">
                        <div>
                            <strong>#{{ $order->order_number }}</strong>
                            <p class="mb-0 small">{{ $order->created_at->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <span class="badge" style="background: {{ $order->statusBg() }}; color: {{ $order->statusColor() }}">
                                {{ $order->statusLabel() }}
                            </span>
                            <strong class="ms-3">{{ $order->formattedTotal() }}</strong>
                        </div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">عرض</a>
                    </div>
                    @empty
                    <p class="text-center text-muted">لا توجد طلبات</p>
                    @endforelse
                </div>
            </div>

            {{-- تقييمات العميل --}}
            <div class="card">
                <div class="card-header">
                    <h5>⭐ تقييمات العميل ({{ $customer->reviews->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($customer->reviews as $review)
                    <div class="p-3 mb-2" style="background: #f8fafc; border-radius: 12px;">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->product->name ?? 'منتج محذوف' }}</strong>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-2">"{{ $review->comment }}"</p>
                        <small class="text-muted">{{ $review->created_at->format('Y-m-d') }}</small>
                    </div>
                    @empty
                    <p class="text-center text-muted">لا توجد تقييمات</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
