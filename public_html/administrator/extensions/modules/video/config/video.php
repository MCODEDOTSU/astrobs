<?php
$config['video'] = array(
    'title' => 'Видео',
    'place' => array(
        'video_model' => array(
            'create' => 'create',
            'remove' => 'delete',
            'file'   => 'file'
        )
    ),
    'icon' => 'film.png',
    'includes' => array(
        'views' => array('form.php'),
        'models' => array('video_model.php')
    ),
    'validation' => array(
        'rules' => array(
            'title' => 'trim|required|xss_clean',
            'desc'  => 'trim|xss_clean',
            'file'  => 'trim|required|xss_clean'
        ),
        'fields' => array(
            'title' => 'Название',
            'desc'  => 'Описание',
            'file'  => 'Видоефайл'
        )
    )
);
?>
