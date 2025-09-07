<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index(): void
    {
        // Explicitly set admin layout (though auto-detection handles this)
        View::setLayout('admin');
        
        $data = [
            'title' => 'Dashboard'
        ];
        
        $this->render('admin/dashboard/index', $data);
    }
    
    /**
     * Admin users management
     */
    public function users(): void
    {
        $data = [
            'title' => 'User Management',
            'users' => [
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'admin'],
                ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'user'],
                ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'user'],
            ]
        ];
        
        $this->render('admin/users/index', $data);
    }
    
    /**
     * Admin settings
     */
    public function settings(): void
    {
        $data = [
            'title' => 'System Settings',
            'settings' => [
                'site_name' => 'Beezu Framework',
                'maintenance_mode' => false,
                'debug_mode' => true
            ]
        ];
        
        $this->render('admin/settings/index', $data);
    }
    
    /**
     * API endpoint for admin stats
     */
    public function apiStats(): void
    {
        $this->json([
            'status' => 'success',
            'data' => [
                'users' => 150,
                'sessions' => 23,
                'uptime' => '99.9%',
                'timestamp' => time()
            ]
        ]);
    }
}
