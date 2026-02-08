<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
final class DB {
    private static ?PDO $pdo = null;

    // Ortam değişkenleri (config.php)
    private const HOST = DB_HOST;
    private const DB   = DB_NAME;
    private const USER = DB_USER;
    private const PASS = DB_PASS;
    private const CHARSET =DB_CHARSET;
    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    // ------------------- CONNECT -------------------
    public static function connect(): PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host=".self::HOST.";dbname=".self::DB.";charset=".self::CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true, // ⚡ Kalıcı bağlantı
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$pdo = new PDO($dsn, self::USER, self::PASS, $options);
            } catch (PDOException $e) {
                error_log('DB Connection Error: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['error' => 'Database connection failed']);
                exit;
            }
        }
        return self::$pdo;
    }

    // ------------------- CREATE -------------------
    public static function create(string $table, array $data): int {
        $pdo = self::connect();
        $data = SECURITY::sanitize($data);

        $cols = implode(',', array_keys($data));
        $placeholders = implode(',', array_map(fn($k)=>":$k", array_keys($data)));
        $stmt = $pdo->prepare("INSERT INTO `$table` ($cols) VALUES ($placeholders)");

        foreach ($data as $k => $v) {
            $stmt->bindValue(":$k", $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$pdo->lastInsertId();
    }

    // ------------------- READ -------------------
    public static function read(string $table, array $conditions = [], string $fields = '*'): array {
        $pdo = self::connect();
        $sql = "SELECT $fields FROM `$table`";
        if ($conditions) {
            $where = implode(' AND ', array_map(fn($k)=>"`$k` = :$k", array_keys($conditions)));
            $sql .= " WHERE $where";
        }

        $stmt = $pdo->prepare($sql);
        foreach ($conditions as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ------------------- UPDATE -------------------
    public static function update(string $table, array $data, array $conditions): int {
        $pdo = self::connect();
        $data = SECURITY::sanitize($data);

        $set = implode(',', array_map(fn($k)=>"`$k` = :set_$k", array_keys($data)));
        $where = implode(' AND ', array_map(fn($k)=>"`$k` = :cond_$k", array_keys($conditions)));
        $stmt = $pdo->prepare("UPDATE `$table` SET $set WHERE $where");

        foreach ($data as $k => $v) {
            $stmt->bindValue(":set_$k", $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        foreach ($conditions as $k => $v) {
            $stmt->bindValue(":cond_$k", $v);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    // ------------------- DELETE -------------------
    public static function delete(string $table, array $conditions): int {
        $pdo = self::connect();
        $where = implode(' AND ', array_map(fn($k)=>"`$k` = :$k", array_keys($conditions)));
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE $where");
        foreach ($conditions as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    // ------------------- RAW QUERY -------------------
    public static function readRaw(string $sql, array $params = []): array {
        $pdo = self::connect();

        // Sadece SELECT izinli, güvenlik amacıyla
        if (!preg_match('/^\s*SELECT/i', $sql)) {
            throw new InvalidArgumentException('Only SELECT queries are allowed in readRaw().');
        }

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(is_int($k) ? $k + 1 : ":$k", $v);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ------------------- EXECUTE (INSERT, UPDATE, DELETE) -------------------
    public static function execQuery(string $sql, array $params = []): int {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    //stored Procedure Çağır 
    public static function callSP(string $spName, array $params = []): array {
    $pdo = self::connect();
    $placeholders = implode(',', array_map(fn($k) => ":$k", array_keys($params)));
    $sql = "CALL $spName($placeholders)";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $k => $v) {
        $stmt->bindValue(":$k", $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll();
}
}
