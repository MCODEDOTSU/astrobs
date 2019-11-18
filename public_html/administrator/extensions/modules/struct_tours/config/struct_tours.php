<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['struct_tours'] = array(

    'title'    => 'Управление турами',
    
    'access'   => true,
    
    'includes' => array(
        
        'views' => array(
        
            'form_tour.php', 
            'get_tours.php',
			'form_cats.php', 
            'get_cats.php',
			'form_objs.php', 
            'get_objs.php',
            'orders.php',
            'ordinfo.php',
            'form_face.php'
            
        ),
        
        'models' => array(
        
            'tours_model.php',
			'cats_model.php',
			'objs_model.php',
			'orders_model'
            
        )
        
    )
             
);
?>
