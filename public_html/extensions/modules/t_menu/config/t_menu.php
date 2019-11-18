<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['t_menu'] = array(

    'type'     => 'header',

    'function' => 'block',

    'includes' => array(

        'views' => array(
            'block.php'
        ),
        
        'css' => 'jquery.jdMenu.css',

        'js' => array(
            'jquery.bgiframe.js',
            'jquery.positionBy.js',
            'jquery.jdMenu.js'
            
        ),

        // Include files from views libraries
//        'libraries' => array(
//            'Folder.php',
//            'File.php',
//            'Category.php'
//        ),

        'models' => array(
            't_menu_model.php'
        )

    )
);
?>
