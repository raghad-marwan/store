<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('profile.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Auth::user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', '✅ تم تحديث البيانات بنجاح');
    }
}
