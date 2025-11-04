<?php
$host = "localhost";
$username = "gacuti.kethia"; 
$password = "Gacuti@2003";  
$database = "webtech_2025A_gacuti_kethia";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected successfully!";
?>
