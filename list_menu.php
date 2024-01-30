<?php
include 'connection.php';

//	Функция рекурсивно формирует список всех пунктов меню для вывода на странице
function get_data($parent_id = "NULL")
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
		$menu = '<ul>';
		
		while($res = $result_query->fetch_assoc())
		{
			$menu .= '<li>';
			$menu .= $res['name'];
			$menu .= get_data($res['id']);
			$menu .= '</li>';
		}
		
		$menu .= '</ul>';
	}
	else
	{
		$menu = '';
	}
	
	mysqli_close($connection);
	
	return $menu;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head.php'; ?>
	</head>
	<body>
		<div class="links">
			<a href="/">На главную</a>
		</div>
		
		<div>
			<?php echo get_data(); ?>
		</div>
	</body>
</html>