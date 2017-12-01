<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods', 'OPTIONS,GET,PUT,POST,DELETE');
header('Access-Control-Allow-Headers', 'Content-Type');
header('Content-Type: text/plain');

$server = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "db_visualizer";
$con = mysqli_connect($server, $username, $password);
mysqli_select_db($con, $dbname);
$result = mysqli_query($con, "SELECT * FROM province");

require_once("./compressor.php");
Compressor::compressMySqli($result);

mysqli_free_result($result);
