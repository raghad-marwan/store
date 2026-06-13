@extends('layouts.admin')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>➕ إضافة منتج جديد</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للمنتجات
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        {{-- اسم المنتج --}}
                        <div class="mb-3">
                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="mb-3">
                            <label class="form-label">وصف المنتج</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- المواصفات --}}
                        <div class="mb-3">
                            <label class="form-label">المواصفات</label>
                            <div id="specifications-container">
                                <div class="row mb-2 spec-row">
                                    <div class="col-5">
                                        <input type="text" name="specifications[0][key]" class="form-control" placeholder="الميزة (مثال: المعالج)">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" name="specifications[0][value]" class="form-control" placeholder="القيمة (مثال: Snapdragon 8)">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeSpecRow(this)">🗑️</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSpecRow()">
                                ➕ إضافة مواصفة
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {{-- السعر --}}
                        <div class="mb-3">
                            <label class="form-label">السعر ($) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- ✅ نسبة الخصم --}}
                        <div class="mb-3">
                            <label class="form-label">نسبة الخصم (%)</label>
                            <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', 0) }}" min="0" max="99">
                            <small class="text-muted">اتركه 0 إذا لا يوجد خصم</small>
                            @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- المخزون --}}
                        <div class="mb-3">
                            <label class="form-label">المخزون <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- الفئة --}}
                        <div class="mb-3">
                            <label class="form-label">الفئة <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">اختر الفئة</option>
                                @foreach(\App\Models\Product::categories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- الماركة --}}
                        <div class="mb-3">
                            <label class="form-label">الماركة</label>
                            <select name="brand" class="form-select @error('brand') is-invalid @enderror">
                                <option value="">اختر الماركة</option>
                                @foreach(\App\Models\Product::brands() as $key => $label)
                                    <option value="{{ $key }}" {{ old('brand') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- الصورة --}}
                        <div class="mb-3">
                            <label class="form-label">صورة المنتج</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- معاينة الصورة --}}
                        <div class="mb-3">
                            <img id="imagePreview" src="#" alt="معاينة" style="max-width: 100%; display: none; border-radius: 12px;">
                        </div>

                        {{-- الحالة --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                                <label class="form-check-label" for="isActive">منتج نشط (يظهر في المتجر)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> حفظ المنتج
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let specIndex = 1;

function addSpecRow() {
    const container = document.getElementById('specifications-container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 spec-row';
    newRow.innerHTML = `
        <div class="col-5">
            <input type="text" name="specifications[${specIndex}][key]" class="form-control" placeholder="الميزة">
        </div>
        <div class="col-5">
            <input type="text" name="specifications[${specIndex}][value]" class="form-control" placeholder="القيمة">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSpecRow(this)">🗑️</button>
        </div>
    `;
    container.appendChild(newRow);
    specIndex++;
}

function removeSpecRow(button) {
    button.closest('.spec-row').remove();
}

// معاينة الصورة
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endsection
