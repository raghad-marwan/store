<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect('/admin');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ]);
    }
}
