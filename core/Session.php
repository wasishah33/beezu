<?php

namespace Core;

class Session
{
    /**
     * Start session
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Set session value
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session value
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * Flash message (set and remove after retrieval)
     */
    public static function flash(string $key, $value = null)
    {
        if ($value === null) {
            $value = self::get($key);
            self::remove($key);
            return $value;
        }
        
        self::set($key, $value);
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
    
    /**
     * Destroy session
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}