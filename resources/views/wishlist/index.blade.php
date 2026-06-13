@extends('layouts.app')

@section('title', 'المفضلة - العالمية للأجهزة الذكية')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 30px;">❤️ المفضلة</h1>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
        @forelse($wishlistItems as $item)
        <div style="background: white; padding: 20px; border-radius: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <a href="{{ route('products.show', $item->product->slug) }}" style="text-decoration: none; color: inherit;">
                @if($item->product->image)
                    <img src="{{ asset('storage/' . $item->product->image) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px;">
                @else
                    <div style="width: 150px; height: 150px; background: #f1f5f9; border-radius: 12px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box" style="font-size: 50px; color: #94a3b8;"></i>
                    </div>
                @endif
                <h3 style="font-size: 16px; margin-top: 10px;">{{ $item->product->name }}</h3>
            </a>
            <p style="font-size: 20px; font-weight: bold; color: #3b82f6;">${{ number_format($item->product->price, 2) }}</p>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <form action="{{ route('cart.add') }}" method="POST" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                    <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 10px; border-radius: 10px; cursor: pointer; width: 100%;">
                        <i class="fas fa-shopping-cart"></i> شراء
                    </button>
                </form>
                <form action="{{ route('wishlist.destroy', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 10px; border-radius: 10px; cursor: pointer;">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
            <i class="fas fa-heart" style="font-size: 60px; color: #94a3b8;"></i>
            <h2 style="margin-top: 20px; color: #64748b;">المفضلة فارغة</h2>
            <p style="color: #94a3b8; margin-bottom: 25px;">لم تقم بإضافة منتجات للمفضلة بعد</p>
            <a href="{{ route('products.index') }}" style="background: #3b82f6; color: white; padding: 14px 30px; border-radius: 12px; text-decoration: none; font-weight: bold;">
                <i class="fas fa-shopping-bag"></i> تصفح المنتجات
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
