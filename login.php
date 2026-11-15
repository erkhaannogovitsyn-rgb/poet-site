<?php
require_once __DIR__ . '/auth.php';
startAppSession();

if (($_SESSION['admin_logged_in'] ?? false) === true) {
    header('Location: admin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    try {
        $pdo = db();
        ensureAdminAccount($pdo);
        $stmt = $pdo->prepare(
            'SELECT id, username, password_hash, role FROM users WHERE username = :username'
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $update = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = :id');
            $update->execute(['id' => $user['id']]);
            header('Location: admin.php');
            exit;
        }
        $error = 'Неверный логин или пароль.';
    } catch (Throwable $e) {
        error_log('Admin login failed: ' . $e->getMessage());
        $error = 'Вход временно недоступен. Попробуйте позже.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход в админ-панель</title>
    <style>
        body { font-family: Arial, sans-serif; background:#121411; color:#f4f1ea; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; padding:20px; box-sizing:border-box; }
        .login-box { background:#1e231e; padding:40px; border-radius:15px; border:1px solid #7d9472; width:min(340px, 100%); text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.5); box-sizing:border-box; }
        h1 { color:#7d9472; margin:0 0 20px; font-size:28px; }
        input { width:100%; padding:12px; margin-bottom:15px; border-radius:50px; border:1px solid rgba(255,255,255,.2); background:rgba(0,0,0,.3); color:#fff; box-sizing:border-box; }
        button { width:100%; padding:12px; border-radius:50px; border:none; background:#7d9472; color:#fff; font-weight:bold; cursor:pointer; }
        .error { color:#ff8a8a; margin-bottom:15px; }
        a { display:inline-block; color:#b0b5aa; margin-top:18px; }
    </style>
</head>
<body>
    <main class="login-box">
        <h1>Вход в систему</h1>
        <?php if ($error !== ''): ?><p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="text" name="username" placeholder="Логин" autocomplete="username" required>
            <input type="password" name="password" placeholder="Пароль" autocomplete="current-password" required>
            <button type="submit">Войти</button>
        </form>
        <a href="index.php">Вернуться на сайт</a>
    </main>
</body>
</html>
