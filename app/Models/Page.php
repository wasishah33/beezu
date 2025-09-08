<?php

namespace App\Models;

use Core\Model;

class Page extends Model
{
    protected static ?string $table = 'pages';
    
    /**
     * Get the author for this page
     */
    public function author()
    {
        return \App\Models\User::find($this->author_id);
    }
    
    /**
     * Generate slug from title
     */
    public function generateSlug(string $title): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $originalSlug = $slug;
        $counter = 1;
        while (static::where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Set slug attribute
     */
    public function setSlugAttribute(string $value): void
    {
        if (empty($value)) {
            $value = $this->generateSlug($this->title);
        }
        $this->attributes['slug'] = $value;
    }
    
    /**
     * Get excerpt from content if not set
     */
    public function getExcerptAttribute(): string
    {
        if (!empty($this->attributes['excerpt'])) {
            return $this->attributes['excerpt'];
        }
        
        $content = strip_tags($this->content);
        return strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
    }
    
    /**
     * Get published pages
     */
    public static function published()
    {
        return static::where('status', '=', 'published')
            ->orderBy('title', 'ASC');
    }
    
    /**
     * Get homepage
     */
    public static function getHomepage()
    {
        return static::where('is_homepage', '=', 1)
            ->where('status', '=', 'published')
            ->first();
    }
    
    /**
     * Set as homepage (unset others)
     */
    public function setAsHomepage(): void
    {
        // Unset current homepage
        static::query()->update(['is_homepage' => 0]);
        
        // Set this as homepage
        $this->update(['is_homepage' => 1]);
    }
}
