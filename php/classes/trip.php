<?php

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

    public function __construct(int $id, 
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
}

