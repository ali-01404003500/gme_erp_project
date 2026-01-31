<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermited
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
        $slug = $request->route()->getName();
        //if it end with store then change slug to create
        if (substr($slug, -5) == 'store') {
            $slug = substr($slug, 0, -5) . 'create';
        }
        //if it end with edit then change slug to update
        if (substr($slug, -4) == 'edit') {
            $slug = substr($slug, 0, -4) . 'update';
        }
       
        if (substr($slug, 0, 4) == 'api.') {
            $slug = substr($slug, 4);
        }
        // dd($slug);
        if (!hasPermission($slug)) {
            return abort(403, 'Access denied due to insufficient permissions.');
        }
        return $next($request);
    }
}
