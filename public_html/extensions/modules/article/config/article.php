<?php
$config['article'] = array(
    'title' => 'Article',
    'left_menu' => array(
        'article_model' => array(
            'category' => 'article_in_category'
        )
    ),
    'includes' => array(
        'models' => array('article_model.php'),
        'views'  => array('view.php', 'ordresult.php'),
        'js'     => array ('order_tours.js')
    )
);  
?>
