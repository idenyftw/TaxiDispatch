<?php

enum TripStatus
{
    case Cancelled;
    case Ended;
    case Ongoing;
    case AwaitingConfirmation;
}

class Trip
{
    int $id;

    DateTime $startTime;
    DateTime $startTime;

    TripStatus $status;

    double $lengthKm;

    int $zoneStartId;
    int $zoneEndId;
    int $driverId;
    int $vehicleId;

    public function __construct(int $id, string $firstName, int $lastName)
    {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
    }
}

