<?php
session_start();

// Разрешаем доступ Супер-админу и Редактору
if (!isset($_SESSION['admin_logged_in']) || ($_SESSION['role'] !== 'super_admin' && $_SESSION['role'] !== 'editor')) {
    die("<h2 style='color:red; text-align:center; margin-top:50px; font-family:sans-serif;'>Доступ запрещен. Только для админов и редакторов.</h2>");
}

$pdo = new PDO("mysql:host=localhost;dbname=poet_site_db;charset=utf8mb4", "root", "");
$message = '';

// 1. ОБРАБОТКА ДОБАВЛЕНИЯ НОВОСТИ С КАРТИНКОЙ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_news'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_name = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $image_name = uniqid() . '.' . $ext; 
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
        }
    }

    $stmt = $pdo->prepare("INSERT INTO news (title, content, image, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt->execute([$title, $content, $image_name])) {
        $message = "Новость успешно добавлена!";
    }
}

// 2. УДАЛЕНИЕ НОВОСТИ
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists('uploads/' . $img)) { unlink('uploads/' . $img); }

    $pdo->prepare("DELETE FROM news WHERE id = ?")->execute([$id]);
    $message = "Новость удалена.";
}

$news_items = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление новостями</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #121411; color: #f4f1ea; padding: 40px; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { color: #7d9472; font-family: 'Caveat', cursive; font-size: 42px; }
        .nav-links { margin-bottom: 20px; display: flex; justify-content: space-between; }
        .nav-links a { color: #fff; text-decoration: none; border-bottom: 2px solid #7d9472; padding-bottom: 5px; }
        
        .news-form { background: #1e231e; padding: 25px; border-radius: 10px; margin-bottom: 30px; border: 1px solid rgba(255,255,255,0.1); }
        .news-form input, .news-form textarea { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #7d9472; background: #121411; color: #fff; box-sizing: border-box; }
        .news-form button { padding: 12px 25px; background: #7d9472; color: #fff; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        
        .admin-news-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .admin-news-card { background: #1e231e; padding: 15px; border-radius: 10px; border-left: 4px solid #7d9472; position: relative; }
        .admin-news-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }
        .btn-del { position: absolute; top: 15px; right: 15px; background: #ff6b6b; color: #fff; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-links">
            <?php if ($_SESSION['role'] === 'super_admin'): ?>
                <a href="admin.php">← Назад к заявкам</a>
            <?php else: ?>
                <span style="color: #7d9472; font-weight: bold; border-bottom: 2px solid #7d9472; padding-bottom: 5px;">Панель редактора</span>
            <?php endif; ?>
            <a href="logout.php" style="color: #ff6b6b; border-color: #ff6b6b;">Выйти</a>
        </div>
        
        <h1>Управление новостями</h1>
        <?php if ($message): ?><p style="color: #4CAF50;"><?= $message ?></p><?php endif; ?>

        <form class="news-form" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Заголовок новости" required>
            <textarea name="content" rows="4" placeholder="Текст новости..." required></textarea>
            <label style="display:block; margin-bottom:5px; color:#b0b5aa;">Прикрепить картинку или GIF:</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="add_news" style="margin-top:15px;">Опубликовать новость</button>
        </form>

        <h2 style="font-size: 24px; margin-bottom: 20px;">Опубликованные новости:</h2>
        <div class="admin-news-grid">
            <?php foreach ($news_items as $item): ?>
                <div class="admin-news-card">
                    <a href="?delete=<?= $item['id'] ?>" class="btn-del" onclick="return confirm('Удалить новость?');">Удалить</a>
                    <?php if ($item['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Фото">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p style="font-size: 14px; color: #b0b5aa;"><?= nl2br(htmlspecialchars($item['content'])) ?></p>
                    <small style="color: #7d9472;"><?= date('d.m.Y', strtotime($item['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>