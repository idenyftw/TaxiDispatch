<?php

require_once __DIR__ . '/../db/db-conn.php';

class VehicleType {

    public int $id;
    public string $nameEn;
    public string $nameFr;

    public function __construct(int $id, string $nameEn, string $nameFr)
    {
        $this->id           = $id;
        $this->nameEn      = $nameEn;
        $this->nameFr      = $nameFr;
    }
}

class Vehicle
{
    public int $id;
    public string $licensePlate;
    
    public VehicleType $type;

    public function __construct(int $id, string $licensePlate, VehicleType $type)
    {
        $this->id           = $id;
        $this->licensePlate = $licensePlate;
        $this->type         = $type;
    }

    public static function getAllVehicles(): array
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

    public static function getVehicleById(int $id)
    {
        try {
            $pdo = connDB();
            $sql = "SELECT * FROM Vehicles v
                    INNER JOIN VehicleType t ON v.type_id = t.type_id
                    WHERE v.vehicle_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $vehicleArray = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($vehicleArray === false) {
                return null;
            }

            $type = new VehicleType(
                $vehicleArray['type_id'],
                $vehicleArray['name_en'],
                $vehicleArray['name_fr']
            );

            return new Vehicle(
                $vehicleArray['vehicle_id'],
                $vehicleArray['license_plate'],
                $type
            );

        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
}