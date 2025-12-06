<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class AuthorRepository
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM authors");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = ?");
        $stmt->execute([$id]);
        $author = $stmt->fetch();
        return $author === false ? null : $author;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO authors (name, country)
            VALUES (:name, :country)
        ");
        
        $stmt->execute([
            'name' => $data['name'],
            'country' => $data['country'] ?? null
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE authors
            SET name = :name, country = :country
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'country' => $data['country'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM authors WHERE id = ?");
        return $stmt->execute([$id]);
    }
}