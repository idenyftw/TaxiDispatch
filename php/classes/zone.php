<?php

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
}