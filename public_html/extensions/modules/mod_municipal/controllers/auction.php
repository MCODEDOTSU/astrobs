<?php
class Auction extends Public_Controller
{
    var $moduleName = 'mod_municipal';
    var $controllerName = 'auction';
    
    function Auction()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $this->display->_content('<h4><span>Объявленные аукционы</span></h4>');
        
        $Auctions = $this->mod_municipal_auction_model->get(array('archive' => 0));
        
        if(count($Auctions) == 0){
            return FALSE;
        }
        
        foreach($Auctions as $auction){
            $data['auction'][] = array(
                'number'        => $auction['number'],
                'title'         => $auction['title'],
                'section'       => $auction['section'],
                'dateHolding'   => $auction['dateHolding']    
            );
        }
        
        $this->module->parse($this->moduleName, 'auction/index.php', $data);
        
    }
}
?>
