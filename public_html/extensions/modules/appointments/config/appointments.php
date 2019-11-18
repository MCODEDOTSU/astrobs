<?php
    $config['appointments'] = array(
		'title'     => 'Запись на прием',
		'includes'  => array(
			'models' => 'appointments_model.php',
			'views' => array('appointments.php'),
			'css' => array('jquery-ui-1.8.10.custom.css', 'appointments.css'),
			'js' => array('jquery-ui.min.js', 'jquery-ui-timepicker-addon.js', 'appointments.js')
		)
    );
?>
