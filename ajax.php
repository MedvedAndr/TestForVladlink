<?php
//	Подключение к БД
include 'connection.php';

//	Функция рекурсивно формирует список всех пунктов меню для добавления в файл
function get_data($parent_id = "NULL", $parent_alias = '', $tab = 0)
{
	$connection = connection();
	$query = "SELECT * FROM `menu`";
	if($parent_id == "NULL")
	{
		$query .= " WHERE `parent_id` IS NULL";
	}
	else
	{
		$query .= " WHERE `parent_id` = ". $parent_id;
	}
	$result_query = $connection->query($query);
	
	if($result_query->num_rows > 0)
	{
		$line = "";
		while($res = $result_query->fetch_assoc())
		{
			$alias = $parent_alias ."/". $res['alias'];
			$line .= str_repeat("\t", $tab) . $res['name'] ." ". $alias  ."\n";
			$line .= get_data($res['id'], $alias, $tab + 1);
		}
	}
	else
	{
		$line = "";
	}
	
	mysqli_close($connection);
	
	return $line;
}

//	Перехватчик POST-запросов от jQuery.ajax()
if(isset($_POST['ajax']))
{
	if($_POST['ajax'] == 'export_to_file')
	{//	Создание файлов со списками меню
		if($_POST['file_name'] == 'type_a')
		{//	Файл с полным списком
			$file = '/home/forVladlink/'. $_POST['file_name'] .'.txt';
			file_put_contents($file, get_data());
			
			echo '/'. $_POST['file_name'] .'.txt';
		}
		elseif($_POST['file_name'] == 'type_b')
		{//	Файл с первым уровнем вложености
			$connection = connection();
			$query = "SELECT * FROM `menu` WHERE `parent_id` IS NULL";
			$result_query = $connection->query($query);
			
			$text = "";
			
			while($res = $result_query->fetch_assoc())
			{
				$text .= $res['name'] ."\n";
				$query = "SELECT * FROM `menu` WHERE `parent_id` = ". $res['id'];
				$sub_result_query = $connection->query($query);
				
				while($sub_res = $sub_result_query->fetch_assoc())
				{
					$text .= "\t". $sub_res['name'] ."\n";
				}
			}
			
			mysqli_close($connection);
			
			file_put_contents('/home/forVladlink/'. $_POST['file_name'] .'.txt', $text);
			
			echo '/'. $_POST['file_name'] .'.txt';
		}
	}
}
?>
