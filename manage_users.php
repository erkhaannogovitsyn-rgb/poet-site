<?php
require_once __DIR__ . '/auth.php';
requireAdmin();
if (($_SESSION['role'] ?? '') !== 'super_admin') {
    http_response_code(403);
    exit('Доступ запрещён.');
}

$pdo = db();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    if (isset($_POST['add_user'])) {
        $username = trim((string)($_POST['new_username'] ?? ''));
        $password = (string)($_POST['new_password'] ?? '');
        $role = (string)($_POST['new_role'] ?? 'operator');

        if (!preg_match('/^[a-zA-Z0-9_.-]{3,100}$/', $username)) {
            $error = 'Логин должен содержать 3–100 латинских букв, цифр или символов . _ -.';
        } elseif (strlen($password) < 8) {
            $error = 'Пароль нового пользователя должен содержать не менее 8 символов.';
        } elseif (!in_array($role, ['operator', 'editor', 'super_admin'], true)) {
            $error = 'Выбрана неизвестная роль.';
        } else {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)'
                );
                $stmt->execute([
                    'username' => $username,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role,
                ]);
                $message = 'Пользователь добавлен.';
            } catch (PDOException $e) {
                $error = 'Не удалось добавить пользователя. Возможно, такой логин уже занят.';
            }
        }
    } elseif (isset($_POST['delete_user'])) {
        $id = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id === (int)$_SESSION['user_id']) {
            $error = 'Нельзя удалить текущего пользователя.';
        } else {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id AND username <> :admin');
            $stmt->execute(['id' => $id, 'admin' => 'admin']);
            $message = $stmt->rowCount() ? 'Пользователь удалён.' : 'Пользователь не найден или это основной аккаунт.';
        }
    }
}

$users = $pdo->query('SELECT id, username, role, last_activity FROM users ORDER BY id')->fetchAll();
$roleNames = ['super_admin' => 'Главный администратор', 'editor' => 'Редактор', 'operator' => 'Оператор'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление персоналом</title>
    <style>
        * { box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background:#121411; color:#f4f1ea; padding:24px; margin:0; }
        .container { max-width:900px; margin:0 auto; }
        h1 { color:#7d9472; font-size:34px; }
        .nav-links { margin-bottom:20px; display:flex; justify-content:space-between; }
        a { color:#fff; }
        .add-form { background:#1e231e; padding:20px; border-radius:10px; margin-bottom:24px; display:flex; gap:10px; flex-wrap:wrap; }
        .add-form input,.add-form select { padding:10px; border-radius:5px; border:1px solid #7d9472; background:#121411; color:#fff; flex:1; min-width:160px; }
        button { padding:10px 16px; background:#7d9472; color:#fff; border:0; border-radius:5px; cursor:pointer; font-weight:bold; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; background:#1e231e; }
        th,td { padding:12px; text-align:left; border-bottom:1px solid rgba(255,255,255,.1); }
        th { background:#7d9472; }
        .msg { color:#bfe2b2; } .err { color:#ff9d9d; }
        .delete-button { background:#9c4b4b; }
    </style>
</head>
<body>
<main class="container">
    <nav class="nav-links"><a href="admin.php">← К подписчикам</a><a href="logout.php">Выйти</a></nav>
    <h1>Управление персоналом</h1>
    <?php if ($message !== ''): ?><p class="msg" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="err" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="POST" class="add-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="text" name="new_username" placeholder="Логин" required>
        <input type="password" name="new_password" placeholder="Пароль (от 8 символов)" minlength="8" required>
        <select name="new_role" aria-label="Роль">
            <option value="operator">Оператор</option>
            <option value="editor">Редактор</option>
            <option value="super_admin">Супер-администратор</option>
        </select>
        <button type="submit" name="add_user">Добавить</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>ID</th><th>Логин</th><th>Роль</th><th>Активность</th><th>Действия</th></tr></thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int)$user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($roleNames[$user['role']] ?? $user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $user['last_activity'] ? htmlspecialchars(date('d.m.Y H:i', strtotime($user['last_activity'])), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                        <td>
                            <?php if ((int)$user['id'] !== (int)$_SESSION['user_id'] && $user['username'] !== 'admin'): ?>
                                <form method="POST" onsubmit="return confirm('Удалить этого пользователя?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                    <button class="delete-button" type="submit" name="delete_user">Удалить</button>
                                </form>
                            <?php else: ?>Основной аккаунт<?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
