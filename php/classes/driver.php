<?php

require_once __DIR__ . '/../db/db-conn.php';

class Driver
{
    public int $id;
    public string $firstName;
    public string $lastName;

    public function __construct(int $id, string $firstName, string $lastName)
    {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
    }

    public static function getAllDrivers(): array
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

    public static function getDriverById(int $id)
    {
        try {
            $pdo = connDB();
            $sql = "SELECT * FROM Drivers WHERE driver_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $driver = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($driver === false) {
                return null;
            }

            return new Driver(
                $driver['driver_id'],
                $driver['first_name'],
                $driver['last_name']
            );

        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
}