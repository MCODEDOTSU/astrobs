<?php
class ArchiveOfEAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'archiveOfEAuction';
    
    function ArchiveOfEAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Архив электронных аукционов</h2>');
        $this->display->_content('<div id="cms_bar"></div>');
        
        $archiveOfEAuction = $this->archiveOfEAuction_model->extra(array('archive' => 1));  
        
        if(count($archiveOfEAuction) == 0) {
            return FALSE;
        }
        
        foreach($archiveOfEAuction as $eauction){
            $data['eauction'][] = array(
                'id'                        => $eauction['id'],    
                'number'                    => $eauction['number'],
                'title'                     => $eauction['title'],
                'subjectMunicipalContract'  => $eauction['subjectMunicipalContract'],
                'dateStartWork'             => date('d.m.Y', $eauction['dateStartWork']),
                'timeStartOrEnd'            => $eauction['timeStartOrEnd'],
                'initialContractPrice'      => $eauction['initialContractPrice'],
                'actions'                   => anchor('admin/'.$this->moduleName.'/municipalFiles/index/eauction/'.$eauction['id'], _icon('page_white'),'title="Прикрепленные файлы"')    
            );   
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data); 
    }
    
}
?>
