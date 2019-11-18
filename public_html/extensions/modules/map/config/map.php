<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['map'] = array(
    'title' => 'Карта',
    'left_menu' => array(
        'map_model' => array(
            'category' => 'map_in_category'
        )
    ),
    'includes' => array(
        'models' => array('map_model.php'),
        'views' => array('view.php'),
        'js' => array('http://api-maps.yandex.ru/1.1/index.xml?key=APSbp04BAAAAt0ynJQIAkpU1WpuCLWcHt9mgNHXn2JyTnsAAAAAAAAAAAADd7BkQn175vbguXpgr_qzcSx5T0Q==')
    )
);
?>
