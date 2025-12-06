<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class BookRepository
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT books.*, authors.name AS author_name
            FROM books
            JOIN authors ON authors.id = books.author_id
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT books.*, authors.name AS author_name
            FROM books
            JOIN authors ON authors.id = books.author_id
            WHERE books.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getByAuthorId(int $authorId): array
    {
        $stmt = $this->db->prepare("
            SELECT books.*, authors.name AS author_name
            FROM books
            JOIN authors ON authors.id = books.author_id
            WHERE books.author_id = ?
            ORDER BY books.id DESC
        ");
        $stmt->execute([$authorId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO books (author_id, title, year)
            VALUES (:author_id, :title, :year)
        ");
        
        $stmt->execute([
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'year' => $data['year']
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE books
            SET author_id = :author_id, title = :title, year = :year
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'year' => $data['year']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
