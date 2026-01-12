<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Content-Type: text/plain');

$host = getenv("DB_HOST");
$db   = getenv("DB_NAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");

echo "DB_HOST=$host\nDB_NAME=$db\nDB_USER=$user\n\n";

try {
  $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);

  $row = $pdo->query("SELECT NOW() AS now_time")->fetch(PDO::FETCH_ASSOC);
  echo "✅ Connected to MySQL-Updated. Server time: " . $row["now_time"] . "\n";
} catch (Exception $e) {
  http_response_code(500);
  echo "❌ DB connection failed:\n" . $e->getMessage() . "\n";
}
