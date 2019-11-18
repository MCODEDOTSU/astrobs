<?php
$config['rubric'] = array(
    'title' => 'Рубрики',
    'access' => true,
    'includes' => array(
        'views' => array('index.php', 'desc.php')
    ),
    'tables' => array('Rubric' => 'th_category')
); 
?>
