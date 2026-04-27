<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

final class DB
{
    private static ?PDO $pdo = null;

    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    private static function pdo(): PDO
    {
        if (!self::$pdo) {
            self::$pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false
                ]
            );
        }
        return self::$pdo;
    }

    public static function add(string $table, array $data): int
    {
        $cols = implode(',', array_keys($data));
        $vals = implode(',', array_map(fn($k) => ":$k", array_keys($data)));

        $stmt = self::pdo()->prepare(
            "INSERT INTO `$table` ($cols) VALUES ($vals)"
        );

        foreach ($data as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }

        $stmt->execute();
        return (int)self::pdo()->lastInsertId();
    }

    public static function row(string $sql, array $params = []): ?array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }
    public static function rows(string $sql, array $params = []): array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public static function value(string $sql, array $params = [])
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $r = $stmt->fetch(PDO::FETCH_NUM);
        return $r[0] ?? null;
    }

    public static function exec(string $sql, array $params = []): int
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}