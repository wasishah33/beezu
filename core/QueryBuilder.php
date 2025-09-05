<?php

namespace Core;

class QueryBuilder
{
    private Database $db;
    private string $table;
    private ?string $modelClass;
    private array $wheres = [];
    private array $bindings = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $select = ['*'];
    
    public function __construct(Database $db, string $table, ?string $modelClass = null)
    {
        $this->db = $db;
        $this->table = $table;
        $this->modelClass = $modelClass;
    }
    
    /**
     * Select columns
     */
    public function select(...$columns): self
    {
        $this->select = $columns;
        return $this;
    }
    
    /**
     * Add where clause
     */
    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }
    
    /**
     * Add where IN clause
     */
    public function whereIn(string $column, array $values): self
    {
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $this->wheres[] = "{$column} IN ({$placeholders})";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }
    
    /**
     * Add order by
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }
    
    /**
     * Set limit
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Set offset
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Build SELECT query
     */
    private function buildSelectQuery(): string
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }
        
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }
        
        return $sql;
    }
    
    /**
     * Get all results
     */
    public function get(): array
    {
        $sql = $this->buildSelectQuery();
        $results = $this->db->fetchAll($sql, $this->bindings);
        
        if ($this->modelClass) {
            return array_map(fn($row) => $this->hydrateModel($row), $results);
        }
        
        return $results;
    }
    
    /**
     * Get first result
     */
    public function first(): ?object
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }
    
    /**
     * Count results
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        $result = $this->db->fetch($sql, $this->bindings);
        return (int) $result['count'];
    }
    
    /**
     * Insert data
     */
    public function insert(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $this->db->execute($sql, array_values($data));
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Update data
     */
    public function update(array $data): bool
    {
        $sets = array_map(fn($col) => "{$col} = ?", array_keys($data));
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets);
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        $bindings = array_merge(array_values($data), $this->bindings);
        $stmt = $this->db->execute($sql, $bindings);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Delete data
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";
        
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        
        $stmt = $this->db->execute($sql, $this->bindings);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Hydrate model from array
     */
    private function hydrateModel(array $data): object
    {
        $model = new $this->modelClass();
        $model->fill($data);
        
        // Mark as existing record
        $reflection = new \ReflectionClass($model);
        $property = $reflection->getProperty('original');
        $property->setAccessible(true);
        $property->setValue($model, $data);
        
        return $model;
    }
}