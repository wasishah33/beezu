<?php

namespace App\Controllers;

use Core\Controller;
use Core\ValidationException;
use App\Models\User;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index(): void
    {
        $users = User::all();
        
        $this->render('users/index', [
            'title' => 'Users',
            'users' => $users
        ]);
    }
    
    /**
     * Show single user
     */
    public function show($id): void
    {
        $user = User::find($id);
        
        if (!$user) {
            $this->response->setStatusCode(404);
            echo "User not found";
            return;
        }
        
        $this->render('users/show', [
            'title' => 'User Profile',
            'user' => $user
        ]);
    }
    
    /**
     * Create user (API endpoint)
     */
    public function store(): void
    {
        try {
            $data = $this->validate([
                'name' => 'required|min:3|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ]);
            
            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password_confirmation']);
            
            // Create user
            $user = User::create($data);
            
            $this->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user->toArray()
            ], 201);
            
        } catch (ValidationException $e) {
            $this->json([
                'status' => 'error',
                'errors' => $e->getErrors()
            ], 422);
        }
    }
    
    /**
     * Update user
     */
    public function update($id): void
    {
        $user = User::find($id);
        
        if (!$user) {
            $this->json(['error' => 'User not found'], 404);
            return;
        }
        
        try {
            $data = $this->validate([
                'name' => 'min:3|max:100',
                'email' => 'email'
            ]);
            
            $user->update($data);
            
            $this->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user->toArray()
            ]);
            
        } catch (ValidationException $e) {
            $this->json([
                'status' => 'error',
                'errors' => $e->getErrors()
            ], 422);
        }
    }
    
    /**
     * Delete user
     */
    public function destroy($id): void
    {
        $user = User::find($id);
        
        if (!$user) {
            $this->json(['error' => 'User not found'], 404);
            return;
        }
        
        $user->delete();
        
        $this->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}