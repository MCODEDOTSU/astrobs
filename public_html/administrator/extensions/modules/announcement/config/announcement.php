<?php
$config['announcement'] = array(

    'title'         => 'Объявление',

    'place'     => array(

                        'announcement_model' => array(
                                            'create' => 'create',
                                            'remove' => 'delete'
                                        )

                    ),

    'icon'          => 'article.png',

    'includes'      => array(

                        'models'    => array('announcement_model.php'),
                        'views'     => array('form.php')

                    )
);
?>
