<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $user = function_exists('auth_user') ? auth_user() : auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->loadMissing('vaiTro');

        return view('admin.profile.index', compact('user'));
    }
}
