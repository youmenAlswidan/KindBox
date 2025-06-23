<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

               // تحقق من تسجيل الدخول
        if (!Auth::check()) {
            // إذا غير مسجل دخول، ارجع لصفحة login الأدمن
            return redirect('/admin/login');
        }

        // تحقق من أن دور المستخدم هو أدمن (مثلاً role_id = 1)
        if (Auth::user()->role_id != 1) {
            // إذا مش أدمن، ارجع لصفحة login الأدمن أو صفحة خطأ
            return redirect('/admin/login')->with('error', 'ليس لديك صلاحية الوصول');
        }

        return $next($request);
    }
}
