<?php

$host = 'localhost'; // Change if necessary
$dbname = 'delivery_services'; // Replace with your actual database name
$username = 'root'; // Change if necessary
$password = ''; // Change if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database. Error: " . $e->getMessage());
}
