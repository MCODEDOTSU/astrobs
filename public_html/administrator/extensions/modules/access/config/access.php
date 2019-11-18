<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['access'] = array(
    'title' => 'Редактирование прав доступа у групп',
    'access' => true,
    'includes' => array(
        'views' => array('index.php', 'groups.php'),
        'models' => array('access_model.php')
    )
);
?>
