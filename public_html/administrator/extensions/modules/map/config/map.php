<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['map'] = array(
    'title'     => 'Карта',
    'place' => array(

                        'map_model' => array(
                                            'create' => 'create',
                                            'remove' => 'delete'
                                        )

                    ),
    'icon'      => 'map.png',
    'includes'  => array(
        'views'      => 'form.php',
        'models'    => 'map_model.php',
        'js' => array('http://api-maps.yandex.ru/1.1/index.xml?key=APSbp04BAAAAt0ynJQIAkpU1WpuCLWcHt9mgNHXn2JyTnsAAAAAAAAAAAADd7BkQn175vbguXpgr_qzcSx5T0Q==')
    )
);
?>
