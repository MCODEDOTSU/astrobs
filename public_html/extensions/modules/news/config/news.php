<?php
$config['news'] = array(
    'title' => 'Новость',
    'left_menu' => array(
        'news_model' => array(
            'category' => 'news_in_category'
        )
    ),
    'includes' => array(
        'models' => array('news_model.php'),
        'views'  => array('view.php', 'block.php')
    )
);
?>
