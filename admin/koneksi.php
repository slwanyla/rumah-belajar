<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahbelajar";

// $host = "localhost";
// $user = "rohanah";
// $pass = "Buana0000!";
// $db = "rumahbelajar";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
	echo 'ERROR : '  . mysqli_connect_error($conn);
}
