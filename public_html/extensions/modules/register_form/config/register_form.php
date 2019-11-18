<?php
$config['register_form'] = array(

	'title' => 'Форма регистрации',

	'includes' => array(
		'views'  => array( 
			'index.php', 	// Форма
			'accept.php',	// Сообщение при активации/деактивации
			'letterA.php',	// Письмо для активации
			'letterB.php'	// Письмо для деактивации
		),
	)
);  
?>
