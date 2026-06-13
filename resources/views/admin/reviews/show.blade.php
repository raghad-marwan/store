@extends('layouts.admin')

@section('title', 'تفاصيل التقييم')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 تفاصيل التقييم</h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">← العودة</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5>التعليق:</h5>
                        <p style="font-size: 1.2rem;">"{{ $review->comment }}"</p>
                    </div>
                    <div class="mb-4">
                        <h5>التقييم:</h5>
                        <div style="font-size: 2rem;">{!! $review->starsHtml() !!}</div>
                    </div>
                    <div class="mb-4">
                        <h5>التاريخ:</h5>
                        <p>{{ $review->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><h5>👤 العميل</h5></div>
                <div class="card-body">
                    <div style="text-align: center;">
                        <div style="width: 60px; height: 60px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; margin: 0 auto;">
                            {{ mb_substr($review->user->name ?? '?', 0, 1) }}
                        </div>
                        <h5 class="mt-3">{{ $review->user->name ?? 'مستخدم محذوف' }}</h5>
                        <p>{{ $review->user->email ?? '' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5>📦 المنتج</h5></div>
                <div class="card-body">
                    <h5>{{ $review->product->name ?? 'منتج محذوف' }}</h5>
                    @if($review->product)
                        <p>السعر: ${{ number_format($review->product->price, 2) }}</p>
                        <a href="{{ route('admin.products.show', $review->product) }}" class="btn btn-sm btn-info">عرض المنتج</a>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم نهائياً؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">🗑️ حذف التقييم</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
