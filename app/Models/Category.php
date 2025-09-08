<?php

namespace App\Models;

use Core\Model;

class Category extends Model
{
    protected static ?string $table = 'categories';
    
    /**
     * Get posts in this category
     */
    public function posts()
    {
        return Post::where('category_id', $this->id)->get();
    }
    
    /**
     * Get published posts count
     */
    public function getPublishedPostsCount(): int
    {
        return Post::where('category_id', $this->id)
            ->where('status', '=', 'published')
            ->count();
    }
    
    /**
     * Generate slug from name
     */
    public function generateSlug(string $name): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
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
            $value = $this->generateSlug($this->name);
        }
        $this->attributes['slug'] = $value;
    }
}
