<?php
// Настройки подключения к базе данных
$host = 'localhost';
$dbname = 'poet_site_db';
$username = 'root';
$password = ''; // В XAMPP по умолчанию пароль пустой

// Проверяем, что данные пришли через форму
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['privacy_agreed'])) {
        $method = $_POST['contact_method'];
        
        if ($method == 'email') {
            $contact = $_POST['email'];
        } elseif ($method == 'telegram') {
            $contact = $_POST['telegram'];
        } else {
            $contact = $_POST['max'];
        }

        try {
            // Подключаемся к базе данных с помощью PDO
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Готовим запрос на добавление новой записи
            $sql = "INSERT INTO leads (created_at, method, contact) VALUES (NOW(), :method, :contact)";
            $stmt = $pdo->prepare($sql);

            // Выполняем запрос, подставляя данные
            $stmt->execute([
                ':method' => $method,
                ':contact' => $contact
            ]);

            // Показываем сообщение об успехе
            echo "<script>
                    alert('Спасибо! Вы успешно подписались.');
                    window.location.href = 'index.html';
                  </script>";
        } catch (PDOException $e) {
            // Если произошла ошибка при подключении к БД
            echo "Ошибка базы данных: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Ошибка: Необходимо согласие на обработку данных.');</script>";
    }
} else {
    echo "Доступ запрещен.";
}
?>