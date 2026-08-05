<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->role === 'super_admin') {
            return $next($request);
        }

        $school = $user->school;
        if (!$school) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not linked to any school. Please contact support.']);
        }

        $isExpired = $school->license_status === 'expired'
            || ($school->license_expiry && \Carbon\Carbon::parse($school->license_expiry)->isPast());

        if ($isExpired) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your school\'s license has expired. Please contact the administrator to renew.']);
        }

        return $next($request);
    }
}