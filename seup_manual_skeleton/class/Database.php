<?php
class Database
{
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt;
    }
    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql,$params)->fetchAll();
    }
    public function fetch(string $sql, array $params = []): ?array {
        $row=$this->query($sql,$params)->fetch(); return $row?:null;
    }
    public function execute(string $sql, array $params = []): bool {
        return $this->query($sql,$params) !== False;
    }
    public function lastInsertId(): string { return $this->pdo->lastInsertId(); }
}
