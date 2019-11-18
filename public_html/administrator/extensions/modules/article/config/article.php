<?php
$config['article'] = array(
    
    'title'         => 'Статья',
    
    'place'     => array(

                        'article_model' => array(
                                            'create' => 'create',
                                            'remove' => 'delete'
                                        )
                                        
                    ),
    
    'icon'          => 'article.png',
    
    'includes'      => array(
    
                        'models'    => array('article_model.php'),
                        'views'     => array('form.php')
    
                    )
);  
?>
