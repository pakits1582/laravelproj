<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfFaculty
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
        if (auth()->check() && auth()->user()->utype === 1) {
            return $next($request);
        }

        // If the user is not authenticated or is not a student (utype !== 2), you can customize the behavior here.
        // For example, you can redirect them to a different page or return a 403 Forbidden response.

        // For simplicity, we'll return a 403 Forbidden response in this example.
        return redirect('home');
    }
}
