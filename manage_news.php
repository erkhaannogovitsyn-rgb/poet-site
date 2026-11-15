<?php
require_once __DIR__ . '/auth.php';
requireAdmin();

if (!in_array($_SESSION['role'] ?? '', ['super_admin', 'editor'], true)) {
    http_response_code(403);
    exit('Доступ запрещён.');
}

$message = '';
$error = '';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();

    if (isset($_POST['add_news'])) {
        $title = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $imageName = null;

        if ($title === '' || $content === '' || mb_strlen($title) > 255) {
            $error = 'Заполните заголовок и текст новости.';
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['image'];
            $allowedMimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];
            $mimeType = $file['error'] === UPLOAD_ERR_OK && $file['size'] <= 5 * 1024 * 1024
                ? (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name'])
                : false;

            if (!is_string($mimeType) || !isset($allowedMimeTypes[$mimeType])) {
                $error = 'Загрузите изображение JPG, PNG, GIF или WebP размером до 5 МБ.';
            } else {
                $uploadDir = __DIR__ . '/uploads';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    $error = 'Не удалось подготовить папку для изображений.';
                } else {
                    $imageName = bin2hex(random_bytes(16)) . '.' . $allowedMimeTypes[$mimeType];
                    if (!move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $imageName)) {
                        $error = 'Не удалось сохранить изображение.';
                    }
                }
            }
        }

        if ($error === '') {
            $stmt = $pdo->prepare(
                'INSERT INTO news (title, content, image) VALUES (:title, :content, :image)'
            );
            $stmt->execute(['title' => $title, 'content' => $content, 'image' => $imageName]);
            $message = 'Новость добавлена.';
        }
    } elseif (isset($_POST['delete_news'])) {
        $id = filter_var($_POST['news_id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $pdo->prepare('SELECT image FROM news WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $imageName = $stmt->fetchColumn();
            $delete = $pdo->prepare('DELETE FROM news WHERE id = :id');
            $delete->execute(['id' => $id]);
            if ($imageName && preg_match('/^[a-f0-9]{32}\.(jpg|png|gif|webp)$/', $imageName)) {
                $imagePath = __DIR__ . '/uploads/' . $imageName;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }
            }
            $message = 'Новость удалена.';
        } else {
            $error = 'Не удалось определить новость для удаления.';
        }
    }
}

$newsItems = $pdo->query('SELECT id, title, content, image, created_at FROM news ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление новостями</title>
    <style>
        * { box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background:#121411; color:#f4f1ea; padding:24px; margin:0; }
        .container { max-width:900px; margin:0 auto; }
        h1 { color:#7d9472; font-size:34px; }
        .nav-links { margin-bottom:20px; display:flex; justify-content:space-between; gap:12px; }
        a { color:#fff; }
        .news-form,.admin-news-card { background:#1e231e; padding:20px; border-radius:10px; margin-bottom:20px; border:1px solid rgba(255,255,255,.1); }
        .news-form input,.news-form textarea { width:100%; padding:12px; margin-bottom:12px; border-radius:5px; border:1px solid #7d9472; background:#121411; color:#fff; }
        button { padding:11px 18px; background:#7d9472; color:#fff; border:0; border-radius:5px; font-weight:bold; cursor:pointer; }
        .admin-news-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:16px; }
        .admin-news-card img { width:100%; max-height:220px; object-fit:cover; border-radius:5px; }
        .msg { color:#bfe2b2; } .err { color:#ff9d9d; }
        .delete-form { margin-top:12px; } .delete-form button { background:#9c4b4b; }
    </style>
</head>
<body>
<main class="container">
    <nav class="nav-links">
        <?php if (($_SESSION['role'] ?? '') === 'super_admin'): ?><a href="admin.php">← Подписчики</a><?php endif; ?>
        <a href="logout.php">Выйти</a>
    </nav>
    <h1>Управление новостями</h1>
    <?php if ($message !== ''): ?><p class="msg" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="err" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form class="news-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="text" name="title" maxlength="255" placeholder="Заголовок новости" required>
        <textarea name="content" rows="4" placeholder="Текст новости..." required></textarea>
        <label for="image">Изображение (до 5 МБ, необязательно)</label>
        <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
        <button type="submit" name="add_news">Опубликовать новость</button>
    </form>
    <h2>Опубликованные новости</h2>
    <?php if ($newsItems): ?>
        <div class="admin-news-grid">
            <?php foreach ($newsItems as $item): ?>
                <article class="admin-news-card">
                    <?php if ($item['image']): ?><img src="uploads/<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Изображение новости"><?php endif; ?>
                    <h3><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= nl2br(htmlspecialchars($item['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <small><?= htmlspecialchars(date('d.m.Y', strtotime($item['created_at'])), ENT_QUOTES, 'UTF-8') ?></small>
                    <form class="delete-form" method="POST" onsubmit="return confirm('Удалить новость?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="news_id" value="<?= (int)$item['id'] ?>">
                        <button type="submit" name="delete_news">Удалить</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Новостей пока нет.</p>
    <?php endif; ?>
</main>
</body>
</html>
