<?php
require __DIR__ . '/../src/db.php';

// デバッグ用：データベース接続を確認
if (!isset($pdo)) {
    die("Error: データベース接続が確立されていません！");
}

// データ取得の処理
try {
    $stmt = $pdo->query("SELECT * FROM items ORDER BY id DESC");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("データ取得エラー: " . $e->getMessage());
}

// 新しいデータの追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO items (name, description) VALUES (:name, :description)");
            $stmt->execute(['name' => $name, 'description' => $description]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            die("データ追加エラー: " . $e->getMessage());
        }
    } else {
        echo "名前と説明を入力してください。";
    }
}
?>

<h1>PHP-MySQL CRUD</h1>

<!-- 新規データ登録フォーム -->
<form method="POST">
    <input type="text" name="name" placeholder="名前" required>
    <textarea name="description" placeholder="説明" required></textarea>
    <button type="submit">追加</button>
</form>

<!-- データ一覧の表示 -->
<ul>
<?php foreach ($items as $item): ?>
    <li>
        <a href="detail.php?id=<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
        <form action="delete.php" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $item['id']; ?>">
            <button type="submit">削除</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>
