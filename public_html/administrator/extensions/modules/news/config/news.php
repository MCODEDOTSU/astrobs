<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$config['news'] = array(
    'title'     => 'Новость',
    'icon'      => 'news.png',
    'place' => array(

                        'news_model' => array(
                                            'create' => 'create',
                                            'remove' => 'delete'
                                        )

                    ),
    'includes'  => array(
        'views'  => array('form.php'),
        'models' => array('news_model.php')
    )
);
?>
