<?php

namespace Jumpix\Core\Database;

class DatabaseSeeder
{
    private \PDO $pdo;
    private string $databasePath;

    public function __construct(\PDO $pdo, string $databasePath)
    {
        $this->pdo = $pdo;
        $this->databasePath = rtrim($databasePath, '/\\') . DIRECTORY_SEPARATOR;
    }

    public function run(array $seeds = [
        'create_tables.sql',
        'create_users.sql',
    ]): void {
        foreach ($seeds as $file) {
            $this->pdo->exec(file_get_contents($this->databasePath . $file));
        }
    }
}