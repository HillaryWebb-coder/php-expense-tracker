<?php
require "database.php";

$sql1 = "CREATE TABLE IF NOT EXISTS ${DB_NAME}.users (id INT(15) NOT NULL AUTO_INCREMENT , username VARCHAR(255) NOT NULL , email VARCHAR(255) NOT NULL , password VARCHAR(255) NOT NULL , PRIMARY KEY (id), UNIQUE username (username), UNIQUE email (email))";
$sql2 = "CREATE TABLE IF NOT EXISTS ${DB_NAME}.transactions (id INT(15) NOT NULL AUTO_INCREMENT , amount INT(255) NOT NULL , description VARCHAR(255) NOT NULL , type VARCHAR(255) NOT NULL , userid INT(15), PRIMARY KEY (id))";
try {
    $stmt = $conn->prepare($sql1);
    $stmt->execute();
    $stmt = $conn->prepare($sql2);
    $stmt->execute();
} catch (Exception $e) {
    echo $e->getMessage();
}
