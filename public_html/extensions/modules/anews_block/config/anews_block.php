<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['anews_block'] = array(
    'title' => 'Блок новостей',
    'type' => 'right',
    'function' => 'block',
    'includes' => array(
        'models' => 'anews_block_model.php',
        'views'  => array('view.php','block.php')
    )
);
?>
