<?php

enum Role
{
    case Admin;
    case Dispatcher;
    case Driver;
}

class User
{
    public int $id;
    public Role $role;
    public string $firstName;
    public string $lastName;

    public function __construct(int $id, string $firstName, string $lastName)
    {
        $this->id               = $id;
        $this->firstName        = $firstName;
        $this->lastName         = $lastName;
        $this->role             = Role::Dispatcher;
    }
}

