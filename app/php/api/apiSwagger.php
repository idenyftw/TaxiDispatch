<?php
require 'functions.php';

// Définir le type de contenu de la réponse en JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
/**
 * @OA\Info(title="API Taxi Dispatch", version="1.0")
 * @OA\Server(url="http://localhost")
 */

// Récupérer la méthode HTTP de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Récupérer les données envoyées par le client (au format JSON)
$data = json_decode(file_get_contents('php://input'), true);

// Extraire l'endpoint de la requête
$endpoint = trim($data['endpoint'] ?? '');

// Traiter la requête selon la méthode HTTP
if ($method === 'POST') {
    switch ($endpoint) {
        /**
         * @OA\Post(
         *     path="/user/get_all",
         *     summary="Obtenir tous les utilisateurs",
         *     @OA\Response(
         *         response="200",
         *         description="Liste des utilisateurs",
         *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
         *     )
         * )
         */
        case "user/get_all":
            fetchAllUsers($data);
            break;
        
        /**
         * @OA\Post(
         *     path="/user/log_in",
         *     summary="Connexion de l'utilisateur",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"username", "password"},
         *             @OA\Property(property="username", type="string"),
         *             @OA\Property(property="password", type="string")
         *         )
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="Réponse de connexion réussie",
         *         @OA\JsonContent(type="object", @OA\Property(property="token", type="string"))
         *     ),
         *     @OA\Response(response="401", description="Identifiants incorrects")
         * )
         */
        case "user/log_in":
            logIn($data);
            break;
        
        /**
         * @OA\Post(
         *     path="/user/get_role",
         *     summary="Obtenir le rôle d'un utilisateur",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"user_id"},
         *             @OA\Property(property="user_id", type="integer")
         *         )
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="Rôle de l'utilisateur",
         *         @OA\JsonContent(type="object", @OA\Property(property="role", type="string"))
         *     )
         * )
         */
        case "user/get_role":
            fetchUserRole($data);
            break;
        
           /**
         * @OA\Post(
         *     path="/zone/get_all",
         *     summary="Obtenir les zones",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"user_id"},
         *             @OA\Property(property="user_id", type="integer")
         *         )
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="Rôle de l'utilisateur",
         *         @OA\JsonContent(type="object", @OA\Property(property="role", type="string"))
         *     )
         * )
         */
        case "zone/get_all":
            fetchAllZones();
            break;
        case "vehicle/get_all":
            fetchAllVehicles();
            break;
        case "driver/get_all":
            fetchAllDrivers();
            break;
        case "trip/get_all":
            fetchAllTrips();
            break;
        case "trip/get_orders":
            fetchAllOrders();
            break;
        
        default:
            // Si l'endpoint n'est pas trouvé
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => "The specified endpoint doesn't exist"
            ]);
            break;
    }
}
// Gérer les requêtes PUT
else if ($method === 'PUT') {

    /**
     * @OA\Put(
     *     path="/trip/Accept",
     *     summary="Accepter un voyage",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_id", "driver_id"},
     *             @OA\Property(property="trip_id", type="integer"),
     *             @OA\Property(property="driver_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Voyage accepté",
     *         @OA\JsonContent(type="object", @OA\Property(property="status", type="string"))
     *     )
     * )
     */
    switch ($endpoint) {
        case "trip/Accept":
            acceptTheTrip($data);
            break;
        default:
            // Endpoint PUT non défini
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => "The specified endpoint doesn't exist"
            ]);
            break;
    }
}
// Si la méthode HTTP est invalide
else {
    http_response_code(403);
    echo json_encode([
        'error' => 'Invalid request method'
    ]);
}
