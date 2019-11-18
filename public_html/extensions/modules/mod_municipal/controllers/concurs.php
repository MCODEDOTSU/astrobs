<?php
class Concurs extends Public_Controller
{
    var $moduleName = 'mod_municipal';
    var $controllerName = 'concurs';
    
    function Concurs()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $this->display->_content('<h4><span>Объявленные конкурсы</span></h4>');
        
        $Concursis = $this->mod_municipal_concurs_model->get(array('archive' => 0));
        
        if(count($Concursis) == 0){
            return FALSE;
        }
        
        foreach($Concursis as $concurs){
            $data['concurs'][] = array(
                'number'        => $concurs['number'],
                'title'         => $concurs['title'],
                'section'       => $concurs['section'],
                'dataToOpen'    => $concurs['dataToOpen']    
            );
        }
        
        $this->module->parse($this->moduleName, 'concurs/index.php', $data);    
    }
}
?>
