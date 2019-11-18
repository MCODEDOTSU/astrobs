<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['random_photo'] = array(
    'title' => 'Случайное фото',
    'type' => 'right',
    'function' => 'block',
    'includes' => array(
        'models' => 'random_photo_model.php',
        'views'  => array('view.php','block.php')
    )
);
?>
