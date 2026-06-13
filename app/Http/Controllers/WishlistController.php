<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // عرض المفضلة
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    // إضافة/إزالة من المفضلة
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'تمت إزالة من المفضلة']);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);
            return response()->json(['status' => 'added', 'message' => 'تمت إضافة إلى المفضلة ❤️']);
        }
    }

    // حذف من المفضلة
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->back()->with('success', 'تمت إزالة من المفضلة');
    }
}
