<?php
$config['menu'] = array(

    'title'    => 'Admin navigation menu',
    
    // Module position
    'type'     => 'header',

    
    // Load function
    'function' => 'get_menu',

    
    // Include files
    'includes' => array(
    
    
        // Include files from css folder
        'css' => 'jquery.jdMenu.css',
        
        
        // Include files from js folder
        'js' => array(
                    'jquery.bgiframe.js',
                    //'jquery.dimensions.js',
                    'jquery.positionBy.js',
                    'jquery.jdMenu.js'
                ),
        
        // Include files from views models
        'models' => 'menu_model.php',
        
        // Include files from views folder
        'views' => array(
        
           
            'jdMenu.php'
            
        )
    )  
);
?>
