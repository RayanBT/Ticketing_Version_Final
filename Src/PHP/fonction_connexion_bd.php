<?php
function connectToDatabase() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "BD_Ticketing";
    $connection = mysqli_connect($host, $user, $password, $database);
    if (!$connection) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }
    return $connection;
}
?>