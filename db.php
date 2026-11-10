<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "text_solution_db";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {

    die("Database Connection Failed: " . $conn->connect_error);

}

?>