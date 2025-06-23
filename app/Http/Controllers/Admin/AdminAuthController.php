<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
   
    public function showLoginForm()
    {
        return view('admin.login');
    }


    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt(array_merge($credentials, ['role_id' => 1]))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
           'email' => 'The login details are incorrect or you do not have admin privileges.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('admin.login'));
    }
}
