<?php

	$config['search'] = array(
		'title' => 'Поиск',
		'type' => 'header',
		'function' => 'search_block',
		'includes' => array(
			'models' => array('search_model.php'),
			'views' => array('article.php','news.php', 'block.php', 'block2.php'),
			'language' => array ('search_lang.php')
		)
	);
	
?>
