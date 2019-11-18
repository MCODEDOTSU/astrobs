<?php
class ArchiveOfConcurs extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'archiveOfConcurs';
    
    function ArchiveOfConcurs()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);     
    }
    
    function index()
    {
        $this->display->_content('<h2>Объявленные конкурсы</h2>');
        $this->display->_content('<div id="cms_bar"></div>');
        
        $declaredConcurs = $this->archiveOfConcurs_model->extra(array('archive' => 1));  if(count($declaredConcurs) == 0) return FALSE;
        
        foreach($declaredConcurs as $concurs){
            
            $data['concurs'][] = array(
                'id'            => $concurs['id'],    
                'number'        => $concurs['number'],
                'title'         => $concurs['title'],
                'section'       => $this->municipal_model->getSectionsTitleAsPath($concurs['section']),
                'dataToOpen'    => date('d.m.Y',$concurs['dataToOpen']).'&nbsp;г.',
                'actions'       => anchor('admin/'.$this->moduleName.'/municipalFiles/index/concurs/'.$concurs['id'], _icon('page_white'),'title="Файлы"')
                                        
            );   
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data);   
    }
}
?>