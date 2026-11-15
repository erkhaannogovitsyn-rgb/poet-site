<?php
require_once __DIR__ . '/auth.php';
requireAdmin();

if (($_SESSION['role'] ?? '') === 'editor') {
    header('Location: manage_news.php');
    exit;
}

try {
    $pdo = db();
    $update = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = :id');
    $update->execute(['id' => $_SESSION['user_id']]);
    $leads = $pdo->query('SELECT id, created_at, method, contact FROM leads ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
    error_log('Admin dashboard failed: ' . $e->getMessage());
    http_response_code(503);
    exit('Панель временно недоступна. Попробуйте обновить страницу позже.');
}
$methodNames = ['email' => 'E-mail', 'telegram' => 'Telegram', 'max' => 'Max'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель — Подписчики</title>
    <style>
        * { box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background:#121411; color:#f4f1ea; padding:24px; margin:0; }
        .admin-header { display:flex; justify-content:space-between; align-items:center; max-width:1000px; margin:0 auto 24px; border-bottom:1px solid rgba(255,255,255,.1); padding-bottom:18px; flex-wrap:wrap; gap:16px; }
        .header-left,.header-right { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        h1 { color:#7d9472; margin:0; font-size:32px; }
        .btn { background:#7d9472; color:#fff; text-decoration:none; padding:10px 15px; border-radius:50px; font-weight:bold; font-size:13px; }
        .user-info { color:#b0b5aa; font-size:14px; }
        .logout-btn { color:#ff8a8a; text-decoration:none; border:1px solid #ff8a8a; padding:8px 15px; border-radius:50px; }
        .table-wrap { max-width:1000px; margin:auto; overflow-x:auto; }
        table { width:100%; border-collapse:collapse; background:#1e231e; border-radius:10px; overflow:hidden; }
        th,td { padding:14px 16px; text-align:left; border-bottom:1px solid rgba(255,255,255,.06); }
        th { background:#7d9472; color:#fff; }
        .empty { text-align:center; color:#b0b5aa; margin-top:50px; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="header-left">
            <h1>Подписчики</h1>
            <?php if (($_SESSION['role'] ?? '') === 'super_admin'): ?>
                <a href="manage_users.php" class="btn">Персонал</a>
                <a href="manage_news.php" class="btn">Новости</a>
            <?php endif; ?>
        </div>
        <div class="header-right">
            <span class="user-info">Вы: <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></span>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
    </header>
    <?php if ($leads): ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Дата и время</th><th>Способ связи</th><th>Контакт</th></tr></thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><?= (int)$lead['id'] ?></td>
                            <td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($lead['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($methodNames[$lead['method']] ?? $lead['method'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($lead['contact'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty">Пока нет ни одной заявки.</p>
    <?php endif; ?>
</body>
</html>
