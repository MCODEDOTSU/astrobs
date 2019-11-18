<?php
$config['users'] = array(

    
    'title'    => 'Site users',
    
    'access'   => true,
    
    // Include files
    'includes' => array(
    
                        // Include files from views language
                        //'language' => 'auth_lang.php',


                        // Include files from views libraries
                        //'libraries' => 'aulib.php',



                        // Include files from views models
                        'models' => 'users_model.php',


                        // Include files from views folder
                        'views' => array(

                                        'index.php',
                                        'form.php'
                                        
                                    )
                             
                  ),
     
     'TABLES' => array(
     
                    'Auser'   => 'th_auser',
                    'Aurole'  => 'th_aurole',
                    'Augroup' => 'th_augroup'
                    
                 )              
);
?>
