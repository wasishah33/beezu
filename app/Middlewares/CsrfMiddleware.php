<?php

namespace App\Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

class CsrfMiddleware extends Middleware
{
    /**
     * Handle CSRF protection
     */
    public function handle(Request $request, Response $response): bool
    {
        // Skip CSRF for GET requests
        if ($request->getMethod() === 'GET') {
            return true;
        }
        
        $token = $request->input('csrf_token') ?? $request->header('x-csrf-token');
        $sessionToken = Session::get('csrf_token');
        
        if (!$sessionToken || !$token || !hash_equals($sessionToken, $token)) {
            $response->setStatusCode(403);
            
            if ($request->isJson() || $request->isAjax()) {
                $response->json(['error' => 'CSRF token mismatch'], 403);
            } else {
                echo "CSRF token validation failed";
            }
            
            return false;
        }
        
        return true;
    }
}