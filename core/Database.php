<?php

namespace Core;

class Database
{
    private ?\PDO $connection = null;
    private array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * Get PDO connection
     */
    public function getConnection(): \PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }
    
    /**
     * Connect to database
     */
    private function connect(): void
    {
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $this->config['connection'],
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset'] ?? 'utf8mb4'
        );
        
        try {
            $this->connection = new \PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Execute query with prepared statement
     */
    public function execute(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Fetch all results
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->execute($sql, $params)->fetchAll();
    }
    
    /**
     * Fetch single result
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->execute($sql, $params)->fetch();
        return $result ?: null;
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->getConnection()->rollBack();
    }
}