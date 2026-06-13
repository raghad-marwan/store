@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 {{ $product->name }}</h2>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← العودة</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>📝 معلومات المنتج</h5>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $product->name }}</p>
                    <p><strong>الوصف:</strong> {{ $product->description ?? 'لا يوجد وصف' }}</p>
                    <p><strong>الفئة:</strong> {{ \App\Models\Product::categories()[$product->category] ?? $product->category }}</p>
                    <p><strong>الماركة:</strong> {{ $product->brand }}</p>
                    <p><strong>السعر:</strong> ${{ number_format($product->price, 2) }}</p>
                    <p><strong>المخزون:</strong>
                        <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                            {{ $product->stock }} قطعة
                        </span>
                    </p>
                    <p><strong>الحالة:</strong>
                        @if($product->is_active)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-secondary">مخفي</span>
                        @endif
                    </p>
                    <p><strong>المبيعات:</strong> {{ $product->sales_count }} مرة</p>
                </div>
            </div>

            {{-- المواصفات ✅ معدلة --}}
            @php
                $specs = is_string($product->specifications) ? json_decode($product->specifications, true) : $product->specifications;
            @endphp

            @if(!empty($specs))
            <div class="card">
                <div class="card-header">
                    <h5>⚙️ المواصفات</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        @foreach($specs as $key => $value)
                        <tr>
                            <td width="40%"><strong>{{ $key }}</strong></td>
                            <td>{{ $value }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>🖼️ صورة المنتج</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="border-radius: 12px;">
                    @else
                        <div style="width: 100%; height: 200px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-box fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج نهائياً؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-trash"></i> حذف المنتج
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
