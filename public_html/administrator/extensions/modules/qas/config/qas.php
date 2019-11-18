<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');        
$config['qas'] = array(
    'title'         => 'Вопрос - ответ',
    'access'        => true,
    'icon'          => 'comments.png',
    
    'includes'      => array(
                        'models'    => array('qas_model.php'),
                        'views'     => array('qas.php', 'form_qas.php')
                    )
);  
?>
