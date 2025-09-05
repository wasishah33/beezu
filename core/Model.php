<?php

namespace Core;

abstract class Model
{
    protected static ?string $table = null;
    protected static string $primaryKey = 'id';
    protected array $attributes = [];
    protected array $original = [];
    protected static ?Database $db = null;
    protected static QueryBuilder $query;
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }
    
    /**
     * Get database instance
     */
    protected static function getDb(): Database
    {
        if (self::$db === null) {
            $app = Application::getInstance();
            self::$db = $app->getDatabase();
        }
        return self::$db;
    }
    
    /**
     * Get table name
     */
    public static function getTable(): string
    {
        if (static::$table === null) {
            $className = (new \ReflectionClass(static::class))->getShortName();
            static::$table = strtolower($className) . 's';
        }
        return static::$table;
    }
    
    /**
     * Create new query builder instance
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(self::getDb(), static::getTable(), static::class);
    }
    
    /**
     * Find record by ID
     */
    public static function find($id): ?static
    {
        return static::query()->where(static::$primaryKey, '=', $id)->first();
    }
    
    /**
     * Find all records
     */
    public static function all(): array
    {
        return static::query()->get();
    }
    
    /**
     * Where clause
     */
    public static function where(string $column, $operator, $value = null): QueryBuilder
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        return static::query()->where($column, $operator, $value);
    }
    
    /**
     * Create new record
     */
    public static function create(array $attributes): static
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }
    
    /**
     * Save model
     */
    public function save(): bool
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }
    
    /**
     * Insert new record
     */
    protected function insert(): bool
    {
        $id = static::query()->insert($this->attributes);
        if ($id) {
            $this->setAttribute(static::$primaryKey, $id);
            $this->original = $this->attributes;
            return true;
        }
        return false;
    }
    
    /**
     * Update existing record
     */
    public function update(array $attributes = []): bool
    {
        if (!empty($attributes)) {
            $this->fill($attributes);
        }
        
        $dirty = $this->getDirty();
        if (empty($dirty)) {
            return true;
        }
        
        $result = static::query()
            ->where(static::$primaryKey, '=', $this->getKey())
            ->update($dirty);
            
        if ($result) {
            $this->original = $this->attributes;
        }
        
        return $result;
    }
    
    /**
     * Delete record
     */
    public function delete(): bool
    {
        if (!$this->exists()) {
            return false;
        }
        
        return static::query()
            ->where(static::$primaryKey, '=', $this->getKey())
            ->delete();
    }
    
    /**
     * Fill attributes
     */
    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    
    /**
     * Get dirty attributes
     */
    public function getDirty(): array
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if (!array_key_exists($key, $this->original) || $value !== $this->original[$key]) {
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }
    
    /**
     * Check if model exists in database
     */
    public function exists(): bool
    {
        return !empty($this->original) && isset($this->original[static::$primaryKey]);
    }
    
    /**
     * Get primary key value
     */
    public function getKey()
    {
        return $this->getAttribute(static::$primaryKey);
    }
    
    /**
     * Get attribute
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Set attribute
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Magic getter
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }
    
    /**
     * Magic setter
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
    
    /**
     * Convert to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->attributes);
    }
}