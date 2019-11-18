<?php
	$config['printer'] = array (
		'type'		=> 'printer',
		'title'		=> 'Printable Version',
		'function'	=> 'printer',
		'includes'	=> array (
			//'views'		=> array ('printer.php'),
			'models'	=> array ('printer_model.php'),
			'language'	=> array ('print_lang.php')
		) 
	);
?>
