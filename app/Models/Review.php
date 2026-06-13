<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function starsHtml()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i class="fas fa-star" style="color: #fbbf24;"></i>';
            } else {
                $html .= '<i class="far fa-star" style="color: #d1d5db;"></i>';
            }
        }
        return $html;
    }


    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

  
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
