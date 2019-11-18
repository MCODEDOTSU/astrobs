<?php
	$config['version_site'] = array (
		'type'		=> 'versite',
		'title'		=> 'Version Site',
		'function'	=> 'get_version',
		'includes'	=> array (
			//'views'		=> array ('printer.php'),
			'models'	=> array ('version_site_model.php')
		) 
	);
?>
