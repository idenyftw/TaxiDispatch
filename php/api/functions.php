<?php


require __DIR__ . '/../repo/users-repo.php';
require __DIR__ . '/../repo/zones-repo.php';
require __DIR__ . '/../repo/vehicles-repo.php';
require __DIR__ . '/../repo/drivers-repo.php';
require __DIR__ . '/../repo/trips-repo.php';

require __DIR__ . '/../classes/notification.php';
require __DIR__ . '/../classes/log.php';


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
            $userId = getUserId($inputUsername);

            if($userId != -1)
            {
                $passwordHash = getUserPasswordHash($userId);

                if(password_verify($inputPassword, $passwordHash))
                {
                    $token = updateUserToken($userId);

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

            $notification = new Notfication("Invalid login credentials");

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
            $role = getUserRole($token);

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
                http_response_code(404);

                $notification = new Notification("User not found");

                echo json_encode(['status' => 'error', 'message' => $notification]);
            }
        }
        else
        {
            http_response_code(404);

            $notification = new Notfication("Invalid token");

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
        $zones = getAllZones();
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
        $vehicles = getAllVehicles();
        http_response_code(200);

        $notification = new Notification("Fetched all vehicles");
        echo json_encode(['status' => 'success','message' => $notification, "fleet" => $vehicles]);

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
        $drivers = getAllDrivers();
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
        $trips = getAllTrips();

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