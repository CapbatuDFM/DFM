<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: 'role' (any active user) or 'role:owner' / 'role:owner,caretaker'.
 * Deactivated users are logged out on their next request.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'This account is deactivated. Contact the owner.']);
        }

        if ($roles && ! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
