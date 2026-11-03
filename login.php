<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Подключение к БД
    $pdo = new PDO("mysql:host=localhost;dbname=poet_site_db;charset=utf8mb4", "root", "");
    
    // Ищем пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Если нашли — сохраняем данные в сессию
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; 
        $_SESSION['username'] = $user['username'];
        
        // Обновляем время последней активности (для статуса Онлайн)
        $update = $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
        $update->execute([$user['id']]);

        header("Location: admin.php");
        exit;
    } else {
        $error = 'Неверный логин или пароль!';
    }
}
?>
<!-- HTML часть остается без изменений (как в прошлом коде) -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админ-панель</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #121411; color: #f4f1ea; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #1e231e; padding: 40px; border-radius: 15px; border: 1px solid #7d9472; width: 300px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { color: #7d9472; margin-bottom: 20px; font-family: 'Caveat', cursive; font-size: 36px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: #fff; box-sizing: border-box; outline: none; }
        input:focus { border-color: #7d9472; }
        button { width: 100%; padding: 12px; border-radius: 50px; border: none; background: #7d9472; color: #fff; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #93ad86; }
        .error { color: #ff6b6b; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Вход в систему</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>