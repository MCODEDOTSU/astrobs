<?php
class ListQuotations extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'listQuotations';
    
    
    function ListQuotations()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $this->display->_content('<h2>Список котировок</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addQuotation', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $listQuotation = $this->listQuotations_model->extra(array('archive' => 0));  
        
        if(count($listQuotation) == 0) {
            return FALSE;
        }
        
        foreach($listQuotation as $quotation){
            
            $Customer = $this->budgetPeoples_model->extra(array('id' => $quotation['customer']));
            
            if(count($Customer) == 0) {
                return FALSE;
            }
            
            foreach($Customer as $customer){
                $data['quotations'][] = array(
                    'dateSubmissionQuotedApplications'  => date('d.m.Y', $quotation['dateSubmissionQuotedApplications']).'&nbsp;г.',
                    'receptionDateClosed'               => date('d.m.Y', $quotation['receptionDateClosed']).'&nbsp;г.',
                    'customer'                          => $customer['title'],
                    'number'                            => $quotation['number'],
                    'nameCharacteristicsQuantityWorks'  => _substr($quotation['nameCharacteristicsQuantityWorks'], 80),
                    'actions'                           => anchor('admin/'.$this->moduleName.'/municipalFiles/index/quotation/'.$quotation['id'], _icon('page_white'),'title="Прикрепленные файлы"').
                                                           anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/delete/'.$quotation['id'], _icon('delete'),'title="Удалить"')     
                );    
            }
               
        }
        
        $this->module->parse('municipal', 'listQuotations/index.php', $data);     
    }
    
    function delete()
    {
        $id_quotation = $this->uri->segment(5);
        
        if(!is_numeric($id_quotation)) {
            return FALSE;
        }
        
        $this->listQuotations_model->delete(array('id' => $id_quotation));
        
        redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
    }
}
?>