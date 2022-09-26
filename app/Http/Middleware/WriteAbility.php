<?php

namespace App\Http\Middleware;

use App\Libs\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WriteAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $access)
    {
        $accesses = Auth::user()->access->toArray();
        $key = Helpers::is_column_in_array($access, 'access', $accesses);

        if($key !== false){
            if($accesses[$key]['write_only']){
                return $next($request);
            }

            return abort(404, 'Page Not Found');
        }

        return abort(404, 'Page Not Found');
    }
}
