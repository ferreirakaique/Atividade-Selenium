<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_selenium";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: $conn->connect_error");
}
