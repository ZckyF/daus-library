<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CheckResetPasswordToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $email = $request->route('email');
        $token = $request->route('token');

        if (!$email || !$token) {
            abort(404);
        }

        $reset = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$reset || !Hash::check($token, $reset->token)) {
            abort(404);
        }

        // Check if the token is expired (3 hours)
        $expiresAt = Carbon::parse($reset->created_at)->addHours(3);

        if (Carbon::now()->greaterThan($expiresAt)) {
            abort(404);
        }

        return $next($request);
    }
}
