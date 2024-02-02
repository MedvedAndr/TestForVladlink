<?php
include 'connection.php';

//	Рекурсивное формирование данных для добавления в БД
function get_values($categories, $ids_in_db = array(), $parent_id = "NULL")
{
	$vals = array();
	
	//	Перебор категорий текущего уровня сложности
	foreach($categories as $category)
	{
		//	Проверка id с данными из ранее сформированного массива и если совпадения нет, то добавляем запись, иначе пропускаем
		if(!in_array($category['id'], $ids_in_db))
		{
			$val = "(". $category['id'] .", ". $parent_id .", '". $category['name'] ."', '". $category['alias'] ."')";
			array_push($vals, $val);
		}
		
		//	Если у текущей категории есть дочерние элементы, то отправляем их на обработку и результат обработки добавляем к текущему результату
		if(isset($category['childrens']))
		{
			$vals = array_merge($vals, get_values($category['childrens'], $ids_in_db, $category['id']));
		}
		elseif(isset($category['0']))
		{//	В файле json у одной категории в структуре нет childrens, но есть 0. Я прировнял 0 к childrens и обработал. Если обработка таких записей не нужна, то нужно закоментировать этот elseif
			$vals = array_merge($vals, get_values($category['0'], $ids_in_db, $category['id']));
		}
	}
	
	return $vals;
}

$connection = connection();

//	Формируем перечень имеющихся id категорий в БД
$query	 = "SELECT `m`.`id` FROM `menu` AS `m`";
$result_query = $connection->query($query);

$curr_cat_ids = array();
if($result_query->num_rows > 0)
{
	while($res = $result_query->fetch_assoc())
	{
		array_push($curr_cat_ids, $res['id']);
	}
}

//	Получаем данные из файла и посылаем их на обработку
$json_data = file_get_contents('categories.json');
$json_data = json_decode($json_data, true);

//	Формируем запрос на добавление данных в БД, игнорируя те записи, которые уже есть
$values = get_values($json_data, $curr_cat_ids);

if(count($values) > 0)
{
	$query	 = "INSERT INTO `menu` (`id`, `parent_id`, `name`, `alias`) VALUES ". implode(', ', $values);
	$connection->query($query);
}

mysqli_close($connection);

echo 'Data added successfully';
?>
