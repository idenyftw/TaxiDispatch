<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <h1>Dashboard</h1>

    <?php

    require_once __DIR__ . '/../php/repo/users-repo.php';

    $token = htmlspecialchars($_COOKIE["token"]);
    $user = getUser($token);

    echo 'Token ' . $token . "<br>";
    echo $user->firstName . "<br>";
    echo $user->role->name . "<br>";

    ?>

</body>
<script src="../js/dashboard.js"></script>
</html>