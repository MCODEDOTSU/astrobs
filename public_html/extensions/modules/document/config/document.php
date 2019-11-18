<?php
$config['document'] = array(
    'title' => 'Документ',
    'left_menu' => array(
        'document_model' => array(
            'category' => 'document_in_category'
        )
    ),
    'includes' => array(
        'models' => array('document_model.php'),
        'views'  => array('view.php')
    )
);  
?>
