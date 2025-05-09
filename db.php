<?php
$servername = "sql209.infinityfree.com";
$username = "if0_38892804";
$password = "GGYHPvzp6nNOL";
$dbname = "if0_38892804_quiz";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
