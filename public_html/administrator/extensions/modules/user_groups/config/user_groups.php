<?php
/* 
 * Создание, удаление, редактирование групп пользователей
 */
$config['user_groups'] = array(
    'title' => 'Модуль групп пользователей',
    'access' => true,
    'includes' => array(
        'views' => array('index.php', 'form.php'),
        'models' => array('user_groups_model.php')
    )
);
?>
