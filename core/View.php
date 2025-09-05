<?php

namespace Core;

class View
{
    private array $data = [];
    private string $viewsPath;
    private static string $currentLayout = 'front';
    
    public function __construct()
    {
        $this->viewsPath = VIEWS_PATH;
    }
    
    /**
     * Set the current layout (front, admin, etc.)
     */
    public static function setLayout(string $layout): void
    {
        self::$currentLayout = $layout;
    }
    
    /**
     * Get the current layout
     */
    public static function getLayout(): string
    {
        return self::$currentLayout;
    }
    
    /**
     * Render view with layout
     */
    public function render(string $view, array $data = [], ?string $layout = null): void
    {
        $this->data = $data;
        
        // Use specified layout or current layout
        $layoutToUse = $layout ?? self::$currentLayout;
        
        // Extract data to make variables available in view
        extract($data);
        
        // Capture view content
        ob_start();
        $viewFile = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        require $viewFile;
        $content = ob_get_clean();
        
        // Render with layout
        $layoutFile = $this->viewsPath . '/layouts/' . $layoutToUse . '.php';
        
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: {$layoutFile}");
        }
        
        require $layoutFile;
    }
    
    /**
     * Escape output for security
     */
    public function e($value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Include partial view
     */
    public function partial(string $partial, array $data = []): void
    {
        extract(array_merge($this->data, $data));
        
        $partialFile = $this->viewsPath . '/' . str_replace('.', '/', $partial) . '.php';
        
        if (!file_exists($partialFile)) {
            throw new \Exception("Partial file not found: {$partialFile}");
        }
        
        require $partialFile;
    }
}