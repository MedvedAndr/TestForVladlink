<?php
function connection()
{
	$host = 'localhost';
	$login = 'root';
	$password = 'pass';
	$db_name = 'db_name';
	
	$connection = new mysqli($host, $login, $password);
	$connection->select_db($db_name);
	$connection->query('SET NAMES utf8');
	
	return $connection;
}
?>
