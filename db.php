<?php

$db = 'mysql:host=localhost;dbname=palladium';

$user = 'admin';
$password = 'admin';

try
{
    $pdo = new PDO($db, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    die("Не удалось подключиться к бд: " . $e->getMessage());
}

?>