<?php
class DeclaredAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'declaredAuction';
    
    function DeclaredAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Объявленные аукционы</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addAuction', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $declaredAuctions = $this->declaredAuction_model->extra(array('archive' => 0));  if(count($declaredAuctions) == 0) return FALSE;
        
        foreach($declaredAuctions as $auction){
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
    
    function delete()
    {
        $id_auction = $this->uri->segment(5);
        
        if(!is_numeric($id_auction)){
            return FALSE;    
        }
        
        $this->declaredAuction_model->delete(array('id' => $id_auction));
        
        redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
    }
}
?>