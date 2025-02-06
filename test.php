<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palladium test task</title>
    <link rel="stylesheet" href="styles.css"> <!-- Путь к CSS-файлу -->
</head>
<?php
require 'function.php';

addUser("name 1", "email1@email.ru");
addUser("name 2", "email2@email.ru");
addUser("name 3", "email3@email.ru");

$users = getUsers();
echo "Первый вывод пользователей: <br><br>";
displayUsersTable($users);

updateUser(1, 'namee1', 'email@mail.com');

deleteUser(2);

$users = getUsers();
echo "<br><br> Второй вывод пользователей: <br><br>";
displayUsersTable($users);

addGroup("Администраторы");
addGroup("Все пользователи");
addGroup("Зарегистрированные пользователи");

$groups[] = 2;
$groups[] = 3;

addToGroup(1, $groups);

$users = getUsers();
echo "<br><br> Третий вывод пользователей: <br><br>";
displayUsersTable($users);

?>