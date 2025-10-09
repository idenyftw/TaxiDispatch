<?php

require_once __DIR__ . 'driver.php';

enum Role: string
{
    case Admin      = 'admin';
    case Dispatcher = 'dispatcher';
    case Driver     = 'driver';
    case Other      = 'other';
}

class User
{
    public readonly int $id;
    public readonly string $username;
    public readonly Role $role;
    public string $firstName;
    public string $lastName;
    public ?Driver $driver;

    public function __construct(int $id, string $username, string $firstName, string $lastName, Role $role, Driver $driver)
    {
        $this->id        = $id;
        $this->username  = $username;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->role      = $role;
        $this->$driver   = $driver;
    }

    public static function getAllUsers()
    {
        try 
        {
            $pdo = connDB();
            $sql = "SELECT * FROM Users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $usersArray =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = [];

            foreach ($usersArray as $user) {
                
                $userRole = Role::tryFrom($user['role']) ?? Role::Other;

                $driver = Driver::getDriverById($user['driver_id']);

                $users[] = new User($user['user_id'], $user['username'], $user['first_name'], $user['last_name'], $userRole, $driver);
            }

            return $users;

        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getUserId()
    {

    }
}

