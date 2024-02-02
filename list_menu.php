<?php
include 'ajax.php';

//	Функция рекурсивно формирует список всех пунктов меню для вывода на странице
function get_menu_list($data, $parent_id = null)
{
	$menu = '<ul>';
	
	foreach($data as $category)
	{
		if($category['parent_id'] == $parent_id)
		{
			$menu .= '<li>';
			$menu .= $category['name'];
			$menu .= get_menu_list($data, $category['id']);
			$menu .= '</li>';
		}
	}
	
	$menu .= '</ul>';
	
	return $menu;
}

//	Функция формирования меню
function get_menu()
{
	return get_menu_list(get_menu_data());
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
			<?php echo get_menu(); ?>
		</div>
	</body>
</html>
