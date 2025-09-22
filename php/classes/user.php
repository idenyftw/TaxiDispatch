<?php

enum Role: string
{
    case Admin = 'Admin';
    case Dispatcher = 'Dispatcher';
    case Driver = 'Driver';
}
class User
{
    public int $id;
    public Role $role;
    public string $firstName;
    public string $lastName;

    public function __construct(int $id, string $firstName, string $lastName, Role $role)
    {
        $this->id               = $id;
        $this->firstName        = $firstName;
        $this->lastName         = $lastName;
        $this->role             = $role;
    }
}

