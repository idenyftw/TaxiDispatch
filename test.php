<?php 
$hash = password_hash("Super256", PASSWORD_DEFAULT);

echo $hash;

echo "<br>";
echo password_verify("Super256",$hash);

?>