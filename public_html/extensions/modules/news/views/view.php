<div class="content_desc" style="padding-top: 20px;">
  <h1>
    {title}
  </h1>
  <div class="mod_news_content">
    <div class="date">
	{created}
    </div>
    {img}
    {body}
    {button}
  </div>
</div>

<script>
function checkform()
{
  if (document.myform.name.value == '')
  {
    alert("Вы не ввели Ваше имя!");
    document.myform.name.focus();
    return false;
  }
  if (document.myform.text.value == '')
  {
    alert("Вы не ввели Ваш комментарий!");
    document.myform.text.focus();
    return false;
  }
  return true;
}
</script>
<?php
$nid		= $this->uri->segment (4);
$news_query	= mysql_query ("SELECT `id`, `commented` FROM `th_news` WHERE `file_id` = '" . (int) $nid . "'");
$news_data	= mysql_fetch_array ($news_query);

if ($news_data['commented'] != '1') {
	echo '';
} else {
	if (isset ($_POST['ok'])) {
		$name  = strip_tags (mysql_real_escape_string ($_POST['name']));
		$email = strip_tags (mysql_real_escape_string ($_POST['email']));
		$text  = strip_tags (mysql_real_escape_string ($_POST['text']));
		$time  = time ();
		$ip    = $_SERVER['REMOTE_ADDR'];

		mysql_query ("INSERT INTO `th_comments` (`nid`, `name`, `email`, `text`, `time`, `ip`) VALUES ('" . (int) $news_data['id'] . "', '$name', '$email', '$text', '$time', '$ip')");
		echo '<p><font color="green">Ваш комментарий успешно добавлен и будет доступен на сайте, после проверки модератором!</font></p>';
	}
	echo '<h3>Оставить комментарий:</h3>
	<form action="" method="POST" name="myform" id="myform" onsubmit="return checkform();">
		<label>Ваше имя <font color="red">*</font>:</label> <input type="text" name="name" value="" /><br />
		<label>E-mail:</label> <input type="text" name="email" value="" /><br />
		<label>Ваш комментарий <font color="red">*</font>:</label> <textarea name="text" cols="35" rows="5"></textarea><br /><br />
		<input type="submit" name="ok" value="   Добавить комментарий   " id="ok" /><br /><br />
		Поля, помеченные <sup><font color="red">*</font></sup> являются обязательными для заполнения!
	</form>
	';
	$comments_query = mysql_query ("SELECT * FROM `th_comments` WHERE `nid` = '" . (int) $news_data['id'] . "' and `active` = '1'");
	echo '<h3>Комментарии:</h3>';
	while ($row = mysql_fetch_array ($comments_query)) {
		?>
		    <div class="myform_ans">
			<i>Оставил <?=$row['name'];?>, <?=date("d.m.Y", $row['time']);?></i><br />
			<?=$row['text'];?>
		    </div>
		<?
	}
}
?>
