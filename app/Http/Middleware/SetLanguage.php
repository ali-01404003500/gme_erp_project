<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLanguage
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
        $lang = $request->lang ?? $request->cookie('lang');
        app()->setLocale($lang);
        //add responce cookie lang
        $response = $next($request);
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response->cookie('lang', $lang, 120);
        }
        return $response;
    }
}
