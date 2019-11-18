<?php
class Quotation extends Public_Controller
{
    var $moduleName = 'mod_municipal';
    var $controllerName = 'quotation';
    
    function Concurs()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $this->display->_content('<h4><span>Список котировок</span></h4>');
        
        $Quotations = $this->mod_municipal_quotation_model->get(array('archive' => 0));
        
        if(count($Quotations) == 0){
            return FALSE;
        }
        
        foreach($Quotations as $quotation){
            $data['quotation'][] = array(
                'dateSubmissionQuotedApplications'  => $quotation['dateSubmissionQuotedApplications'],
                'receptionDateClosed'               => $quotation['receptionDateClosed'],
                'customer'                          => $quotation['customer'],
                'number'                            => $quotation['number'],    
                'nameCharacteristicsQuantityWorks'  => $quotation['nameCharacteristicsQuantityWorks']    
            );
        }
        
        $this->module->parse($this->moduleName, 'quotation/index.php', $data);    
    }
}
?>
