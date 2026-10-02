<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncEmployeeContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $employee = $request->user()?->employee()->with('role')->first();
        if (!$employee || !$employee->role || $employee->status !== 'active') {
            abort(403, 'Your account needs an active employee profile. Contact HR.');
        }

        // Compatibility with the existing Mazer views; authorization never relies on session values.
        $request->session()->put('role', $employee->role->title);
        $request->session()->put('employee_id', $employee->id);
        return $next($request);
    }
}
