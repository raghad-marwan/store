<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category',
        'brand',
        'image',
        'specifications',
        'is_active',
        'sales_count',
        'discount',
    ];

    protected $casts = [
        //'specifications' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];


    public static function categories()
    {
        return [
            'smartphones' => '📱 جوالات',
            'tablets' => '📟 أجهزة لوحية',
            'laptops' => '💻 كمبيوتر ولابتوب',
            'smart-home' => '🏠 أجهزة المنزل الذكية',
            'accessories' => '🎧 سماعات واكسسوارات',
        ];
    }


    public static function brands()
    {
        return [
            'samsung' => 'Samsung',
            'apple' => 'Apple',
            'xiaomi' => 'Xiaomi',
            'tecno' => 'Tecno',
            'eufy' => 'Eufy',
            'other' => 'أخرى',
        ];
    }
}
