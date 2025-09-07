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
        \Core\View::setLayout('front');
        
        $data = [
            'title' => 'Welcome to Beezu Framework',
            'message' => 'A secure, lightweight PHP framework built from scratch'
        ];
        
        $this->render('home/index', $data);
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