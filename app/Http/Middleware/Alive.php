<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Alive
{
    public function handle(Request $request, Closure $next)
    {
        if( Auth::check() && ( Auth::user()->is_alive || Auth::user()->isadmin ) ) {
            return $next($request);
        }
        return redirect()->route('home')->with('negative-message', 'Azione non permessa ai morti');
    }
}
