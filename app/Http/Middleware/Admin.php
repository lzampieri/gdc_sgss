<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if( Auth::check() && Auth::user()->isadmin ) {
            return $next($request);
        }
        return redirect( route('home') )->with('negative-message','Non hai i privilegi per accedere a questa pagina');
    }
}
