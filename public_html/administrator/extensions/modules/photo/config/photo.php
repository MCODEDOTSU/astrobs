<?php
$config['photo'] = array(
    'title' => 'Изображение',
    'place' => array(
        'photo_model' => array(
            'create' => 'create',
            'remove' => 'delete',
            'file'   => 'file'
        )
    ),
    'icon' => 'photo.png',
    'includes' => array(
        'views' => array('form.php'),
        'models' => array('photo_model.php')
    ),
    'validation' => array(
        'rules' => array(
            'title' => 'trim|required|xss_clean',
            'desc'  => 'trim|xss_clean'
        ),
        'fields' => array(
            'title' => 'Название',
            'desc'  => 'Описание'
        )
    )
);
?>