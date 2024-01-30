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
	if($_POST['ajax'] == 'upload_json')
	{//	Обработка файла categories.json и добавление данных в БД
		//	Рекурсивное формирование данных для добавления в БД
		//function get_values($categories, $ids_in_db = array(), $parent_id = "NULL")
		//{
		//	$vals = array();
		//	
		//	//	Перебор категорий текущего уровня сложности
		//	foreach($categories as $category)
		//	{
		//		//	Проверка id с данными из ранее сформированного массива и если совпадения нет, то добавляем запись, иначе пропускаем
		//		if(!in_array($category['id'], $ids_in_db))
		//		{
		//			$val = "(". $category['id'] .", ". $parent_id .", '". $category['name'] ."', '". $category['alias'] ."')";
		//			array_push($vals, $val);
		//		}
		//		
		//		//	Если у текущей категории есть дочерние элементы, то отправляем их на обработку и результат обработки добавляем к текущему результату
		//		if(isset($category['childrens']))
		//		{
		//			$vals = array_merge($vals, get_values($category['childrens'], $ids_in_db, $category['id']));
		//		}
		//		elseif(isset($category['0']))
		//		{//	В файле json у одной категории в структуре нет childrens, но есть 0. Я прировнял 0 к childrens и обработал. Если обработка таких записей не нужна, то нужно закоментировать этот elseif
		//			$vals = array_merge($vals, get_values($category['0'], $ids_in_db, $category['id']));
		//		}
		//	}
		//	
		//	return $vals;
		//}
		//
		//$connection = connection();
		//
		////	Создание таблицы, если она отсутствует
		//$query	 = "CREATE TABLE IF NOT EXISTS `menu` (";
		//$query	.=      "`ai_id` int(11) NOT NULL AUTO_INCREMENT,";
		//$query	.=     " `id` int(11) DEFAULT NULL,";
		//$query	.=     " `parent_id` int(11) DEFAULT NULL,";
		//$query	.=     " `name` varchar(255) DEFAULT NULL,";
		//$query	.=     " `alias` varchar(255) DEFAULT NULL,";
		//$query	.=     "  PRIMARY KEY (ai_id)";
		//$query	.= ")";
		//$query	.= " ENGINE = INNODB,";
		//$query	.= " CHARACTER SET utf8,";
		//$query	.= " COLLATE utf8_general_ci";
		//$connection->query($query);
		//
		////	Формируем перечень имеющихся id категорий в БД
		//$query	 = "SELECT `m`.`id` FROM `menu` AS `m`";
		//$result_query = $connection->query($query);
		//
		//$curr_cat_ids = array();
		//if($result_query->num_rows > 0)
		//{
		//	while($res = $result_query->fetch_assoc())
		//	{
		//		array_push($curr_cat_ids, $res['id']);
		//	}
		//}
		//
		////	Получаем данные из файла и посылаем их на обработку
		//$json_data = file_get_contents('categories.json');
		//$json_data = json_decode($json_data, true);
		//
		////	Формируем запрос на добавление данных в БД, игнорируя те записи, которые уже есть
		//$values = get_values($json_data, $curr_cat_ids);
		//
		//if(count($values) > 0)
		//{
		//	$query	 = "INSERT INTO `menu` (";
		//	$query	.=      "`id`,";
		//	$query	.=     " `parent_id`,";
		//	$query	.=     " `name`,";
		//	$query	.=     " `alias`";
		//	$query	.= ") VALUES ". implode(', ', $values);
		//	$connection->query($query);
		//}
		//
		//mysqli_close($connection);
		//
		//echo 'Data added successfully';
	}
	elseif($_POST['ajax'] == 'export_to_file')
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