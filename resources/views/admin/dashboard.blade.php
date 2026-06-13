@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container-fluid">
    {{-- إحصائيات --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold">${{ number_format($stats['sales_today'], 2) }}</h3>
                            <p class="text-muted mb-0">المبيعات اليوم</p>
                        </div>
                        <div style="width: 50px; height: 50px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 24px;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold">{{ $stats['new_orders'] }}</h3>
                            <p class="text-muted mb-0">طلبات اليوم</p>
                        </div>
                        <div style="width: 50px; height: 50px; background: #fef9c3; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 24px;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold">{{ $stats['total_products'] }}</h3>
                            <p class="text-muted mb-0">منتج</p>
                        </div>
                        <div style="width: 50px; height: 50px; background: #dcfce7; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 24px;">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold">⭐ {{ $stats['avg_rating'] }}</h3>
                            <p class="text-muted mb-0">تقييم المتجر</p>
                        </div>
                        <div style="width: 50px; height: 50px; background: #f5f3ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #8b5cf6; font-size: 24px;">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المنتجات الأكثر مبيعاً + الطلبات المعلقة --}}
    <div class="row mb-4">
        {{-- المنتجات --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-fire" style="color: #ef4444;"></i> الأكثر مبيعاً</h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>السعر</th>
                                    <th>المخزون</th>
                                    <th>المبيعات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-mobile-alt"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-muted">{{ $product->category }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock <= 5 ? 'bg-warning text-dark' : 'bg-success' }}">
                                            {{ $product->stock }} قطعة
                                        </span>
                                    </td>
                                    <td>{{ $product->sales_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- الطلبات المعلقة --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white" style="border-radius: 16px 16px 0 0;">
                    <h5><i class="fas fa-clock"></i> طلبات معلقة</h5>
                </div>
                <div class="card-body">
                    @forelse($recentOrders->where('status', 'pending') as $order)
                    <div class="mb-3 p-3" style="background: #fef9c3; border-radius: 12px;">
                        <strong>#{{ $order->order_number }}</strong>
                        <p class="mb-1">{{ $order->customer_name }}</p>
                        <div class="d-flex justify-content-between">
                            <small>{{ $order->formattedTotal() }}</small>
                            <span class="badge bg-warning text-dark">بانتظار الدفع</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">لا توجد طلبات معلقة 🎉</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- آخر التقييمات + آخر رسائل اتصل بنا --}}
    <div class="row mb-4">
        {{-- التقييمات --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-heart" style="color: #ec4899;"></i> آخر التقييمات</h5>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($recentReviews as $review)
                    <div class="p-3 mb-2" style="background: #f8fafc; border-radius: 12px;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div style="width: 40px; height: 40px; background: #1e293b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                {{ mb_substr($review->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <strong>{{ $review->user->name ?? 'مستخدم' }}</strong>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 small">"{{ Str::limit($review->comment, 50) }}"</p>
                    </div>
                    @empty
                    <p class="text-center text-muted">لا توجد تقييمات</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 📬 رسائل اتصل بنا --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white" style="border-radius: 16px 16px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-envelope" style="color: #f59e0b;"></i> آخر رسائل اتصل بنا</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $contacts = \App\Models\Contact::latest()->take(5)->get();
                    @endphp

                    @forelse($contacts as $contact)
                    <div class="p-3 mb-2" style="background: {{ $contact->is_read ? '#f8fafc' : '#eff6ff' }}; border-radius: 12px; border-right: 4px solid {{ $contact->is_read ? '#e2e8f0' : '#3b82f6' }};">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <strong>{{ $contact->name }}</strong>
                                <small class="text-muted ms-2">{{ $contact->email }}</small>
                                @if(!$contact->is_read)
                                    <span class="badge bg-primary ms-1" style="font-size: 10px;">جديد</span>
                                @endif
                                <p class="mb-0 mt-1" style="color: #475569; font-size: 13px;">{{ Str::limit($contact->message, 60) }}</p>

                                {{-- ✅ زر رد --}}
                                <button onclick="document.getElementById('replyForm{{ $contact->id }}').style.display='block'; this.style.display='none';"
                                        style="background: #3b82f6; color: white; border: none; padding: 4px 10px; border-radius: 6px; font-size: 12px; cursor: pointer; margin-top: 8px;">
                                    <i class="fas fa-reply"></i> رد
                                </button>

                                {{-- فورم الرد --}}
                                <form id="replyForm{{ $contact->id }}" action="{{ route('admin.contacts.reply', $contact) }}" method="POST"
                                      style="display: none; margin-top: 10px;">
                                    @csrf
                                    <textarea name="reply_message" rows="3" placeholder="اكتب ردك هنا..." required
                                              style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'Tajawal', sans-serif;"></textarea>
                                    <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 14px; border-radius: 6px; margin-top: 5px; cursor: pointer; font-size: 12px;">
                                        <i class="fas fa-paper-plane"></i> إرسال الرد
                                    </button>
                                </form>
                            </div>
                            <small class="text-muted text-nowrap ms-2">{{ $contact->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px;"></i>
                        <p>لا توجد رسائل حالياً</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
@if(session('success'))
<div id="toast" style="position: fixed; bottom: 30px; left: 30px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 12px; font-weight: 500; z-index: 9999; opacity: 1;">
    <i class="fas fa-check-circle" style="font-size: 22px;"></i>
    <span>{{ session('success') }}</span>
</div>
<script>setTimeout(function(){ var t=document.getElementById('toast'); if(t){ t.style.opacity='0'; t.style.transform='translateY(100px)'; } }, 4000);</script>
@endif

@endsection
