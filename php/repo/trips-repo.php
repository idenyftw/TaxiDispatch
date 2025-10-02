<?php

require_once __DIR__ . '/../db/db-conn.php';
require_once __DIR__ . '/../classes/trip.php';
require_once 'drivers-repo.php';
require_once 'zones-repo.php';
require_once 'vehicles-repo.php';

function getAllTrips(): array
{
    $pdo = connDB();

    $stmt = $pdo->prepare('SELECT * FROM Trips;');
    $stmt->execute();
    
    $tripsArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $trips = [];

    foreach ($tripsArray as $tripArray) 
    {
        $status = match ($tripArray['status']) 
        {
            'cancelled'             => TripStatus::Cancelled,
            'ended'                 => TripStatus::Ended,
            'ongoing'               => TripStatus::Ongoing,
            'awaiting_confirmation' => TripStatus::AwaitingConfirmation,
            default                 => throw new Exception("Unknown trip status: ".$tripArray['status'])
        };

        // Handle null
        $startTime = !empty($tripArray['start_time'])
            ? new DateTime($tripArray['start_time'])
            : null;

        $endTime = !empty($tripArray['end_time'])
            ? new DateTime($tripArray['end_time'])
            : null;

        $driver  = !empty($tripArray['driver_id'])
            ? getDriverById($tripArray['driver_id'])
            : null;

        $vehicle = !empty($tripArray['vehicle_id'])
            ? getVehicleById($tripArray['vehicle_id'])
            : null;

        $zoneStart = !empty($tripArray['start_zone_id'])
            ? getZoneById($tripArray['start_zone_id'])
            : null;

        $zoneEnd = !empty($tripArray['end_zone_id'])
            ? getZoneById($tripArray['end_zone_id'])
            : null;

        $trips[] = new Trip(
            $tripArray['trip_id'],
            $startTime,
            $endTime,
            $status,
            $tripArray['length'],
            $zoneStart,
            $zoneEnd,
            $driver,
            $vehicle
        );
    }

    return $trips;
}
