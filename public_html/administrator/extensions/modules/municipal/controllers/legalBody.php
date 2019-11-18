<?php
class LegalBody extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'legalBody';
    
    function LegalBody()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Юридические лица</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addLegalBody', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $legalBody = $this->legalBody_model->get();  if(count($legalBody) == 0) return FALSE;
        
        foreach($legalBody as $body){
            $data['legalBody'][] = array(
                'id'            => $body['id'],    
                'fullName'      => $body['fullName'],
                'shortName'     => $body['shortName'],
                'formEntity'    => $body['formEntity'],
                'inn'           => $body['inn'],
                'kpp'           => $body['kpp'],
                'actions'       => ''
            );   
        }
        
        $this->module->parse($this->moduleName, $this->controllerName.'/index.php', $data);    
    }
}  
?>
