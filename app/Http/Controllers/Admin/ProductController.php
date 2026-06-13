<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // عرض كل المنتجات
    public function index()
    {
        // الأقدم أولاً - الجديد في الآخر
        $products = Product::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // صفحة إضافة منتج جديد
    public function create()
    {
        return view('admin.products.create');
    }

    // تخزين المنتج الجديد
    public function store(Request $request)
    {
        // ✅ التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:99',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'brand' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ رفع الصورة إذا وجدت
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // ✅ إضافة slug
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['discount'] = $request->discount ?? 0;

        // ✅ حفظ المنتج
        Product::create($validated);

        // ✅ رسالة نجاح والعودة لقائمة المنتجات
        return redirect()->route('admin.products.index')
            ->with('success', '✅ تم إضافة المنتج بنجاح');
    }

    // عرض منتج واحد
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    // صفحة تعديل منتج
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // تحديث المنتج
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:99',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'brand' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // ✅ تحديث slug مع التأكد من عدم تكراره
        if ($product->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $count = 1;
            $originalSlug = $slug;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['discount'] = $request->discount ?? 0;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', '✅ تم تحديث المنتج بنجاح');
    }

    // حذف منتج
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', '🗑️ تم حذف المنتج بنجاح');
    }
}
