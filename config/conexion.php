<?php
$host = '10.0.40.85';
$dbname = 'tienda_flores';
$username = 'u_flores_andres'; 
$password = 'Mar#26C9tR5';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("" . $e->getMessage());
}
?>