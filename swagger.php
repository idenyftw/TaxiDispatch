<?php
require 'vendor/autoload.php';

use OpenApi\Annotations as OA;

// Spécifie le répertoire à scanner pour trouver les annotations Swagger
$openapi = \OpenApi\Generator::scan(['/var/www/html']);  // Le chemin d'accès de tes fichiers PHP

// Génère le fichier swagger.json
file_put_contents('/var/www/html/swagger/swagger.json', $openapi->toJson());
echo "Swagger documentation generated at /swagger/swagger.json";
