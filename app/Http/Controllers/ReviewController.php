<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // التأكد من أن المستخدم اشترى هذا المنتج واستلمه
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->whereHas('items', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', '⚠️ يجب شراء المنتج واستلامه أولاً لتتمكن من تقييمه');
        }

        // التأكد من عدم تقييم مسبق
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'لقد قمت بتقييم هذا المنتج مسبقاً');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', '✅ تم إضافة تقييمك بنجاح! شكراً لك');
    }
}
