<?php if (!defined('BASEPATH')) exit('No direct script access allowed');        
$config['document'] = array(
    'title' => 'Документ',
    'includes' => array(
        'models' => 'document_model.php',
        'views' => array('view.php')
    ),
    'place' => array(
        'document_model' => array(
            'create' => 'create',
            'remove' => 'delete'
        )
    ),
    'icon' => 'page_save.png'
);
?>
