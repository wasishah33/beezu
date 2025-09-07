<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Session;
use Core\Application;
use App\Models\User;

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
            'title' => 'Beezu Dashboard'
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

    /**
     * Show login form
     */
    public function showLogin(): void
    {
        View::setLayout('login');
        $data = [
            'title' => 'Beezu Login',
            'flash_error' => Session::flash('error'),
            'flash_success' => Session::flash('success')
        ];
        
        if (Session::has('user_id')) {
            $this->redirect(url('/admin'));
            return;
        }
        $this->render('admin/auth/login', $data);
    }

    /**
     * Handle login
     */
    public function login(): void
    {
 
        $email = trim($this->request->input('email', ''));
        $password = (string) $this->request->input('password', '');
        $remember = (bool) $this->request->input('remember', false);

        if ($email === '' || $password === '') {
            Session::flash('error', 'Email and password are required.');
            $this->redirect(url('/admin/login'));
            return;
        }

        // Lookup user securely using User model
        $user = User::where('email', $email)
            ->where('is_active', '=', 1)
            ->first();

        if (!$user || !$user->verifyPassword($password)) {
            // Timing-safe dummy hash verification to mitigate user enumeration timing
            password_verify($password, password_hash('dummy', PASSWORD_DEFAULT));
            Session::flash('error', 'Invalid credentials.');
            $this->redirect(url('/admin/login'));
            return;
        }

        // Regenerate session
        Session::regenerate();
        Session::set('user_id', $user->id);
        Session::set('user_email', $user->email);
        Session::set('user_name', $user->name);
        Session::set('user_role', $user->role);

        // Optional remember token
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $user->update([
                'remember_token' => $token,
                'last_login_at' => date('Y-m-d H:i:s')
            ]);
            setcookie('remember_token', $token, time() + (86400*30), '/', '', isset($_SERVER['HTTPS']), true);
        } else {
            $user->update(['last_login_at' => date('Y-m-d H:i:s')]);
        }

        $this->redirect(url('/admin'));
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        // Clear remember token
        if (Session::has('user_id')) {
            $user = User::find(Session::get('user_id'));
            if ($user) {
                $user->update(['remember_token' => null]);
            }
        }

        // Destroy session and cookie
        setcookie('remember_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        Session::destroy();
        $this->redirect(url('/admin/login'));
    }

    /**
     * Profile view
     */
    public function profile(): void
    {
        $user = User::find(Session::get('user_id'));
        if (!$user) {
            $this->redirect(url('/admin/login'));
            return;
        }
        $this->render('admin/auth/profile', [
            'title' => 'My Profile',
            'user' => $user,
            'flash_success' => Session::flash('success'),
            'flash_error' => Session::flash('error')
        ]);
    }

    /**
     * Update user (name/email)
     */
    public function updateUser(): void
    {
        $name = trim($this->request->input('name', ''));
        $email = trim($this->request->input('email', ''));

        if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please provide a valid name and email address.');
            $this->redirect(url('/admin/profile'));
            return;
        }

        // Ensure email unique to others
        $existing = User::where('email', $email)
            ->where('id', '!=', Session::get('user_id'))
            ->first();
        if ($existing) {
            Session::flash('error', 'Email is already taken.');
            $this->redirect(url('/admin/profile'));
            return;
        }

        $user = User::find(Session::get('user_id'));
        $user->update([
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        Session::set('user_name', $name);
        Session::set('user_email', $email);
        Session::flash('success', 'Profile updated successfully.');
        $this->redirect(url('/admin/profile'));
    }

    /**
     * Change password
     */
    public function changePassword(): void
    {
       
        $password = (string) $this->request->input('password', '');
        $confirm = (string) $this->request->input('password_confirmation', '');

        if ($password === '' || $password !== $confirm || strlen($password) < 8) {
            Session::flash('error', 'Password must be at least 8 characters and match confirmation.');
            $this->redirect(url('/admin/profile'));
            return;
        }

        $user = User::find(Session::get('user_id'));
        
        $newHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $user->update([
            'password_hash' => $newHash,
            'password_updated_at' => date('Y-m-d H:i:s'),
            'remember_token' => null
        ]);
        Session::flash('success', 'Password changed successfully. Please log in again.');
        $this->logout();
    }
}
