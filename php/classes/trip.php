<?php

require_once __DIR__ . '/../db/db-conn.php';

require_once 'driver.php';
require_once 'vehicle.php';
require_once 'zone.php';

enum TripStatus
{
    case Cancelled;
    case Ended;
    case Ongoing;
    case AwaitingConfirmation;
}

class Trip implements JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'startTime' => $this->startTime?->format('Y-m-d H:i:s'),
            'endTime'   => $this->endTime?->format('Y-m-d H:i:s'),
            'status'    => $this->status->name,
            'lengthKm'  => $this->lengthKm,
            'zoneStart' => $this->zoneStart ? [
                'id'      => $this->zoneStart->id,
                'name'    => $this->zoneStart->name,
                'zipCode' => $this->zoneStart->zipCode,
            ] : null,
            'zoneEnd' => $this->zoneEnd ? [
                'id'      => $this->zoneEnd->id,
                'name'    => $this->zoneEnd->name,
                'zipCode' => $this->zoneEnd->zipCode,
            ] : null,
            'driver' => $this->driver ? [
                'id'        => $this->driver->id,
                'firstName' => $this->driver->firstName,
                'lastName'  => $this->driver->lastName,
            ] : null,
            'vehicle' => $this->vehicle ? [
                'id'           => $this->vehicle->id,
                'licensePlate' => $this->vehicle->licensePlate,
                'type'         => $this->vehicle->type ? [
                    'id'     => $this->vehicle->type->id,
                    'nameEn' => $this->vehicle->type->nameEn,
                    'nameFr' => $this->vehicle->type->nameFr,
                ] : null,
            ] : null,
        ];
    }

    public static function getAllTrips(): array
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

    public static function getAllOrders(): array
    {
        $pdo = connDB();

        $stmt = $pdo->prepare('SELECT * FROM Trips WHERE status = "awaiting_confirmation"; ');
        $stmt->execute();
        
        $ordersArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $trips = [];

        foreach ($ordersArray as $orderArray) 
        {
            $status = match ($orderArray['status']) 
            {
                'cancelled'             => TripStatus::Cancelled,
                'ended'                 => TripStatus::Ended,
                'ongoing'               => TripStatus::Ongoing,
                'awaiting_confirmation' => TripStatus::AwaitingConfirmation,
                default                 => throw new Exception("Unknown trip status: ".$orderArray['status'])
            };

            // Handle null
            $startTime = !empty($orderArray['start_time'])
                ? new DateTime($orderArray['start_time'])
                : null;

            $endTime = !empty($orderArray['end_time'])
                ? new DateTime($orderArray['end_time'])
                : null;

            $driver  = !empty($orderArray['driver_id'])
                ? getDriverById($orderArray['driver_id'])
                : null;

            $vehicle = !empty($orderArray['vehicle_id'])
                ? getVehicleById($orderArray['vehicle_id'])
                : null;

            $zoneStart = !empty($orderArray['start_zone_id'])
                ? getZoneById($orderArray['start_zone_id'])
                : null;

            $zoneEnd = !empty($orderArray['end_zone_id'])
                ? getZoneById($orderArray['end_zone_id'])
                : null;

            $trips[] = new Trip(
                $orderArray['trip_id'],
                $startTime,
                $endTime,
                $status,
                $orderArray['length'],
                $zoneStart,
                $zoneEnd,
                $driver,
                $vehicle
            );
        }

        return $trips;
    }
}

