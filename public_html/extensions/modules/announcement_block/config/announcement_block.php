<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['announcement_block'] = array(
    'title' => 'Объявления',
    'type' => 'left',
    'function' => 'block',
    'includes' => array(
        'models' => 'announcement_block_model.php',
        'views'  => array('view.php','block.php')
    )
);
?>
