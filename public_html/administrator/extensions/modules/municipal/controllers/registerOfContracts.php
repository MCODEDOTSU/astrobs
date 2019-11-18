<?php
class RegisterOfContracts extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'registerOfContracts';
    var $confValid = array(); 
    
    function RegisterOfContracts()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'RegisterOfContracts', 'validation');
    }
    
    function index()
    {
        $data = array(
            'form_open'     => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/search'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Искать'),
            
            'from'          => form_dropdown('from[day]', $this->municipal_model->DAY).
                               form_dropdown('from[month]', $this->municipal_model->MONTH).
                               form_input('from[year]', date('Y')-1),
                               
            'to'            => form_dropdown('to[day]', $this->municipal_model->DAY).
                               form_dropdown('to[month]', $this->municipal_model->MONTH).
                               form_input('to[year]', date('Y')),
                               
            'object'        => form_radio('object', 1). 'Аукционы&nbsp;'.
                               form_radio('object', 2). 'Котировки&nbsp;'.                    
                               form_radio('object', 3). 'Конкурсы&nbsp;'.                    
                               form_radio('object', 4). 'Электронные аукционы'                    
            
        );
        
        $this->display->_content('<h2>Реестр контрактов</h2>');
        $this->module->parse($this->moduleName, $this->controllerName.'/index.php', $data);    
    }
    
    function search()
    {
        $this->validation->set_rules($this->confValid['rules']);    
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            
            $from = mktime(0,0,0, $this->validation->from['month'], $this->validation->from['day'], $this->validation->from['year']);
            $to = mktime(0,0,0, $this->validation->to['month'], $this->validation->to['day'], $this->validation->to['year']); 
            
            switch($this->validation->object){
                
                // Аукционы
                case 1: 
                    $resultArray = $this->archiveOfAuction_model->extra(array('date >' => $from, 'date <' => $to, 'archive' => 1));
                    
                    break;
                
                // Котировки
                case 2:
                    $resultArray = $this->archiveOfQuotations_model->extra(array('date >' => $from, 'date <' => $to, 'archive' => 1));
                    break;    
                
                // Конкурсы
                case 3:
                    $resultArray = $this->archiveOfConcurs_model->extra(array('date >' => $from, 'date <' => $to, 'archive' => 1));
                    break;    
                
                // Электронные аукционы
                case 4:
                    $resultArray = $this->archiveOfEAuction_model->extra(array('date >' => $from, 'date <' => $to, 'archive' => 1));
                    break;
                    
                default:
                    redirect('admin/'.$this->moduleName.'/'.$this->controllerName); 
                    break;    
                        
            }
            
            if(count($resultArray) == 0) {
                redirect('admin/'.$this->moduleName.'/'.$this->controllerName);      
            }
            
            foreach($resultArray as $object){
                $data['object'][] = array(
                    'number' => $object['number'],
                    'title' => anchor('admin/'.$this->moduleName.'/'.$this->controllerName, $object['title']),
                );    
            }
            
            $this->index();
            
            $this->module->parse($this->moduleName, $this->controllerName.'/search.php', $data);
               
        } else {
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName);    
        }   
    }
}
?>