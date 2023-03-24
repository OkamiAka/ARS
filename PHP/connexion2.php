<link rel="stylesheet" href="../CSS/Style_connect.css"/>
<?php
$servername = "localhost";
$username = "root";
$password = "root";
$base = "ars";

//create connection
$conn = new mysqli($servername, $username, $password, $base);

/*
//check connection
if ($conn ->connect_error)
{
	die("Connection failed: ".$conn ->connect_error);
}
else
{
	echo("Connecte ࠬa base de donnees");
}
*/
?>