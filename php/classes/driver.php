<?php

class Driver
{
    int $id;
    string $firstName;
    string $lastName;

    public function __construct(int $id, string $firstName, int $lastName)
    {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
    }
}