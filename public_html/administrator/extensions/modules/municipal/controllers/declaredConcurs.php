<?php
class DeclaredConcurs extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'declaredConcurs';
    
    function DeclaredConcurs()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Объявленные конкурсы</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addConcurs', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $declaredConcurs = $this->declaredConcurs_model->extra(array('archive' => 0));  if(count($declaredConcurs) == 0) return FALSE;
        
        foreach($declaredConcurs as $concurs){
            
            $data['concurs'][] = array(
                'id'            => $concurs['id'],    
                'number'        => $concurs['number'],
                'title'         => $concurs['title'],
                'section'       => $this->municipal_model->getSectionsTitleAsPath($concurs['section']),
                'dataToOpen'    => date('d.m.Y',$concurs['dataToOpen']).'&nbsp;г.',
                'actions'       => anchor('admin/'.$this->moduleName.'/municipalFiles/index/concurs/'.$concurs['id'], _icon('page_white'),'title="Файлы"').
                                   anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/delete/'.$concurs['id'],_icon('delete'), 'title="Удалить"')     
            );   
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data);   
    }
    
    function delete()
    {
        $id_concurs = $this->uri->segment(5);
        
        if(!is_numeric($id_concurs)){
            return FALSE;    
        }
        
        $this->declaredConcurs_model->delete(array('id' => $id_concurs)); 
        
        redirect('admin/'.$this->moduleName.'/'.$this->controllerName);  
    }
}
?>