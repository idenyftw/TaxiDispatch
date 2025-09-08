<?php

require_once "php/db/db-conn.php";
require_once "php/classes/zone.php";

function getAllZones(): array
{
    try {

        $pdo = connexionDB();
        $sql = "SELECT * FROM Zones";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $zonesArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $postalCodes = [];

        foreach ($zonesArray as $zoneArray) {
            $postalCodes[] = new Zone($zoneArray['zone_id'], $zoneArray['name'], $zoneArray['zip_code']);
        }

        return $postalCodes;

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}

/*
function acheterPlace($id,$places): bool
{
    try {
        $pdo = connexionDB();

        $sql = "UPDATE shows 
                SET seatSold = :seatSold
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':seatSold' => $places+1,
            ':id' => $id
        ]);

        return true;

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}
*/
