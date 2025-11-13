<?php

require_once __DIR__ . '/../db/db-conn.php';

require_once 'driver.php';
require_once 'vehicle.php';
require_once 'zone.php';

enum TripStatus: string
{
    case Cancelled  = 'cancelled';
    case Ended      = 'ended';
    case Ongoing    = 'ongoing';
    case AwaitingConfirmation = 'awaiting_confirmation';
}

class Trip
{
    public int $id;

    public ?DateTime $startTime;
    public ?DateTime $endTime;
    public TripStatus $status;
    public float $lengthKm;

    public ?Zone $zoneStart;
    public ?Zone $zoneEnd;
    public ?Driver $driver;
    public ?Vehicle $vehicle;

    public function __construct(
        int $id, 
        ?DateTime $startTime, 
        ?DateTime $endTime,
        TripStatus $status,
        float $lengthKm,
        ?Zone $zoneStart,
        ?Zone $zoneEnd,
        ?Driver $driver,
        ?Vehicle $vehicle
    )
    {
        $this->id           = $id;
        $this->startTime    = $startTime;
        $this->endTime      = $endTime;
        $this->status       = $status;
        $this->lengthKm     = $lengthKm;
        $this->zoneStart    = $zoneStart;
        $this->zoneEnd      = $zoneEnd;
        $this->driver       = $driver;
        $this->vehicle      = $vehicle;
    }

    public static function getAllTrips(): array
    {
        $pdo = connDB();

        $stmt = $pdo->prepare('SELECT * FROM Trips;');
        $stmt->execute();

        return array_map(
            fn($row) => Trip::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public static function getAllOrders(): array
    {
        $pdo = connDB();

        $stmt = $pdo->prepare('SELECT * FROM Trips WHERE status = "awaiting_confirmation";');
        $stmt->execute();

        return array_map(
            fn($row) => Trip::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    private static function fromArray(array $row): self
    {
        $status = TripStatus::tryFrom($row['status']) ?? TripStatus::Cancelled;

        return new self(
            (int)$row['trip_id'],
            $row['start_time'] ? new DateTime($row['start_time']) : null,
            $row['end_time'] ? new DateTime($row['end_time']) : null,
            $status,    
            (float)$row['length'],
            $row['start_zone_id'] ? Zone::getZoneById($row['start_zone_id']) : null,
            $row['end_zone_id'] ?   Zone::getZoneById($row['end_zone_id']) : null,
            $row['driver_id'] ?     Driver::getDriverById($row['driver_id']) : null,
            $row['vehicle_id'] ?    Vehicle::getVehicleById($row['vehicle_id']) : null
        );
    }
    //Fonction qui va update le trips voulu en ajoutant le status la date le driver en fonction du trip
    public static function acceptTrip(int $id, int $trip){
        $pdo = connDB();
        $stmt = $pdo->prepare("UPDATE `Trips` SET `start_time`=:startTime, `status`=:status, `driver_id`=:driver WHERE `Trips`.`trip_id`=:trip");
        $stmt->execute([
            ":startTime" => date("Y-m-d H:i:s"),
            ":status" => "ongoing",
            ":driver" => $id,
            ":trip" => $trip
        ]);
        return true;
    }
    //------------
}

