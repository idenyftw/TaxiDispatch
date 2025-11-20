<?php

namespace App\php\api;

use Slim\Http\Request;
use Slim\Http\Response;

class ApiController
{
    public function info($request, $response)
    {
        $data = [
            'message' => 'QuickAWEB API',
            'version' => '1.0.0',
            'framework' => 'Slim Framework 4',
            'routes' => [
                'GET /api/info' => 'Informations API',
                'POST /api/user/get_all' => 'Obtenir tous les utilisateurs',
                'POST /api/user/log_in' => 'Connexion utilisateur',
                'POST /api/user/get_role' => 'Obtenir rôle d\'un utilisateur',
                'POST /api/zone/get_all' => 'Obtenir toutes les zones',
                'POST /api/vehicle/get_all' => 'Obtenir tous les véhicules',
                'POST /api/driver/get_all' => 'Obtenir tous les conducteurs',
                'POST /api/trip/get_all' => 'Obtenir tous les trajets',
                'POST /api/trip/get_orders' => 'Obtenir toutes les commandes de trajets',
                'PUT /api/trip/accept' => 'Accepter un trajet'
            ]
        ];
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllUsers( $request,  $response)
    {
        $data = fetchAllUsers(); 
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function logIn( $request,  $response)
    {
        $data = logIn($request->getParsedBody());
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getUserRole( $request,  $response)
    {
        $data = fetchUserRole($request->getParsedBody());
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllZones( $request,  $response)
    {
        $data = fetchAllZones();
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllVehicles( $request,  $response)
    {
        $data = fetchAllVehicles();
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllDrivers( $request,  $response)
    {
        $data = fetchAllDrivers();
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllTrips( $request,  $response)
    {
        $data = fetchAllTrips();
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllOrders( $request,  $response)
    {
        $data = fetchAllOrders();
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function acceptTrip( $request,  $response)
    {
        $data = acceptTheTrip($request->getParsedBody());
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
