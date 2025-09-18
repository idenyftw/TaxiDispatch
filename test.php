<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxi Dispatch</title>
</head>
<body>
    <h1>hello</h1>
</body>
</html>

<?php

require_once "php/classes/log.php";
require_once "php/repositories/zones-repo.php";
require_once "php/repositories/vehicles-repo.php";

$zones = getAllZones();
$vehicles = getAllVehicles();

/*
foreach($zones as $zone)
{
    echo $zone->name. "<br>";
}
*/

foreach($vehicles as $vehicle)
{
    echo $vehicle->licensePlate . " ". $vehicle->type->nameFr . " ". $vehicle->type->nameEn ."<br>";
}

$log = new Log("1990-10-13", "Premier log");
echo $log->toString();
echo "<br>";
$hash = password_hash("Super256", PASSWORD_DEFAULT);

echo $hash;

echo "<br>";
echo password_verify("Super256",$hash);

?>