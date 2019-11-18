<?php
$config['mod_municipal'] = array(
    'title' => 'Муниципальный заказ',
    'function' => 'block',
    'type' => 'left',
    
    'includes' => array(
        'views' => array(
            'auction/index.php',    
            'concurs/index.php',    
            'eauction/index.php',    
            'quotation/index.php'    
        ),
        'models' => array(
            'mod_municipal_model.php',
            'mod_municipal_auction_model.php',
            'mod_municipal_concurs_model.php',  
            'mod_municipal_eauction_model.php',  
            'mod_municipal_quotation_model.php'  
        )    
    ),
    'tables' => array(
        'Auction'   => 'th_municipal_auctions',
        'Concurs'   => 'th_municipal_concurs',
        'EAuction'  => 'th_municipal_eauctions',
        'Quotation' => 'th_municipal_quotations'
    )
);
?>
