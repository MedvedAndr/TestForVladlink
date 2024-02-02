<?php
//	Подключение к БД
include 'connection.php';

//	Функция получения данных меню из БД
function get_menu_data()
{
	$connection = connection();
	
	$query = "SELECT * FROM `menu`";
	$result_query = $connection->query($query);
	
	mysqli_close($connection);
	
	$data = array();
	
	if($result_query->num_rows > 0)
	{
		while($res = $result_query->fetch_assoc())
		{
			array_push($data, $res);
		}
	}
	
	return $data;
}

//	Функция рекурсивно формирует список всех пунктов меню для добавления в файл
function get_data($data, $parent_id = null, $parent_alias = '', $tab = 0)
{
	$line = "";
	
	foreach($data as $category)
	{
		if($category['parent_id'] == $parent_id)
		{
			$alias = $parent_alias ."/". $category['alias'];
			$line .= str_repeat("\t", $tab) . $category['name'] ." ". $alias  ."\n";
			$line .= get_data($data, $category['id'], $alias, $tab + 1);
		}
	}
	
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
			file_put_contents($file, get_data(get_menu_data()));
			
			echo '/'. $_POST['file_name'] .'.txt';
		}
		elseif($_POST['file_name'] == 'type_b')
		{//	Файл с первым уровнем вложености
			$menu_data = get_menu_data();
			
			$text = "";
			
			foreach($menu_data as $category)
			{
				if($category['parent_id'] == null)
				{
					$text .= $category['name'] ."\n";
					foreach($menu_data as $sub_category)
					{
						if($sub_category['parent_id'] == $category['id'])
						{
							$text .= "\t". $sub_category['name'] ."\n";
						}
					}
				}
			}
			
			file_put_contents('/home/forVladlink/'. $_POST['file_name'] .'.txt', $text);
			
			echo '/'. $_POST['file_name'] .'.txt';
		}
	}
}
?>
