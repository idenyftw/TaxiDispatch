<?php

require_once __DIR__ . '/../db/db-conn.php';

class Zone
{
    public int $id;
    public string $name;
    public string $zipCode;

    public function __construct(int $id, string $name, string $zipCode)
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->zipCode      = $zipCode;
    }
    
    public static function getAllZones()
    {
        try {
            $pdo = connDB();
            $sql = "SELECT * FROM Zones";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $zonesArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            $zones = [];

            foreach ($zonesArray as $zoneArray) {
                $zones[] = new Zone($zoneArray['zone_id'], $zoneArray['name'], $zoneArray['zip_code']);
            }

            return $zones;

        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public static function getZoneById(int $id)
    {
        try {
            $pdo = connDB();
            $sql = "SELECT * FROM Zones WHERE zone_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $zoneArray = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($zoneArray === false) {
                return null;
            }

            return new Zone(
                $zoneArray['zone_id'],
                $zoneArray['name'],
                $zoneArray['zip_code']
            );

        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
}