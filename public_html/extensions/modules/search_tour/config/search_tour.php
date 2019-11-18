<?php
	$config['search_tour'] = array (
		'title'		=>	'Поиск тура',
		'includes'	=>	array (
			'models'	=>	array ('search_tour_model.php'),
			'views'		=>	array ('index.php', 'results.php'),
			'js'		=>	array ('search_tours.js'),
			'language'	=>	array ('search_tour_lang.php')
		)
	);
?>
