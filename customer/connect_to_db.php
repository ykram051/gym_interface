<?php
 
$servername="localhost";
$dbpassword="Ikram2004@";
$username = "root";
$dbname = "gym";

// Create a PDO connection
$con = mysqli_connect($servername,$username,$dbpassword,$dbname);
if(!$con) {
    die("Connection failed: " . mysqli_connect_error());
}