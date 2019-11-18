<?php

	$config['folders'] = array(
		'title' => 'Folders',
		'left_menu' => array(
		    'news_model' => array(
		        'category' => 'news_in_category'
		    )
		),
		'includes' => array(
			//'libraries' => array('lmenu.php'),
			'models' => array('folders_model.php'),
			'views' => array ('index.php')
		)
	);

?>
