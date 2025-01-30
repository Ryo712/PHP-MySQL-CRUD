<?php
require __DIR__ . '/../src/db.php';

// URL パラメータから `id` を取得
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID が指定されていません！");
}

// データ取得処理
try {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();

    if (!$item) {
        die("指定された ID のアイテムが見つかりません！");
    }
} catch (PDOException $e) {
    die("データ取得エラー: " . $e->getMessage());
}

// アイテムの更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("UPDATE items SET name = :name, description = :description WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'id' => $id
            ]);
            // 更新後にトップページへリダイレクト
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            die("更新エラー: " . $e->getMessage());
        }
    } else {
        echo "名前と説明を入力してください！";
    }
}
?>

<h1>アイテム詳細</h1>
<form method="POST">
    <label>名前:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    
    <label>説明:</label>
    <textarea name="description" required><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>
    
    <button type="submit">更新</button>
</form>

<!-- トップページに戻るリンク -->
<a href="index.php">← 戻る</a>
