<?php
class AddQuotation extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addQuotation';
    var $confValid = array();
    
    function AddQuotation()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'Quotation', 'validation'); 
    }
    
    function index()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $Sections = $this->municipal_model->getSections();
        $sectionsTree = $this->municipal_model->parseSectionsAsTree($Sections);
       
        $allBudgetPeoples = $this->budgetPeoples_model->get();
       
        if(count($allBudgetPeoples) == 0){
            return FALSE;
        }
        
        foreach($allBudgetPeoples as $body){
            $budgetPeoplesAsDropdown[$body['id']] = $body['title'];            
        }
       
        $data = array(
            'form_open'     => form_open('admin/municipal/addQuotation/save'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Добавить'),
            
            // Раздел номенклатурного справочника
            'section'                               => $this->municipal_model->parseSectionsAsDropdown($sectionsTree),   
            
            // Заказчик
            'customer'                              => form_dropdown('customer', $budgetPeoplesAsDropdown, $this->validation->customer),
            
            // Номер
            'number'                                => form_input('number', $this->validation->number),
            
            // Источник финансирования заказа
            'fundingOrder'                          => form_input('fundingOrder', $this->validation->fundingOrder),
            
            // Наименование, характеристики и количество поставляемых товаров, наименование и объем выполняемых работ
            'nameCharacteristicsQuantityWorks'      => form_textarea('nameCharacteristicsQuantityWorks', $this->validation->nameCharacteristicsQuantityWorks),
            
            // Место доставки поставляемых товаров, место выполнения работ, место оказания услуг
            'placeDeliveryWorks'                    => form_textarea('placeDeliveryWorks', $this->validation->placeDeliveryWorks),
            
            // Сроки поставок товаров, выполнения работ, оказания услуг
            'timingDeliveriesWorksServices'         => form_textarea('timingDeliveriesWorksServices', $this->validation->timingDeliveriesWorksServices),
            
            // Сведения о включенных (не включенных) в цену товаров, работ, услуг расходах, в том числе расходах на перевозку, страхование, уплату таможенных пошлин, налогов, сборов и других обязательных платежей
            'detailsIncludedPriceWorksServices'     => form_textarea('detailsIncludedPriceWorksServices', $this->validation->detailsIncludedPriceWorksServices),
            
            // Максимальная цена контракта, определяемая заказчиком, в результате изучения рынка необходимых товаров, работ, услуг
            'maximumPriceContract'                  => form_textarea('maximumPriceContract', $this->validation->maximumPriceContract),
            
            // Место подачи котировочных заявок
            'placeSubmissionQuotedApplications'     => form_textarea('placeSubmissionQuotedApplications', $this->validation->placeSubmissionQuotedApplications),
            
            // Дата подачи котировочной заявки, дата публикации на сайте
            'dateSubmissionQuotedApplications'      => form_dropdown('dateSubmissionQuotedApplications[day]', $this->municipal_model->DAY).
                                                       form_dropdown('dateSubmissionQuotedApplications[month]', $this->municipal_model->MONTH).
                                                       form_input('dateSubmissionQuotedApplications[year]'),
            
            // Срок и условия оплаты поставок товаров, выполнения работ, оказания услуг
            'termsOfDeliveriesOfTheGoods'           => form_textarea('termsOfDeliveriesOfTheGoods', $this->validation->termsOfDeliveriesOfTheGoods),
            
            // Дата окончания приема
            'receptionDateClosed'                   => form_dropdown('receptionDateClosed[day]', $this->municipal_model->DAY).
                                                       form_dropdown('receptionDateClosed[month]', $this->municipal_model->MONTH).
                                                       form_input('receptionDateClosed[year]')
        );
        
        $this->display->_content('<h2>Добавление аукциона</h2>');
         
        $this->module->parse('municipal', 'addQuotation/form.php', $data);    
    }
    
    function save()
    {
        $this->validation->set_rules($this->confValid['rules']);   
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            $sectionAsString = '';
            foreach($this->validation->section as $k => $v){
                if($v == 0) continue;
                $sectionAsString .= $k.':'.$v.';';   
            } 
            
            $insertId = $this->addQuotation_model->create(array(
                'section'                               => $sectionAsString,    
                'number'                                => $this->validation->number,    
                'customer'                              => $this->validation->customer,    
                'fundingOrder'                          => $this->validation->fundingOrder,    
                'nameCharacteristicsQuantityWorks'      => $this->validation->nameCharacteristicsQuantityWorks,    
                'placeDeliveryWorks'                    => $this->validation->placeDeliveryWorks,    
                'timingDeliveriesWorksServices'         => $this->validation->timingDeliveriesWorksServices,    
                'detailsIncludedPriceWorksServices'     => $this->validation->detailsIncludedPriceWorksServices,    
                'maximumPriceContract'                  => $this->validation->maximumPriceContract,    
                'placeSubmissionQuotedApplications'     => $this->validation->placeSubmissionQuotedApplications,    
                'dateSubmissionQuotedApplications'      => mktime(0,0,0, $this->validation->dateSubmissionQuotedApplications['month'], $this->validation->dateSubmissionQuotedApplications['day'], $this->validation->dateSubmissionQuotedApplications['year']),    
                'termsOfDeliveriesOfTheGoods'           => $this->validation->termsOfDeliveriesOfTheGoods,    
                'receptionDateClosed'                   => mktime(0,0,0, $this->validation->receptionDateClosed['month'], $this->validation->receptionDateClosed['day'], $this->validation->receptionDateClosed['year']),    
            ));
            
            redirect('admin/'.$this->moduleName.'/municipalFiles/index/quotation/'.$insertId);
        } else {
            $this->index();
        }    
    }
}
?>