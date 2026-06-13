@extends('layouts.admin')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 إدارة المنتجات</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة منتج جديد
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- بحث وفلترة --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="🔍 بحث بالاسم..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">كل الفئات</option>
                        @foreach(\App\Models\Product::categories() as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">كل الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متوفر</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>مخفي</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول المنتجات --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>المخزون</th>
                            <th>الحالة</th>
                            <th>المبيعات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            {{-- ✅ رقم تسلسلي بدل ID --}}
                            <td>{{ $loop->iteration + (($products->currentPage() - 1) * $products->perPage()) }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 45px; height: 45px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        @if($product->image)
                                            <img src="{{ asset('products/' . $product->image) }}" width="45" height="45" style="border-radius: 10px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-box text-muted"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">{{ $product->brand }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \App\Models\Product::categories()[$product->category] ?? $product->category }}</td>
                            <td><strong>${{ number_format($product->price, 2) }}</strong></td>
                            <td>
                                <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock }} قطعة
                                </span>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">مخفي</span>
                                @endif
                            </td>
                            <td>{{ $product->sales_count }}</td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>لا توجد منتجات حالياً</h5>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">إضافة أول منتج</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ✅ ترقيم مخصص بحجم صغير --}}
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-muted" style="font-size: 13px;">
                    عرض {{ $products->firstItem() }} إلى {{ $products->lastItem() }} من {{ $products->total() }} منتج
                </div>
                <div>
                    {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5-small') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
