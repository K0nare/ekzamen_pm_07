<?php
// config.php - подключение к базе данных
$host = 'localhost';
$user = 'admin';
$pass = 'admin'; // если пароль есть, укажите

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>