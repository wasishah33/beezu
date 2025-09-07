<?php

namespace App\Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

class AuthMiddleware extends Middleware
{
    /**
     * Handle authentication check
     */
    public function handle(Request $request, Response $response): bool
    {
        if (!Session::has('user_id')) {
            // If it's an API request, return JSON error
            if ($request->isJson() || $request->isAjax()) {
                $response->json(['error' => 'Unauthorized'], 401);
            } else {
                $response->redirect(url('/admin/login'));
            }
            return false;
        }
        
        return true;
    }
}