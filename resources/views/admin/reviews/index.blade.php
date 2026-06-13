@extends('layouts.admin')

@section('title', 'إدارة التقييمات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>⭐ إدارة التقييمات</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- إحصائيات --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>كل التقييمات</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>⭐ {{ $stats['avg_rating'] }}</h3>
                    <p>متوسط التقييم</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3>⭐⭐⭐⭐⭐</h3>
                    <p>{{ $stats['five_star'] }} تقييم</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>⭐⭐⭐⭐</h3>
                    <p>{{ $stats['four_star'] }} تقييم</p>
                </div>
            </div>
        </div>
    </div>

    {{-- فلترة --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="🔍 بحث..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">كل التقييمات</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                </div>
                <div class="col-md-2">
                    <button type="submit" form="bulkDeleteForm" class="btn btn-danger" onclick="return confirm('حذف التقييمات المحددة؟')">🗑️ حذف المحدد</button>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول التقييمات --}}
    <div class="card">
        <div class="card-body">
            <form id="bulkDeleteForm" action="{{ route('admin.reviews.bulk-delete') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>العميل</th>
                                <th>المنتج</th>
                                <th>التقييم</th>
                                <th>التعليق</th>
                                <th>التاريخ</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $review->id }}" class="review-checkbox"></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 35px; height: 35px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                            {{ mb_substr($review->user->name ?? '?', 0, 1) }}
                                        </div>
                                        {{ $review->user->name ?? 'مستخدم محذوف' }}
                                    </div>
                                </td>
                                <td>{{ $review->product->name ?? 'منتج محذوف' }}</td>
                                <td>{!! $review->starsHtml() !!}</td>
                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-info">👁️</a>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                    <h5>لا توجد تقييمات حالياً</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>

<script>
// تحديد الكل
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.review-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
