<?php

namespace Core;

abstract class Middleware
{
    /**
     * Handle the request. Return false to stop propagation.
     */
    abstract public function handle(Request $request, Response $response): bool;
}


