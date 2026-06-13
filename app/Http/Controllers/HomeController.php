<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ المنتجات الأكثر مبيعاً (الأعلى sales_count)
        $featuredProducts = Product::where('is_active', true)
            ->orderBy('sales_count', 'desc')
            ->take(8)
            ->get();

        // ✅ مقترحة لك (عشوائية)
        $suggestedProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts', 'suggestedProducts'));
    }
}
