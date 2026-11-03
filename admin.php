<?php
// 1. ЗАЩИТА СТРАНИЦЫ И ПРОВЕРКА СЕССИИ
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit; 
}

// 2. ЕСЛИ ЭТО РЕДАКТОР — ПЕРЕНАПРАВЛЯЕМ ЕГО НА НОВОСТИ
if ($_SESSION['role'] === 'editor') {
    header("Location: manage_news.php");
    exit;
}

// 3. ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ
$host = 'localhost';
$dbname = 'poet_site_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Обновляем статус "Онлайн" для текущего пользователя
    $upd = $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
    $upd->execute([$_SESSION['user_id']]);

    // Получаем заявки (лиды)
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель — Заявки</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #121411; color: #f4f1ea; padding: 40px; margin: 0; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: 0 auto 30px auto; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .header-left { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        h1 { color: #7d9472; margin: 0; font-family: 'Caveat', cursive; font-size: 42px; }
        
        .btn-manage { background: #7d9472; color: #fff; text-decoration: none; padding: 10px 15px; border-radius: 50px; font-weight: bold; font-size: 13px; transition: 0.3s; }
        .btn-manage:hover { background: #93ad86; }
        .btn-news { background: #5a7d5a; }
        .btn-news:hover { background: #6f9c6f; }

        .user-info { color: #b0b5aa; font-size: 14px; margin-right: 15px; }
        .logout-btn { background-color: transparent; color: #ff6b6b; text-decoration: none; font-weight: bold; border: 1px solid #ff6b6b; padding: 8px 15px; border-radius: 50px; transition: all 0.3s; }
        .logout-btn:hover { background-color: #ff6b6b; color: #fff; }

        table { width: 100%; max-width: 1000px; margin: 0 auto; border-collapse: collapse; background: #1e231e; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        th { background-color: #7d9472; color: #fff; font-weight: 600; }
        tr:hover { background-color: rgba(255,255,255,0.02); }
        .empty { text-align: center; font-size: 18px; color: #b0b5aa; margin-top: 50px; }
    </style>
</head>
<body>

    <div class="admin-header">
        <div class="header-left">
            <h1>Заявки</h1>
            <?php if ($_SESSION['role'] === 'super_admin'): ?>
                <a href="manage_users.php" class="btn-manage">Персонал</a>
                <a href="manage_news.php" class="btn-manage btn-news">Новости</a>
            <?php endif; ?>
        </div>
        
        <div style="display: flex; align-items: center;">
            <span class="user-info">
                Вы: <?= htmlspecialchars($_SESSION['username']) ?> 
                (<?= $_SESSION['role'] === 'super_admin' ? 'Админ' : 'Оператор' ?>)
            </span>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
    </div>

    <?php
    if (count($leads) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Дата и время</th><th>Способ связи</th><th>Контакт</th></tr>";
        foreach ($leads as $lead) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($lead['id']) . "</td>";
            echo "<td>" . htmlspecialchars($lead['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($lead['method']) . "</td>";
            echo "<td>" . htmlspecialchars($lead['contact']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='empty'>Пока нет ни одной заявки.</p>";
    }
    ?>
</body>
</html>