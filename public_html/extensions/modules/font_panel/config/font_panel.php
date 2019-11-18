<?php
	$config['font_panel'] = array (
		'type'		=> 'fontpanel',
		'title'		=> 'Font Panel',
		'function'	=> 'get_panel',
		'includes'	=> array (
			'views'		=> array ('panel.php'),
			'models'	=> array ('font_panel_model.php'),
			'js'		=>	array('site_panel.js')
		) 
	);
?>
