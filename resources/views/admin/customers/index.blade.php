@extends('layouts.admin')

@section('title', 'إدارة العملاء')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 إدارة العملاء</h2>
    </div>

    {{-- إحصائيات --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>إجمالي العملاء</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['new_today'] }}</h3>
                    <p>عملاء جدد اليوم</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['with_orders'] }}</h3>
                    <p>لديهم طلبات</p>
                </div>
            </div>
        </div>
    </div>

    {{-- بحث --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="🔍 بحث بالاسم أو الإيميل..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">بحث</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول العملاء --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الإيميل</th>
                            <th>تاريخ التسجيل</th>
                            <th>عدد الطلبات</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 40px; height: 40px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ mb_substr($customer->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $customer->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $customer->orders->count() }} طلب</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">👁️ عرض</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>لا يوجد عملاء حالياً</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
