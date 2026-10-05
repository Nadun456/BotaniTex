<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check() && $guard == 'web') {
        //         $user = Auth::guard('web')->user();
        //         // dd($user->roles[0]);
        //         if(count($user->roles) > 0 && $user->roles[0]->type == 'admin') {
        //             return redirect(RouteServiceProvider::HOME);
        //         } else if(count($user->roles) > 0 && $user->roles[0]->type == 'property') {
        //             return redirect(RouteServiceProvider::HOME);
        //             // return redirect('property/admin/dashboard');
        //         } else if(count($user->roles) > 0 && $user->roles[0]->type == 'partner') {
        //             return redirect('partner/dashboard');
        //         } else {
        //             // return redirect()->route('home');
        //         }
        //     } else if(Auth::guard($guard)->check() && $guard == 'customer'){
        //         return redirect()->route('home');
        //     }
        // }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
