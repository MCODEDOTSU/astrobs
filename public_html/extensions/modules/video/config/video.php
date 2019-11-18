<?php
$config['video'] = array(
    'title' => 'Изображение',
    'left_menu' => array(
        'video_model' => array(
            'category' => 'photo_in_category'
        )
    ),
    'includes' => array(
        'models' => array('video_model.php'),
        'views'  => array('view.php')
    )
);  
?>
