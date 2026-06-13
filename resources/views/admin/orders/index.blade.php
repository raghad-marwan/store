@extends('layouts.admin')

@section('title', 'إدارة الطلبات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🛒 إدارة الطلبات</h2>
    </div>

    {{-- رسائل النجاح --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- إحصائيات سريعة --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>كل الطلبات</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p>بانتظار الدفع</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['processing'] }}</h3>
                    <p>قيد التجهيز</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['today'] }}</h3>
                    <p>طلبات اليوم</p>
                </div>
            </div>
        </div>
    </div>

    {{-- فلترة وبحث --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="🔍 بحث..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">كل الحالات</option>
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول الطلبات --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>الهاتف</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td><strong>{{ $order->formattedTotal() }}</strong></td>
                            <td>
                                <span style="background: {{ $order->statusBg() }}; color: {{ $order->statusColor() }}; padding: 6px 12px; border-radius: 30px; font-size: 13px; font-weight: 600;">
                                    {{ $order->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">👁️ عرض</a>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                    🔄 حالة
                                </button>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>

                                {{-- مودال تحديث الحالة ✅ تم التصحيح --}}
                                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">تحديث حالة الطلب #{{ $order->order_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <select name="status" class="form-select">
                                                        @foreach($statuses as $key => $status)
                                                            <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-primary">حفظ التغيير</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5>لا توجد طلبات حالياً</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الترقيم --}}
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
