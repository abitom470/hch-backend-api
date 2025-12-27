<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // CRITICAL FIX: Exclude the API login route used for token issuance
        'api/login', 
        
        // Exclude all other API routes as a safety measure for stateless auth
        'api/*', 
    ];
}