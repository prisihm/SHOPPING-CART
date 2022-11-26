<?php
$dbhost= "localhost";
$dbuser= "root";
$dbpass= "";
$dbname= "";

if (! $con= mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)) {
    # code...
    echo("Connection failed");
    die;
}
?>