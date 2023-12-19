<?php

$host = 'localhost';
$db = 'php_crud';
$charset = 'utf8mb4';
// $dsn = "mysql:host $host; dbname=$db ; charset=$charset";
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$username = 'root';
$password = 'fima@2019';

$pdo = new PDO($dsn, $username , $password);



