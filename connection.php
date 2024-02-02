<?php
function connection()
{
	$connection = new mysqli($db['host'], $db['login'], $db['pass']);
	$connection->select_db($db['db_name']);
	$connection->query('SET NAMES utf8');
	
	return $connection;
}

$db = array(
	'host'		=> 'localhost',
	'login'		=> 'root',
	'pass'		=> 'pass',
	'db_name'	=> 'menu_db'
);
?>
