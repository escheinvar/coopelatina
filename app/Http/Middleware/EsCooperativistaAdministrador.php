<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsCooperativistaAdministrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $autorizados=['root','admon','teso'];  #usr
        if(in_array(Auth::user()['priv'],$autorizados)  ){    
            return $next($request);
        }else{
            return redirect ('/noadmin');
        }
    }
}
