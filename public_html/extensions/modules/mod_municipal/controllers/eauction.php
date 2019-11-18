<?php
class EAuction extends Public_Controller
{
    var $moduleName = 'mod_municipal';
    var $controllerName = 'eauction';
    
    function EAuction()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $this->display->_content('<h4><span>Объявленные аукционы</span></h4>');
        
        $EAuctions = $this->mod_municipal_eauction_model->get(array('archive' => 0));
        
        if(count($EAuctions) == 0){
            return FALSE;
        }
        
        foreach($EAuctions as $eauction){
            $data['eauction'][] = array(
                'number'        => $eauction['number'],
                'title'         => $eauction['title'],
                'section'       => $eauction['section'],
                'dateHolding'   => $eauction['dateHolding']    
            );
        }
        
        $this->module->parse($this->moduleName, 'eauction/index.php', $data);
        
    }
}
?>
