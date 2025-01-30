<?php
require __DIR__ . '/../vendor/autoload.php'; // `autoload.php` を読み込む

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 環境変数の取得
$host = $_ENV['DB_HOST'] ?? null;
$dbname = $_ENV['DB_NAME'] ?? null;
$username = $_ENV['DB_USER'] ?? null;
$password = $_ENV['DB_PASS'] ?? null;

// デバッグ用に環境変数を出力
//echo "<pre>";
//print_r($_ENV);
//echo "</pre>";

// 環境変数が正しくセットされているか確認
if (!$host || !$dbname || !$username ) {
    die("Error: 環境変数が正しく設定されていません！<br>DB_HOST={$host}, DB_NAME={$dbname}, DB_USER={$username}");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// デバッグ用：データベース接続が確立されたか確認
if (!isset($pdo)) {
    die("Error: \$pdo is not set!");
}
?>
