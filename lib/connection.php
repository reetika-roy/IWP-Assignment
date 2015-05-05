<?php
$mysqli = new mysqli("hostname", "username", "password", "databasename");
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
} 
?>