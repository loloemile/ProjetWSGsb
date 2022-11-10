<?php
namespace App\Http\Middleware;

use Closure;

class Cors
{
/**
* Handle an incoming request.
*
* @param  \Illuminate\Http\Request $request
* @param  \Closure $next
*
* @return mixed
*/

public function handle($request, Closure $next)
{

    if (isset($_SERVER['HTTP_ORIGIN'])&&  ($_SERVER['HTTP_ORIGIN'] != '')) {
                 return $next($request)
           ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
    }
    else
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
    }

}

}
