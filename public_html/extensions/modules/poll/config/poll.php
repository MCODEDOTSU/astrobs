<?php
$config['poll'] = array(
    'title' => 'Poll',
    'type' => 'right',
    'function' => 'block',
    'includes' => array(
        'models' => array('poll_model.php', 'poll_answer_model.php'),
        'views' => array('block.php', 'view.php'),
        'js' => array('poll.js')
    )
); 
?>
