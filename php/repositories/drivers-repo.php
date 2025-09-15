<?php 

require_once "php/db/db-conn.php";
require_once "php/classes/driver.php";

function getAllVehicles(): array
{
    try {

        $pdo = connexionDB();
        $sql = "SELECT * FROM Drivers";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $driversArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $drivers = [];

        foreach ($driversArray as $driver) {
            $drivers[] = new Driver($driver['driver_id'], $driver['first_name'], $driver['last_name']);
        }

        return $vehicles;

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}


