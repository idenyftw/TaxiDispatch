<?php

require_once __DIR__ . '/../classes/zone.php';
require_once __DIR__ . '/../classes/vehicle.php';
require_once __DIR__ . '/../classes/driver.php';
require_once __DIR__ . '/../classes/trip.php';
require_once __DIR__ . '/../classes/user.php';

require_once __DIR__ . '/../classes/notification.php';
require_once __DIR__ . '/../classes/log.php';

function fetchAllUsers()
{
    try
    {
        $users = User::getAllUsers();
        http_response_code(200);

        $notification = new Notification("Fetched all users");

        echo json_encode(['status' => 'success','message' => $notification, "users" => $users]);
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function logIn($data)
{
    $inputUsername = trim($data['username'] ?? '');
    $inputPassword = trim($data['password'] ?? '');

    // transform user username input to lowercase (e.g. UserName -> username)
    $inputUsername = strtolower($inputUsername);

    try
    {
        if($inputUsername && $inputPassword)
        {
            $userId = User::getUserId($inputUsername);

            if($userId != -1)
            {
                if(User::verifyUserPassword($userId, $inputPassword))
                {
                    $token = User::updateUserToken($userId);

                    $log = new Log(date("Y-m-d H:i:s"), $inputUsername . " logged in succesfully");
                    $log->write();

                    $notification = new Notification("Logged in succesfully");
                    
                    http_response_code(200);
                    echo json_encode([
                    'status' => 'success',
                    'message' => $notification,
                    'token' => $token,
                    'log' => $log
                    ]);
                }
                else
                {

                    http_response_code(401);
                    $log = new Log(date("Y-m-d H:i:s"), "Failed log in attempt for user: " .  $inputUsername );
                    $log->write();

                    $notification = new Notification("Invalid password!");

                    echo json_encode(['status' => 'error', 'message' => $notification, 'log' => $log]);
                }
            }
            else
            {
                http_response_code(404);

                $notification = new Notification("User not found");

                echo json_encode(['status' => 'error', 'message' => $notification]);
            }
        }
        else
        {
            http_response_code(401);

            $notification = new Notification("Invalid login credentials");

            echo json_encode(['status' => 'error', 'message' => $notification]);
        }
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchUserRole($data)
{
    $token = trim($data['token'] ?? '');

    try
    {
        if($token)
        {
            $role = User::getUserRole($token);

            if($role != "")
            {
                $log = new Log(date("Y-m-d H:i:s"), $token . " requested role");
                $log->write();

                $notification = new Notification($token ." fetched role succesfully");
                
                http_response_code(200);
                echo json_encode([
                'status' => 'success',
                'message' => $notification,
                'role' => $role,
                'log' => $log
                ]);
            }
            else
            {
                http_response_code(401);

                $notification = new Notification("User not found");

                echo json_encode(['status' => 'error', 'message' => $notification]);
            }
        }
        else
        {
            http_response_code(401);

            $notification = new Notification("Invalid token");

            echo json_encode(['status' => 'error', 'message' => $notification]);
        }
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchAllZones()
{
    try
    {
        $zones = Zone::getAllZones();
        http_response_code(200);

        $notification = new Notification("Fetched all zones");

        echo json_encode(['status' => 'success','message' => $notification, "zones" => $zones]);

    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchAllVehicles()
{
    try
    {
        $vehicles = Vehicle::getAllVehicles();
        http_response_code(200);

        $notification = new Notification("Fetched all vehicles");
        echo json_encode(['status' => 'success','message' => $notification, "vehicles" => $vehicles]);

    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchAllDrivers()
{
    try
    {
        $drivers = Driver::getAllDrivers();
        http_response_code(200);

        $notification = new Notification("Fetched all drivers");
        echo json_encode(['status' => 'success','message' => $notification, "drivers" => $drivers]);

    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchAllTrips()
{
    try
    {
        $trips = Trip::getAllTrips();

        http_response_code(200);

        $notification = new Notification("Fetched all trips");

        echo json_encode(['status' => 'success','message' => $notification, "trips" => $trips]);
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function fetchAllOrders()
{
    try
    {
        $orders = Trip::getAllOrders();

        http_response_code(200);

        $notification = new Notification("Fetched all orders");

        echo json_encode(['status' => 'success','message' => $notification, "orders" => $orders]);
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
//Fonction afin de pouvoir accepter les trips
function acceptTheTrip($data){
    $inputTrip = trim($data['trip'] ?? '');
    $token = trim($data['token'] ?? '');
    try
    {
        if($token)
        {
            $id = User::getDriverIdByToken($token);
            if($id != "")
            {                  
                Trip::acceptTrip($id, $inputTrip);
                echo json_encode(['status' => 'success','message' => 'Accepted']);
            }
            else
            {
                http_response_code(401);
                $notification = new Notification("User not found");
                echo json_encode(['status' => 'error', 'message' => $notification]);
            }
        }
        else
        {
            http_response_code(401);
            $notification = new Notification("Invalid token");
            echo json_encode(['status' => 'error', 'message' => $notification]);
        }
    }
//------------
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}