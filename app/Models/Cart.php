<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
    ];

    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // حساب الإجمالي لمحتويات السلة
    public static function total($sessionId = null, $userId = null)
    {
        $items = self::when($userId, function ($q) use ($userId) {
                return $q->where('user_id', $userId);
            })
            ->when($sessionId, function ($q) use ($sessionId) {
                return $q->where('session_id', $sessionId);
            })
            ->with('product')
            ->get();

        return $items->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });
    }

    // عدد العناصر في السلة
    public static function count($sessionId = null, $userId = null)
    {
        return self::when($userId, function ($q) use ($userId) {
                return $q->where('user_id', $userId);
            })
            ->when(!$userId && $sessionId, function ($q) use ($sessionId) {
                return $q->where('session_id', $sessionId);
            })
            ->sum('quantity');
    }
}
