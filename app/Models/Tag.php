<?php

namespace App\Models;

use Core\Model;

class Tag extends Model
{
    protected static ?string $table = 'tags';
    
    /**
     * Get posts with this tag
     */
    public function posts()
    {
        $db = static::getDb();
        $sql = "SELECT p.* FROM posts p 
                INNER JOIN post_tags pt ON p.id = pt.post_id 
                WHERE pt.tag_id = ? AND p.status = 'published'
                ORDER BY p.published_at DESC";
        $results = $db->fetchAll($sql, [$this->id]);
        
        return array_map(function($row) {
            return new Post($row);
        }, $results);
    }
    
    /**
     * Get posts count
     */
    public function getPostsCount(): int
    {
        $db = static::getDb();
        $sql = "SELECT COUNT(*) as count FROM posts p 
                INNER JOIN post_tags pt ON p.id = pt.post_id 
                WHERE pt.tag_id = ? AND p.status = 'published'";
        $result = $db->fetch($sql, [$this->id]);
        
        return (int) $result['count'];
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
    
    /**
     * Get or create tag by name
     */
    public static function getOrCreate(string $name): self
    {
        $tag = static::where('name', $name)->first();
        
        if (!$tag) {
            $tag = static::create([
                'name' => $name,
                'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)))
            ]);
        }
        
        return $tag;
    }
}
