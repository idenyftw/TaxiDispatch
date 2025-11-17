<?php

require_once 'driver.php';

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

    public static function getUserByUsername(string $username)
    {
        $pdo = connDB();
        $sql = "SELECT * FROM Users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        $userRole = Role::tryFrom($userInfo['role']) ?? Role::Other;

        $driver   = Driver::getDriverById($userInfo['driver_id']);

        $user     = new User($userInfo['user_id'], $userInfo['username'], $userInfo['first_name'], $userInfo['last_name'], $userRole, $driver);

        return $user;
    }

    public static function getUserId(string $username)
    {
        $pdo = connDB();
        $sql = "SELECT user_id FROM Users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userInfo !== false ? (int)$userInfo["user_id"] : -1;
    }

    private static function getUserPasswordHash(int $userId)
    {
        $pdo = connDB();
        $sql = "SELECT password FROM Users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userInfo["password"];
    }

    public static function verifyUserPassword(int $userId, string $password)
    {
        $passwordHash = self::getUserPasswordHash($userId);

        return password_verify($password, $passwordHash);
    }

    public static function updateUserToken($userId) : string
    {
        $pdo = connDB();

        $token = uniqid('', true);
        $pdo = connDB();
        $stmt = $pdo->prepare('UPDATE Users SET token = ? WHERE user_id = ?');
        $stmt->execute([$token, $userId]);

        return $token;
    }

    public static function getUserRole($token)
    {
        $pdo = connDB();

        $stmt = $pdo->prepare('
            SELECT role FROM Users WHERE token = ? LIMIT 1;
        ');

        $stmt->execute([$token]);
        $dbResult = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dbResult ? $dbResult['role'] : "";
    }
    
    public static function getDriverIdByToken($token)
    {
        $pdo = connDB();

        $stmt = $pdo->prepare('
            SELECT driver_id FROM Users WHERE token = ? LIMIT 1;
        ');

        $stmt->execute([$token]);
        $dbResult = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dbResult ? $dbResult['driver_id'] : "";
    }
}

