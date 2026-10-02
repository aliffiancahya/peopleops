<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $employee = $request->user()?->employee()->with('role')->first();
        if (!$employee || !$employee->role || $employee->status !== 'active' ||
            !in_array($employee->role->title, $roles, true)) {
            abort(403, 'You do not have access to this action.');
        }
        return $next($request);
    }
}
