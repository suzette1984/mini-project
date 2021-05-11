<?php
require_once 'dbConfig.phpp';

// Check connection to DB

try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
    echo "Connected to $database at $servername successfully.";
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
