<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // عرض كل التقييمات
    public function index(Request $request)
    {
        $query = Review::with('user', 'product');

        // فلترة حسب التقييم
        if ($request->filled('rating')) {
            $query->rating($request->rating);
        }

        // بحث في التعليق أو اسم المستخدم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        // إحصائيات
        $stats = [
            'total' => Review::count(),
            'avg_rating' => number_format(Review::avg('rating') ?? 0, 1),
            'five_star' => Review::rating(5)->count(),
            'four_star' => Review::rating(4)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    // عرض تفاصيل تقييم
    public function show(Review $review)
    {
        $review->load('user', 'product');
        return view('admin.reviews.show', compact('review'));
    }

    // حذف تقييم
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', '🗑️ تم حذف التقييم بنجاح');
    }

    // حذف عدة تقييمات مع بعض
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        Review::whereIn('id', $ids)->delete();
        return back()->with('success', '🗑️ تم حذف ' . count($ids) . ' تقييم');
    }
}
