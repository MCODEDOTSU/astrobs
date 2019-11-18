	<ul class="jd_menu">

		<li><?=anchor('admin/place/place', 'Редактор структуры')?></li>
		<li><?=anchor('admin/mod_content/content', 'Редактор контента')?></li>


		<li><a href="#" class="accessible">Шаблон &raquo;</a>
			<ul>
				<li><?=anchor('admin/tpl_block/tpl_block_main', 'Главная страница')?></li>
				<li><?=anchor('admin/tpl_block/tpl_block_left', 'Левый блок')?></li>
				<li><?=anchor('admin/tpl_block/tpl_block_right', 'Правый блок')?></li>
				<li><?=anchor('admin/tpl_block/tpl_block_bottom', 'Подвал')?></li>
				<li><?=anchor('admin/qas/qas', 'Вопрос директору')?></li>
			</ul>
		</li>

		<li><a href="#" class="accessible">Системные &raquo;</a>
			<ul>
				<li><?=anchor('admin/users/users', 'Пользователи')?></li>
				<li><?=anchor('admin/user_groups/user_groups', 'Группы пользователей')?></li>
				<li><?=anchor('admin/access/access', 'Управление доступом')?></li>
			</ul>
		</li>
		
		<li style="float:right;"><?=anchor('admin/logout', 'Выход')?></li>
		      
		<li style="float:right;"><?=anchor('', 'Сайт')?></li>
	</ul>
