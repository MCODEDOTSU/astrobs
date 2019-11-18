<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['tours'] = array(
    'title'     => 'Назначение туров',
    'access'    => true,
    'icon'      => 'chart_organisation.png',
    'includes'  => array(
        //'libraries'     => array('Folder.php','File.php','Category.php'),
        'models'        => array('place_model.php'),
        'views'         => array('tours.php', 'form.php')
    )
);
?>
