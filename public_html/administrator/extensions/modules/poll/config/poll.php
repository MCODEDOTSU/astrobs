<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['poll'] = array(

    'title'    => 'Голосование',
    
    'access'   => true,
    
    'includes' => array(
        
        'views' => array(
        
            'form_poll.php', 
            'polls.php', 
            'answers.php', 
            'form_answer.php'
            
        ),
        
        'models' => array(
        
            'poll_model.php',
            'answer_model.php'
            
        )
        
    )
             
);
?>