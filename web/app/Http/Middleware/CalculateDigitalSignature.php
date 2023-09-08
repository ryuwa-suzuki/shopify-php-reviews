<?php

namespace App\Http\Middleware;

use App\Lib\AuthRedirection;
use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Shopify\Utils;

class CalculateDigitalSignature
{
    /**
     * Checks if the shop in the query arguments is currently installed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $sharedSecret = config('shopify.apikey');

        $queryParams = $request->query();
        $signature = $queryParams['signature'];
        unset($queryParams['signature']);

        $sortedParams = collect($queryParams)->map(function ($value, $key) {
            if (is_array($value)) {
                return $key . '=' . implode(',', $value);
            }
            return $key . '=' . $value;
        })->sort()->implode('');

        $calculatedSignature = hash_hmac('sha256', $sortedParams, $sharedSecret, false);


        if (!hash_equals($signature, $calculatedSignature)) {
            abort(401);
        }

        return $next($request);
    }
}
