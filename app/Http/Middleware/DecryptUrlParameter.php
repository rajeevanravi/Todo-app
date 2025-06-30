<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DecryptUrlParameter
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('data')) {
            try {
                $decrypted = Crypt::decryptString($request->query('data'));
                // Inject the decrypted value into the request
                $request->merge(['decrypted_data' => $decrypted]);
            } catch (DecryptException $e) {
                abort(403, 'Invalid or tampered URL.');
            }
        }

        return $next($request);
    }
}
