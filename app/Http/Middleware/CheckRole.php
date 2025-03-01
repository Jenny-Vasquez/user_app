<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || ($role === 'admin' && $request->user()->role !== 'admin')) {
            return redirect('/');
        }

        if ($role === 'superadmin' && ($request->user()->id !== 1 || $request->user()->role !== 'admin')) {
            return redirect('/');
        }

        return $next($request);
    }
}