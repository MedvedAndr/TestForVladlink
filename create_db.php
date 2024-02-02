<?php
include 'connection.php';

$connection = new mysqli($db['host'], $db['login'], $db['pass']);

//	Создание БД 'menu_db'
$query = "CREATE DATABASE `". $db['db_name'] ."`";
$connection->query($query);

$connection->select_db($db['db_name']);

//	Создание таблицы 'menu'
$query	 = "CREATE TABLE IF NOT EXISTS `menu` (`ai_id` int(11) NOT NULL AUTO_INCREMENT, `id` int(11) DEFAULT NULL, `parent_id` int(11) DEFAULT NULL, `name` varchar(255) DEFAULT NULL, `alias` varchar(255) DEFAULT NULL,  PRIMARY KEY (`ai_id`)) ENGINE = INNODB, CHARACTER SET utf8, COLLATE utf8_general_ci";
$connection->query($query);

mysqli_close($connection);

echo 'DB create successfully';
?>