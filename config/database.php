<?php
$DB_HOST = 'localhost';
define('DB_USER', 'root');
define('DB_PASSWORD', '');
$DB_NAME = 'expenses';

$dsn = "mysql:host=${DB_HOST};dbname=${DB_NAME}";
$conn = new PDO($dsn, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
