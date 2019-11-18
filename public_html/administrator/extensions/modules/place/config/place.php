<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['place'] = array(
    'title'     => 'Редактор структуры',
    'access'    => true,
    'icon'      => 'chart_organisation.png',
    'includes'  => array(
        'libraries'     => array('Folder.php','File.php','Category.php'),
        'models'        => array('place_model.php'),
        'views'         => array('item.php','place.php', 'content.php','form.php')
    )
);
?>
