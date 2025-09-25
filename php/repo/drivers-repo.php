<?php 

require_once __DIR__ . '/../db/db-conn.php';
require_once __DIR__ . '/../classes/driver.php';

function getAllDrivers(): array
{
    try {
        $pdo = connDB();
        $sql = "SELECT * FROM Drivers";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $driversArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $drivers = [];

        foreach ($driversArray as $driver) {
            $drivers[] = new Driver($driver['driver_id'], $driver['first_name'], $driver['last_name']);
        }

        return $drivers;

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}


