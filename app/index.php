<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);


$app->get('/', function ($request, $response) {
    $response->getBody()->write('
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Taxi Dispatch</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/login.css">
        </head>
        <body>
            <div class="d-flex justify-content-center align-items-center min-vh-100">
                <div class="container frost" id="loginContainer">
                <form method="post" id="loginForm" class="needs-validation" novalidate>
                    <h3>Log In</h3>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input name="username" type="text" class="form-control" id="inputUsername" placeholder="" minlength="4" required>
                        <div class="invalid-feedback">
                            Invalid Username
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="" minlength="8" required>
                        <div class="invalid-feedback">
                            Invalid Password
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Log In</button>
                </form>
                </div>
            </div>
        </body>
        <script src="js/login.js"></script>
        </html>
    ');
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/hello', function ($request, $response) {
    $response->getBody()->write('
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Hello</title>
        </head>
        <body>
            <h1>Hello Route</h1>
            <p>Cette route répond à GET /hello</p>
            <a href="/">Retour à l\'accueil</a>
        </body>
        </html>
    ');
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/hello/{name}', function ($request, $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <title>Hello $name</title>
        </head>
        <body>
            <h1>Hello $name !</h1>
            <p>Cette route utilise un paramètre dynamique.</p>
            <a href='/'>Retour à l'accueil</a>
        </body>
        </html>
    ");
    return $response->withHeader('Content-Type', 'text/html');
});


// API JSON
$app->get('/api/info', function ($request, $response) {
    $data = [
        'message' => 'QuickAWEB API',
        'version' => '1.0.0',
        'framework' => 'Slim Framework 4',
        'routes' => [
            'GET /' => 'Page d\'accueil',
            'GET /hello' => 'Route Hello',
            'GET /api/info' => 'Informations API'
        ]
    ];
    
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json');
});


// Lancer l'application
$app->run();
