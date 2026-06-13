<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // عرض كل المنتجات مع فلترة
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // فلترة حسب الفئة
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // فلترة حسب السعر
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // بحث
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ترتيب
        $sort = $request->get('sort', 'latest');
        if ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'popular') {
            $query->orderBy('sales_count', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends($request->all());

        $categories = [
            'smartphones' => '📱 جوالات',
            'tablets' => '📟 أجهزة لوحية',
            'laptops' => '💻 لابتوب',
            'smart-home' => '🏠 أجهزة منزلية',
            'accessories' => '🎧 سماعات واكسسوارات',
        ];

        return view('products.index', compact('products', 'categories'));
    }

    // عرض منتج واحد
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}



