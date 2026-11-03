<?php
session_start();

// Защита: пускаем только Супер-админа
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['role'] !== 'super_admin') {
    die("<h2 style='color:red; text-align:center; margin-top:50px; font-family:sans-serif;'>Доступ запрещен. Вы не Супер-админ.</h2>");
}

$pdo = new PDO("mysql:host=localhost;dbname=poet_site_db;charset=utf8mb4", "root", "");
$message = '';
$error = '';

// 1. ЛОГИКА УДАЛЕНИЯ ПОЛЬЗОВАТЕЛЯ
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    
    // Проверка: нельзя удалить самого себя
    if ($delete_id === $_SESSION['user_id']) {
        $error = "Вы не можете удалить самого себя!";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$delete_id])) {
            $message = "Пользователь успешно удален!";
        }
    }
}

// 2. ЛОГИКА ДОБАВЛЕНИЯ ПОЛЬЗОВАТЕЛЯ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $new_user = $_POST['new_username'];
    $new_pass = $_POST['new_password'];
    $new_role = $_POST['new_role']; 

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$new_user, $new_pass, $new_role])) {
        $message = "Пользователь $new_user успешно добавлен!";
    }
}

// Обновляем свою активность (я онлайн)
$update = $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
$update->execute([$_SESSION['user_id']]);

// Получаем список всех пользователей
$users = $pdo->query("SELECT * FROM users ORDER BY role DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление персоналом</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #121411; color: #f4f1ea; padding: 40px; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { color: #7d9472; font-family: 'Caveat', cursive; font-size: 42px; }
        .nav-links { margin-bottom: 20px; display: flex; justify-content: space-between; }
        .nav-links a { color: #fff; text-decoration: none; border-bottom: 2px solid #7d9472; padding-bottom: 5px;}
        
        .add-form { background: #1e231e; padding: 20px; border-radius: 10px; margin-bottom: 30px; display: flex; gap: 10px; flex-wrap: wrap; border: 1px solid rgba(255,255,255,0.1); }
        .add-form input, .add-form select { padding: 10px; border-radius: 5px; border: 1px solid #7d9472; background: #121411; color: #fff; flex: 1; }
        .add-form button { padding: 10px 20px; background: #7d9472; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; background: #1e231e; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { background: #7d9472; color: #fff; }
        .online { color: #4CAF50; font-weight: bold; }
        .offline { color: #ff6b6b; font-weight: bold; }
        
        .msg { color: #4CAF50; margin-bottom: 15px; font-weight: bold; }
        .err { color: #ff6b6b; margin-bottom: 15px; font-weight: bold; }
        
        .btn-delete { color: #ff6b6b; text-decoration: none; font-weight: bold; border: 1px solid #ff6b6b; padding: 5px 10px; border-radius: 5px; font-size: 12px; transition: 0.3s; }
        .btn-delete:hover { background: #ff6b6b; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-links">
            <a href="admin.php">← Вернуться к заявкам</a>
            <a href="logout.php" style="color: #ff6b6b; border-color: #ff6b6b;">Выйти</a>
        </div>
        
        <h1>Управление персоналом</h1>
        
        <?php if ($message): ?><div class="msg"><?= $message ?></div><?php endif; ?>
        <?php if ($error): ?><div class="err"><?= $error ?></div><?php endif; ?>

        <form method="POST" class="add-form">
            <input type="text" name="new_username" placeholder="Логин" required>
            <input type="text" name="new_password" placeholder="Пароль" required>
            <select name="new_role">
                <option value="operator">Оператор</option>
                <option value="editor">Редактор</option> <!-- НОВАЯ РОЛЬ -->
                <option value="super_admin">Супер-админ</option>
            </select>
            <button type="submit" name="add_user">Добавить</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Имя (Логин)</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($users as $u): 
                $is_online = false;
                if ($u['last_activity']) {
                    if (time() - strtotime($u['last_activity']) < 300) { $is_online = true; }
                }
            ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td>
                    <?php 
                        if ($u['role'] === 'super_admin') echo 'Главный админ';
                        elseif ($u['role'] === 'editor') echo 'Редактор';
                        else echo 'Оператор';
                    ?>
                </td>
                <td>
                    <?php if ($is_online): ?>
                        <span class="online">✔ Онлайн</span>
                    <?php else: ?>
                        <span class="offline">✖ Оффлайн</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                        <a href="?delete=<?= $u['id'] ?>" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить пользователя <?= htmlspecialchars($u['username']) ?>?');">Удалить</a>
                    <?php else: ?>
                        <span style="color: #b0b5aa; font-size: 12px;">(Это вы)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>