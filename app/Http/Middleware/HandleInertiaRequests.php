<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PropertyController;
use App\Models\Branch;
use App\Models\Property;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Middleware;
use Laravel\Jetstream\Http\Middleware\ShareInertiaData;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';
    // public function rootView(Request $request)
    // {
        // if ($request->route()->getPrefix() == 'admin') {
            // return 'app';
        //  }
        // return 'frontend';
    // }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
        // return $this->rootView . parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'flash' => [
                'error' => fn () => $request->session()->get('error')
            ],
            'permission' => Auth::check() ? auth()->user()->getUserPermisionByNameArray() : null,
            'logged_customer' => Auth::guard('customer')->user(),// this get logged user
            'recaptcha_site_key' => config('services.google_recaptcha.site_key'),
        ]);
    }

        /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(func_get_args()[2]);
        if ($rootView = func_get_args()[2] ?? null) {

            $this->rootView = $rootView;
        }

        return parent::handle($request, $next);
    }
}
