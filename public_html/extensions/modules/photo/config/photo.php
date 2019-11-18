<?php
$config['photo'] = array(
    'title' => 'Изображение',
    'left_menu' => array(
        'photo_model' => array(
            'category' => 'photo_in_category'
        )
    ),
    'includes' => array(
        'models' => array('photo_model.php'),
        'views'  => array('view.php')
    )
);  
?>
