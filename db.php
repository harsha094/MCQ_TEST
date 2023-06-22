<?php
$servername = "harsh.localhost";
$username = "harsh";
$password = "123456";

try {
  $conn = new PDO("mysql:host=$servername;dbname=quiz_system", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}


?>