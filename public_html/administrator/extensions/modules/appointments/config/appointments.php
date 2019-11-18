<?php
$config['appointments'] = array(
    'title' => 'Записи на прием',
    'access' => true,
    'icon' => 'table.png',
    'includes' => array(
        'views' => array('index.php'),
        'models' => array('appointments_model.php')
    )
);
?>
