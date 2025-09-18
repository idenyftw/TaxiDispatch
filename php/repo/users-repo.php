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