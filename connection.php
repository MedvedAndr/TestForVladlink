<?php
function connection()
{
	$connection = new mysqli('localhost', 'root', '!Xthtpyjue666z');
	$connection->select_db('test_db');
	$connection->query('SET NAMES utf8');
	
	return $connection;
}
?>