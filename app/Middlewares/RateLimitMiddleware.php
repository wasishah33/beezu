<?php

namespace App\Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Session;

class RateLimitMiddleware extends Middleware
{
    /**
     * Simple in-session, per-IP-and-path rate limiter.
     * Reads limits from env: RATE_LIMIT_MAX (default 60), RATE_LIMIT_WINDOW (seconds, default 60).
     */
    public function handle(Request $request, Response $response): bool
    {
        Session::start();

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $path = $request->getPath();

        $maxRequests = (int)($_ENV['RATE_LIMIT_MAX'] ?? 60);
        $windowSeconds = (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 60);

        $now = time();

        // Session bucket
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }

        $key = sha1($ip . '|' . $path);
        $bucket = $_SESSION['rate_limit'][$key] ?? ['count' => 0, 'reset' => $now + $windowSeconds];

        // Reset window if expired
        if ($bucket['reset'] <= $now) {
            $bucket = ['count' => 0, 'reset' => $now + $windowSeconds];
        }

        $bucket['count']++;

        $_SESSION['rate_limit'][$key] = $bucket;

        if ($bucket['count'] > $maxRequests) {
            $retryAfter = max(1, $bucket['reset'] - $now);
            $response->setStatusCode(429);
            $response->setHeader('Retry-After', (string)$retryAfter);

            if ($request->isJson() || $request->isAjax()) {
                $response->json([
                    'error' => 'Too Many Requests',
                    'retry_after' => $retryAfter,
                ], 429);
            } else {
                echo 'Too Many Requests. Try again in ' . $retryAfter . ' seconds.';
            }

            return false;
        }

        return true;
    }
}


