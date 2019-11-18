<?php
$config['library'] = array(
    'title' => 'Библиотека',
    'access' => true,
    'icon' => 'book.png',
    'includes' => array(
        'views' => array('index.php', 'form.php', 'category.php', 'category_form.php'),
        'models' => array('library_model.php', 'library_category_model.php')
    )
);
?>
