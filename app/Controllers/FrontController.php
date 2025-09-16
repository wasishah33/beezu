<?php

namespace App\Controllers;

use Core\Controller;

class FrontController extends Controller
{
    /**
     * Home page
     */
    public function index(): void
    {
        // Explicitly set front layout (though auto-detection handles this)
        \Core\View::setLayout('main');

        $data = [
            'title' => 'Welcome to Beezu',
            'heading' => 'Beezu Framework',
            'tagline' => 'A lightweight PHP framework for building modern web apps',
            'details' => 'Beezu is successfully installed. Start creating your routes, controllers, and views with ease.
        Use the links below to access the admin dashboard or view the source code.'
        ];

        $this->render('front/home/index', $data);
    }

    /**
     * API example
     */
    public function api(): void
    {
        $this->json([
            'status' => 'success',
            'message' => 'API endpoint is working',
            'timestamp' => time()
        ]);
    }
}
