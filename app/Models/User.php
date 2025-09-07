<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static ?string $table = 'users';
    
    /**
     * Find user by email
     */
    public static function findByEmail(string $email): ?self
    {
        return static::where('email', $email)->first();
    }
    
    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }
    
    /**
     * Hash password before saving
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }
}