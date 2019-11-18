<?php
class ArchiveOfAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'archiveOfAuction';
    
    function ArchiveOfAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Объявленные аукционы</h2>');
        $this->display->_content('<div id="cms_bar"></div>');
        
        $archiveAuctions = $this->archiveOfAuction_model->extra(array('archive' => 1));  if(count($archiveAuctions) == 0) return FALSE;
        
        foreach($archiveAuctions as $auction){
            $data['auction'][] = array(
                'id'            => $auction['id'],    
                'number'        => $auction['number'],
                'title'         => $auction['title'],
                'section'       => $this->municipal_model->getSectionsTitleAsPath($auction['section']),
                'dateHolding'   => date('d.m.Y', $auction['dateHolding']).'&nbsp;г.',
                'actions'       => anchor('admin/'.$this->moduleName.'/municipalFiles/index/auction/'.$auction['id'], _icon('page_white'),'title="Прикрепленные файлы"').
                                    anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/delete/'.$auction['id'], _icon('delete'), 'title="Удалить"')    
            );   
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data);    
    }
}
?>