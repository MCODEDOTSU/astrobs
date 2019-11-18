<?php
class PhysicalBody extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'physicalBody';
    
    function PhysicalBody()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Физические лица</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addPhysicalBody', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $physicalBody = $this->physicalBody_model->get();  if(count($physicalBody) == 0) return FALSE;
        
        foreach($physicalBody as $body){
            $data['physicalBody'][] = array(
                'id'            => $body['id'],    
                'surname'       => $body['surname'],
                'name'          => $body['name'],
                'patronymic'    => $body['patronymic'],
                'inn'           => $body['inn'],
                'dateOfBirth'   => $body['dateOfBirth'],
                'actions'       => ''
            );   
        }
        
        $this->module->parse($this->moduleName, $this->controllerName.'/index.php', $data);    
    }
}  
?>
