<?php
define("MYSQL_HOST", "147.134.0.59"); // replace host with 147.134.0.59
define("MYSQL_USER", "jnd98092"); // replace username with net ID in lowercase
define("MYSQL_PASSWORD", "********************"); // replace password with the user’s password
define("MYSQL_DB", "354groupb1"); // replace db with the schema
$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false
];
$conn = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" .
MYSQL_DB , MYSQL_USER, MYSQL_PASSWORD, $options);
?>
