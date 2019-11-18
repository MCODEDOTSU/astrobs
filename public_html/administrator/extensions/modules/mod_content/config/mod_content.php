<?php
$config['mod_content'] = array(
    'title' => 'Редактор контента',
    'access' => true,
    'includes' => array(
        'views' => array('index.php'),
        'models' => array('content_model.php')
    )
);
?>
