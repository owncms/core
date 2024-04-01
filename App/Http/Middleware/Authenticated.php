<?php

namespace Modules\Core\App\Http\Middleware;

use Closure;

class Authenticated
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
        if ($this->auth->check()) {
            return redirect('/');
        }
        return $next($request);
    }
}
