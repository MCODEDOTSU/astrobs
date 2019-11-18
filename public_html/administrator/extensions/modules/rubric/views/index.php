<?php

if (isset ($_POST['ok'])) {
	$count  = count ($_POST['checkbox']);
	$counts = count ($_POST['checkboxs']);
	$countss = count ($_POST['is_tour']);
	//print_r ($_POST);
	mysql_query ("UPDATE `th_category` SET `main` = '0', `left` = '0', `top` = '0', `tour` = '0'");

	for ($i = 0; $i < $count; $i++) {
		mysql_query ("UPDATE `th_category` SET `main` = '1' WHERE `id` = '" . $_POST['checkbox'][$i] . "'");
	}

	for ($i = 0; $i < $counts; $i++) {
		mysql_query ("UPDATE `th_category` SET `left` = '1' WHERE `id` = '" . $_POST['checkboxs'][$i] . "'");
	}
	
	//mysql_query ("UPDATE `th_category` SET `tour` = '0'");
	
	foreach ($_POST['is_tour'] AS $k => $v) {
		mysql_query ("UPDATE `th_category` SET `tour` = '1' WHERE `id` = '" . (int) $k . "'");
	}

	mysql_query ("UPDATE `th_category` SET `top` = '1' WHERE `id` = '" . $_POST['radio'] . "'");
}

echo '<div class="cms_title">Рубрики для главной страницы</div>';

echo '<div class="cms_content">';

	$main_cats_query = mysql_query ("SELECT * FROM `th_category` ORDER BY `id` DESC");
	
	echo '<form action="" method="POST">';
		echo '<table width="100%" borde="0" cellspacing="0" cellpadding="0">';
			echo '<tr><th>Название:</th><th>Вывод на главной:</th><th>Вывод слева:</th><th>Вывод сверху:</th><th>Редактировать описание:</th><th>Туры:</th></tr>';
			while ($cats = mysql_fetch_array ($main_cats_query)) {
				$check_tour = ($cats['tour'] == 1) ? true : false;
?>

<tr>
	<th><?=$cats['title'];?></th>
	<td><center><input type="checkbox" name="checkbox[]" value="<?=$cats['id'];?>"<?php if ($cats['main'] == '1') echo ' checked';?>></center></td>
	<td><center><input type="checkbox" name="checkboxs[]" value="<?=$cats['id'];?>"<?php if ($cats['left'] == '1') echo ' checked';?>></center></td>
	<td><center><input type="radio" name="radio" value="<?=$cats['id'];?>"<?php if ($cats['top'] == '1') echo ' checked';?>></center></td>
	<td><a href="<?=site_url(array ('admin', 'rubric', 'rubric', 'descrub', $cats['id']))?>">Изменить</a></td>
	<td><?=form_checkbox ('is_tour[' .$cats['id'] . ']', 1, $check_tour)?></td>
</tr>

<?php
	}
		echo '</table>';
	echo '<input type="submit" name="ok" value="Сохранить">';
	echo "</form>";
	
echo '</div>';

?>
