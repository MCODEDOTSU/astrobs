<?php
$config['anchor'] = array(
    'title' => 'Ссылка',
    'place' => array(
        'anchor_model' => array(
            'create' => 'create',
            'remove' => 'delete'
        )
    ),
    'icon' => 'link.png',
    'includes' => array(
        'models' => array('anchor_model.php'),
        'views' => array('form.php')
    )
);
?>
