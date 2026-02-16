<?php

namespace Riddle\TgBotBase\Db;

require_once __DIR__ . '/rb-sqlite.php';

class SeedService
{
    public function __construct(
        public readonly DbConfig $config,
    ) {}

    public function seedAll(): void
    {
        foreach ($this->config->seedFiles as $seedFile) {
            if (\R::count('seeds', 'file = ?', [$seedFile]) < 1) {
                $this->fromFile($seedFile);
                \R::exec("INSERT INTO seeds (file) VALUES (?)", [$seedFile]);
            }
        }
    }

    public function fromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("SQL файл не найден: {$filePath}");
        }

        $sql = file_get_contents($filePath);
        if ($sql === false) {
            throw new \RuntimeException("Не удалось прочитать SQL файл: {$filePath}");
        }

        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                \R::exec($statement);
            }
        }
    }
}
