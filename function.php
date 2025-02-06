<?php
require 'db.php';

function addUser($name, $email)
{
    global $pdo;
    $request = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $request->execute(['email' => $email]);
    $exists = $request->fetchColumn();

    if ($exists)
    {
        return "Email уже используется!";
    }

    $request = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
    $request->execute(['name' => $name, 'email' => $email]);
    return "Пользователь добавлен!";
}

function getUsers()
{
    global $pdo;
    $users = $pdo->query("
        SELECT u.id_user, u.name, u.email, u.created_at, u.updated_at, GROUP_CONCAT(tg.name SEPARATOR ', ') AS `groups`
        FROM users u
        LEFT JOIN group_user gu ON u.id_user = gu.id_user
        LEFT JOIN type_groups tg ON gu.id_group = tg.id_group
        GROUP BY u.id_user
        ORDER BY u.created_at DESC
    ");
    return $users->fetchAll(PDO::FETCH_ASSOC);
}

function displayUsersTable($users)
{
    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Имя</th> 
            <th>Email</th> 
            <th>Дата создания</th> 
            <th>Дата обновления</th> 
            <th>Группы в которых состоит пользователь</th> 
          </tr>";
    foreach ($users as $user) {
        echo "<tr class='collapsed'>
                <td>{$user['id_user']}</td>
                <td>{$user['name']}</td>
                <td>{$user['email']}</td>
                <td>{$user['created_at']}</td>
                <td>{$user['updated_at']}</td>
                <td>{$user['groups']}</td>
              </tr>";
    }
    echo "</table>";
}


function updateUser($id, $name, $email)
{
    global $pdo;

    $request = $pdo->prepare("SELECT name, email FROM users WHERE id_user = :id");
    $request->execute(['id' => $id]);
    $user = $request->fetch(PDO::FETCH_ASSOC);

    if ($email !== $user['email']) {
        $emailCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id_user != :id");
        $emailCheck->execute(['email' => $email, 'id' => $id]);
        $exists = $emailCheck->fetchColumn();

        if ($exists) {
            return "Email уже используется!";
        }
    }

    $updateFields = [];
    $updateParams = ['updated_at' => date('Y-m-d H:i:s'), 'id' => $id];
    if ($name !== $user['name']) {
        $updateFields[] = 'name = :name';
        $updateParams['name'] = $name;
    }

    if ($email !== $user['email']) {
        $updateFields[] = 'email = :email';
        $updateParams['email'] = $email;
    }

    if (empty($updateFields)) {
        return "Нет изменений для обновления.";
    }

    $request = $pdo->prepare("UPDATE users SET " . implode(', ', $updateFields) . " WHERE id_user = :id");
    $request->execute($updateParams);
    return "Пользователь обновлен!";
}

function deleteUser($id)
{
    global $pdo;

    $request = $pdo->prepare("DELETE FROM users WHERE id_user = :id");
    $request->execute(['id' => $id]);
    return "Пользователь удален!";
}

function addGroup($name)
{
    global $pdo;

    $request = $pdo->prepare("SELECT COUNT(*) FROM type_groups WHERE name = :name");
    $request->execute(['name' => $name]);
    $exists = $request->fetchColumn();

    if ($exists)
    {
        return "Такой тип группы уже есть!";
    }

    $request = $pdo->prepare("INSERT INTO type_groups (name) VALUES (:name)");
    $request->execute(['name' => $name]);
    return "Группа добавлена!";
}

function addToGroup($id_user, $groups)
{
    global $pdo;

    foreach ($groups as $group)
    {
        $request = $pdo->prepare("SELECT COUNT(*) FROM group_user WHERE id_user = :id_user AND id_group != :id_group");
        $request->execute(['id_user' => $id_user, 'id_group' => $group]);
        $exists = $request->fetchColumn();

        if ($exists)
        {
            return "Такая запись уже есть!";
        }

        $request = $pdo->prepare("INSERT INTO group_user (id_user, id_group) VALUES (:id_user, :id_group)");
        $request->execute(['id_user' => $id_user, 'id_group' => $group]);
    }
}

?>