<?php
$config['calendar'] = array(
    'title' => 'Calendar',
    'type' => 'right',
    'function' => 'calendar_block',
    'includes' => array(
        'models'=> array ('calendar_model.php'),
        'views'  => array('article.php', 'block.php')
    ),
);  
?>
