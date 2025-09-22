<?php

require __DIR__ . '/../repo/users-repo.php';
require __DIR__ . '/../classes/log.php';

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
                    
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'token' => $token, 'log' => $log->toString()]);
                }
                else
                {
                    http_response_code(401);
                    $log = new Log(date("Y-m-d H:i:s"), " failed log in attempt for user: " .  $inputUsername );
                    echo json_encode(['status' => 'error', 'message' => 'Invalid Password!', 'log' => $log->toString()]);
                }
            }
            else
            {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
            }
        }
        else
        {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid login credentials']);
        }
    }
    catch(Exception $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}