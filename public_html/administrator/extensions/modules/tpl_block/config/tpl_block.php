<?php

$config['tpl_block'] = array(
    'title' => 'Блоки',
    'access' => true,
    'includes' => array(
        'views' => array('tpl_block_main.php', 'tpl_block_right.php', 'tpl_block_left.php', 'tpl_block_bottom.php')
    ),
    'tables' => array('tpl_block' => 'th_tpl_block')
);

?>
