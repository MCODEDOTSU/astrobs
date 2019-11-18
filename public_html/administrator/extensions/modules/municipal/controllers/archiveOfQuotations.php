<?php
class ArchiveOfQuotations extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'archiveOfQuotations';
    
    function ArchiveOfQuotations()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    
    function index()
    {
        $this->display->_content('<h2>Архив котировок</h2>');
        $this->display->_content('<div id="cms_bar"></div>');
        
        $listQuotation = $this->archiveOfQuotations_model->extra(array('archive' => 1));  
        
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
                    'actions'                           => anchor('admin/'.$this->moduleName.'/municipalFiles/index/quotation/'.$quotation['id'], _icon('page_white'),'title="Прикрепленные файлы"')
                                                                
                );    
            }
               
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data);     
    }    
}
?>