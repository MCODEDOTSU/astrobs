<?php
$config['cattours'] = array(
    'title' => 'Туры',
    'left_menu' => array(
        'news_model' => array(
            'category' => 'news_in_category'
        )
    ),
    'includes' => array(
        'models' => array('tours_model.php'),
        'views'  => array('index.php', 'categories.php', 'objects.php', 'object.php', 'getord.php'),
        'language'	=> array ('tours_lang.php')
    )
);
?>
