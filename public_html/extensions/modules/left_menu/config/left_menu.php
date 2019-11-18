<?php

	$config['left_menu'] = array(
		'title'    => 'Site left menu',
		'type' => 'left',
		'function' => 'init',
		'includes' => array(
			'libraries' => array('lmenu.php'),
			'models' => array('left_menu_model.php')
		)
	);

?>