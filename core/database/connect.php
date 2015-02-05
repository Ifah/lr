<?php
$conn_error = "Sorry, we're experiencing connection problems.";

$mysql_host = '127.0.0.1';
$mysql_user = 'ifah';
$mysql_pass = 'password';

$mysql_connect = @mysqli_connect($mysql_host, $mysql_user, $mysql_pass);
$mysql_db = 'register_login';

if(!@mysqli_connect($mysql_host, $mysql_user, $mysql_pass) || !@mysqli_select_db($mysql_connect, $mysql_db))
{
	die($conn_error);
}
?>
