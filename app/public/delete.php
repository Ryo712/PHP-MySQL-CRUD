<?php
require __DIR__ . '/../src/db.php';

// POST リクエストで `id` を取得
$id = $_POST['id'] ?? null;

if (!$id) {
    die("❌ ID が指定されていません！");
}

// データ削除処理
try {
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    // 削除後、トップページへリダイレクト
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("❌ 削除エラー: " . $e->getMessage());
}
?>
