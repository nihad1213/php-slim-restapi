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
}
