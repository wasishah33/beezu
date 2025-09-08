<?php

namespace App\Models;

use Core\Model;

class Post extends Model
{
    protected static ?string $table = 'posts';
    
    /**
     * Get the category for this post
     */
    public function category()
    {
        return Category::find($this->category_id);
    }
    
    /**
     * Get the author for this post
     */
    public function author()
    {
        return \App\Models\User::find($this->author_id);
    }
    
    /**
     * Get tags for this post
     */
    public function tags()
    {
        $db = static::getDb();
        $sql = "SELECT t.* FROM tags t 
                INNER JOIN post_tags pt ON t.id = pt.tag_id 
                WHERE pt.post_id = ?";
        $results = $db->fetchAll($sql, [$this->id]);
        
        return array_map(function($row) {
            return new Tag($row);
        }, $results);
    }
    
    /**
     * Attach tags to post
     */
    public function attachTags(array $tagIds): void
    {
        $db = static::getDb();
        
        // Remove existing tags
        $db->execute("DELETE FROM post_tags WHERE post_id = ?", [$this->id]);
        
        // Add new tags
        foreach ($tagIds as $tagId) {
            $db->execute("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)", [$this->id, $tagId]);
        }
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
     * Get published posts
     */
    public static function published()
    {
        return static::where('status', '=', 'published')
            ->orderBy('published_at', 'DESC');
    }
    
    /**
     * Get featured posts
     */
    public static function featured()
    {
        return static::where('is_featured', '=', 1)
            ->where('status', '=', 'published')
            ->orderBy('published_at', 'DESC');
    }
}
