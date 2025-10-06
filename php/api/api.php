<?php

require 'functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);
$endpoint = trim($data['endpoint'] ?? '');

if ($method === 'POST') 
{
    switch($endpoint)
    {
        case "user/log_in":
            logIn($data);
            break;
        case "user/get_role":
            fetchUserRole($data);
            break;
        case "zone/get_all":
            fetchAllZones();
            break;
        case "fleet/get_all":
            fetchAllVehicles();
            break;
        case "driver/get_all":
            fetchAllDrivers();
            break;
        case "trip/get_all":
            fetchAllTrips();
            break;
        default:
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => "The specified endpoint doesn't exist"]);
            break;
    }    
}
else 
{
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
