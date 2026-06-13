<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
        'customer_name',
        'phone',
        'address',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price', 'subtotal');
    }

    public function formattedTotal()
    {
        return '$' . number_format($this->total, 2);
    }


    public static function statuses()
    {
        return [
            'pending' => ['label' => 'بانتظار الدفع', 'color' => '#f59e0b', 'bg' => '#fef9c3'],
            'processing' => ['label' => 'قيد التجهيز', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
            'shipped' => ['label' => 'تم الشحن', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
            'delivered' => ['label' => 'تم التوصيل', 'color' => '#10b981', 'bg' => '#dcfce7'],
            'cancelled' => ['label' => 'ملغي', 'color' => '#ef4444', 'bg' => '#fee2e2'],
        ];
    }

    public function statusColor()
    {
        return self::statuses()[$this->status]['color'] ?? '#64748b';
    }

    public function statusBg()
    {
        return self::statuses()[$this->status]['bg'] ?? '#f1f5f9';
    }

    public function statusLabel()
    {
        return self::statuses()[$this->status]['label'] ?? $this->status;
    }


    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
