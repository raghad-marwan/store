@extends('layouts.admin')

@section('title', 'تعديل: ' . $product->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ تعديل: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للمنتجات
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">اسم المنتج</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">السعر ($)</label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-4">
                                {{-- ✅ حقل الخصم --}}
                                <div class="mb-3">
                                    <label class="form-label">نسبة الخصم (%)</label>
                                    <input type="number" name="discount" class="form-control" value="{{ old('discount', $product->discount ?? 0) }}" min="0" max="99">
                                    <small class="text-muted">0 = بدون خصم</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">المخزون</label>
                                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">الفئة</label>
                                    <select name="category" class="form-select" required>
                                        @foreach(\App\Models\Product::categories() as $key => $label)
                                            <option value="{{ $key }}" {{ $product->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">الماركة</label>
                                    <select name="brand" class="form-select">
                                        <option value="">اختر الماركة</option>
                                        @foreach(\App\Models\Product::brands() as $key => $label)
                                            <option value="{{ $key }}" {{ $product->brand == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- الصورة الحالية --}}
                        @if($product->image)
                        <div class="mb-3">
                            <label class="form-label">الصورة الحالية</label><br>
                            <img src="{{ asset('storage/' . $product->image) }}" width="150" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                        </div>
                        @endif

                        {{-- رفع صورة جديدة --}}
                        <div class="mb-3">
                            <label class="form-label">تغيير الصورة</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">اتركه فارغ إذا ما بدك تغير الصورة</small>
                        </div>

                        {{-- الحالة --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActive" {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">منتج نشط</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> تحديث المنتج
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
