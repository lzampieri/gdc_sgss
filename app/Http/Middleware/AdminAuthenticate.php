<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if( User::admin() ) {
            return $next($request);
        } else if( Auth::check() ) {
            return redirect( route('home') )->with('negative-message','Non hai i privilegi per accedere a questa pagina');
        } else {
            return redirect()->guest(route('auth.redirect') );
        }
    }
}
