<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrabajanHoy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(session('UsrEnTrabajo')=='1' OR auth()->user()->estatus == 'act'){
            return $next($request);
        }else{
            return redirect ('/notrabajohoy');
        }
    }
}
