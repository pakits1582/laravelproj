<?php

namespace App\Http\Middleware;

use Closure;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadAbility
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
            if($accesses[$key]['read_only']){
                return $next($request);
            }

            return abort(403, 'Unauthorized Access');
        }

        return abort(403, 'Unauthorized Access');
    }
}
