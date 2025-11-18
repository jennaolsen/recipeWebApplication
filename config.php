<?php

session_start();
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = "localhost";
$user = "root";
$pass = "";
$db = "recipe_app";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Failed to connect to DB:".$conn->connect_error);
}

?>