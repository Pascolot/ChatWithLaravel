<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthVerification
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
        if (!session()->has('LoggedUser') && ($request->path() != 'auth/login' && $request->path() != 'auth/register')) {
            return redirect('auth/login')->with('erreur', 'Vous devez se connecter');
        }

        if (session()->has('LoggedUser') && ($request->path() == 'auth/login' || $request->path() == 'auth/register')) {
            return back(); // miverina @ lay teo aloha (url no resaka eto)
        }
        return $next($request)->header('Cache-Control', 'no-cache, no-store, max-age-0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Satt 01 Jan 1990 00:00:00 GMT'); // eto migérer an'ilay rehefa cliquena ny flêche retour de miverina login raha tsy connecter
    }
}
