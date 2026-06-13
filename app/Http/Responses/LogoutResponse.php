<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
