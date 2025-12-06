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
}