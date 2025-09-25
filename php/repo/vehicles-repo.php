<?php 

require_once __DIR__ . '/../db/db-conn.php';
require_once __DIR__ . '/../classes/vehicle.php';

function getAllVehicles(): array
{
    try {

        $pdo = connDB();
        $sql = "SELECT * FROM Vehicles v
                INNER JOIN VehicleType t ON v.type_id = t.type_id ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $vehiclesArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $vehicles = [];

        foreach ($vehiclesArray as $vehicleArray) {

            $type = new VehicleType($vehicleArray['type_id'], $vehicleArray['name_en'], $vehicleArray['name_fr']);

            $vehicles[] = new Vehicle($vehicleArray['vehicle_id'], $vehicleArray['license_plate'], $type);
        }

        return $vehicles;

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}


