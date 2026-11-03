<?php
session_start();
session_destroy(); // Уничтожаем все данные сессии
header("Location: login.php"); // Перенаправляем на вход
exit;
?>