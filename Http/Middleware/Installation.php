<?php

namespace Modules\Core\Http\Middleware;

use Closure;

class Installation
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('installation_lang')) {
            app()->setLocale($request->session()->get('installation_lang'));
        }
        if ($request->getUri() != route('installation.start') &&
            !str_starts_with($request->route()->uri(), 'installation/')) {
            return redirect(route('installation.start'));
        }

        return $next($request);
    }
}
