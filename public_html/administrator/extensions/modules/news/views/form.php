<div class="cms_title"><?=_module_icon('news')?>Создание / редактирование новости</div>

<div class="cms_content">
{form_open}
			{file_id}

            <label for="title">Заголовок: </label>{title}

			<br /><br />

            <label for="created">Дата публикации: </label>{created}

            <br /><br />

            <div style="display:none;"><label for="commented">Коментируемая: </label> {commented}

            <br /><br />
			</div>
            <label>Превью:</label><br /><br />{img}<br /><br />
            <input type="file" name="preview" value=""></input>

            <br /><br />

            <label for="desc">Краткое описание: </label><br /><br />
            {desc}

            <br /><br />

            {body}

            {submit}

{form_close}
</div>

<?php /*
<div class="cms_title">Комментарии к новости:</div>

<div class="cms_content">
	<?php

		$nid = $this->uri->segment (5);
		$news_query	= mysql_query ("SELECT `id`, `commented` FROM `th_news` WHERE `file_id` = '" . (int) $nid . "'");
		$news_data	= mysql_fetch_array ($news_query);

		if ($news_data['commented'] != '1') {
			echo '';
		} else {

			$comments_query = mysql_query ("SELECT * FROM `th_comments` WHERE `nid` = '" . (int) $news_data['id'] . "'");

			echo '<table width="100%" border="0" cellspacing="1" cellpadding="0">';

			while ($row = mysql_fetch_array ($comments_query)) {
	?>

			<tr<?php if ($row['active'] != '1') echo ' style="background: #FFE6F3;"';?>>
				<td><b>Оставил:</b> <?=$row['name'];?><br />
					<b>E-mail:</b> <?=$row['email'];?>, IP: <?=$row['ip'];?>), <?=date("d.m.Y", $row['time']);?><br /><br />
					<?=$row['text'];?>
				</td>
				<td style="width: 100px;">
					<a href="/admin/comments/comments/delete/<?=$row['id'];?>/<?=$this->uri->segment (5);?>" onclick="return confirm('Действительно удалить?');">удалить</a> | 
					<a href="/admin/comments/comments/active/<?=$row['id'];?>/<?php if ($row['active'] != '1') echo '1'; else echo '0';?>/<?=$this->uri->segment (5);?>"><?php if ($row['active'] != '1') echo 'одобрить'; else echo 'заблокировать';?></a>
				</td>
			</tr>
			
	<?
		}
			echo '</table>';
		}
	?>
</div>
*/?>
