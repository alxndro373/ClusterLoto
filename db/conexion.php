<?php

$host = "localhost";
$usuario = "root";
$contrasena = "";
$db = "cluster_project";

$conn = new mysqli($host, $usuario, $contrasena, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>