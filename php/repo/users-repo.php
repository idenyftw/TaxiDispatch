<?php

require_once __DIR__ . '/../db/db-conn.php';
require_once __DIR__ . '/../classes/user.php';

function getUserId($username) : int
{
    $pdo = connDB();

    $stmt = $pdo->prepare('
        SELECT user_id FROM Users WHERE username = ? LIMIT 1;
    ');
    $stmt->execute([$username]);
    $dbResult = $stmt->fetch(PDO::FETCH_ASSOC);

    return $dbResult ? $dbResult['user_id'] : -1;
}

function getUser($token)
{
    $pdo = connDB();

    $stmt = $pdo->prepare('
        SELECT user_id, first_name, last_name, role FROM Users WHERE token = ? LIMIT 1;
    ');

    $stmt->execute([$token]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $roleStr = ucfirst(strtolower($user["role"]));
    $role = Role::from($roleStr);

    return new User($user["user_id"], $user["first_name"], $user["last_name"], $role);
}

function getUserPasswordHash($userId) : string
{
    $pdo = connDB();

    $stmt = $pdo->prepare('
        SELECT password FROM Users WHERE user_id = ? LIMIT 1;
    ');

    $stmt->execute([$userId]);
    $dbResult = $stmt->fetch(PDO::FETCH_ASSOC);

    return $dbResult['password'];
}

function updateUserToken($userId) : string
{
    $pdo = connDB();

    $token = uniqid('', true);
    $pdo = connDB();
    $stmt = $pdo->prepare('UPDATE Users SET token = ? WHERE user_id = ?');
    $stmt->execute([$token, $userId]);

    return $token;
}