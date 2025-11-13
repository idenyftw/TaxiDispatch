<?php

require_once "db-var.php";

function connDB() {

    try {
        $pdo = new PDO("mysql:host=".HOST.";dbname=".DB_NAME.";charset=utf8", USERNAME, PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::ATTR_EMULATE_PREPARES => false 
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}