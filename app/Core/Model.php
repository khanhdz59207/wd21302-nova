<?php

namespace App\Core;

use PDO;

abstract class Model {
    protected string $table = '';
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    protected function db(): PDO {
        return Database::getInstance();
    }

    public function all(string $orderBy = 'id DESC'): array {
        $stmt = $this->db()->query("SELECT * FROM `{$this->table}` ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db()->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array {
        $stmt = $this->db()->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :val LIMIT 1");
        $stmt->execute([':val' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function where(string $column, mixed $value, string $orderBy = 'id DESC'): array {
        $stmt = $this->db()->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :val ORDER BY {$orderBy}");
        $stmt->execute([':val' => $value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        if (empty($filtered)) {
            $filtered = $data;
        }

        $columns = array_keys($filtered);
        $fields = implode('`, `', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO `{$this->table}` (`{$fields}`) VALUES ({$placeholders})";
        $stmt = $this->db()->prepare($sql);

        foreach ($filtered as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }

        $stmt->execute();
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        if (empty($filtered)) {
            $filtered = $data;
        }

        $setParts = [];
        foreach ($filtered as $key => $val) {
            $setParts[] = "`{$key}` = :{$key}";
        }
        $setString = implode(', ', $setParts);

        $sql = "UPDATE `{$this->table}` SET {$setString} WHERE `{$this->primaryKey}` = :primary_id";
        $stmt = $this->db()->prepare($sql);

        foreach ($filtered as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':primary_id', $id);

        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->db()->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function count(string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 12, string $where = '', array $params = [], string $orderBy = 'id DESC'): array {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $total = $this->count($where, $params);
        $totalPages = (int)ceil($total / $perPage);

        $sql = "SELECT * FROM `{$this->table}`";
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }
        $sql .= " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return [
            'data'         => $items,
            'total'        => $total,
            'current_page' => $page,
            'per_page'     => $perPage,
            'total_pages'  => $totalPages,
            'has_prev'     => $page > 1,
            'has_next'     => $page < $totalPages
        ];
    }
}

