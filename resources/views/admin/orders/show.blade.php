@extends('layouts.admin')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 تفاصيل الطلب #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← العودة للطلبات</a>
    </div>

    <div class="row">
        {{-- معلومات الطلب --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>🛍️ المنتجات المطلوبة</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>الإجمالي الكلي</strong></td>
                                <td><strong style="font-size: 1.2rem;">{{ $order->formattedTotal() }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- تحديث الحالة ✅ تم التصحيح --}}
            <div class="card">
                <div class="card-header">
                    <h5>🔄 تحديث حالة الطلب</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-8">
                            <select name="status" class="form-select">
                                @php
                                    $statuses = [
                                        'pending' => '🟡 قيد الانتظار',
                                        'processing' => '🔄 قيد التجهيز',
                                        'shipped' => '🚚 تم الشحن',
                                        'delivered' => '✅ تم التوصيل',
                                        'cancelled' => '❌ ملغي',
                                    ];
                                @endphp
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- معلومات العميل --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>👤 معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $order->customer_name }}</p>
                    <p><strong>الهاتف:</strong> {{ $order->phone }}</p>
                    <p><strong>العنوان:</strong> {{ $order->address }}</p>
                    @if($order->notes)
                        <p><strong>ملاحظات:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>📊 حالة الطلب</h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $statuses = [
                            'pending' => '🟡 قيد الانتظار',
                            'processing' => '🔄 قيد التجهيز',
                            'shipped' => '🚚 تم الشحن',
                            'delivered' => '✅ تم التوصيل',
                            'cancelled' => '❌ ملغي',
                        ];
                        $statusColors = [
                            'pending' => ['bg' => '#fef9c3', 'color' => '#854d0e'],
                            'processing' => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                            'shipped' => ['bg' => '#d1fae5', 'color' => '#065f46'],
                            'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                            'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                        ];
                        $status = $statusColors[$order->status] ?? ['bg' => '#f1f5f9', 'color' => '#334155'];
                    @endphp
                    <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 10px 20px; border-radius: 30px; font-size: 18px; font-weight: bold;">
                        {{ $statuses[$order->status] ?? $order->status }}
                    </span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>📅 معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <p><strong>تاريخ الإنشاء:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $order->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
