<?php
	$config['central_menu'] = array (
		'title'		=>	'Central Menu',
		'function'	=>	'get',
		'type'		=>	'central_menu',
		'includes'	=>	array (
			'models'	=>	array ('central_menu_model.php'),
			'js'		=>	array ('central_menu.js')
		)
	);
?>
