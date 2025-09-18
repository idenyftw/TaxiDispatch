<?php

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
}