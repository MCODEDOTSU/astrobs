<?php
class AddAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addAuction';
    var $confValid = array();
    
    function AddAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'Auction', 'validation');
    }
    
    function index()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $Sections = $this->municipal_model->getSections();
        $sectionsTree = $this->municipal_model->parseSectionsAsTree($Sections);
       
        $data = array(
            'form_open'     => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/saveAuction'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Добавить'),
            
            //Номер аукциона
            'number'                                => form_input('number', $this->validation->number),
            
            //Название
            'title'                                 => form_textarea('title', $this->validation->title),
            
            //Раздел номенклатурного справочника
            'section'                               => $this->municipal_model->parseSectionsAsDropdown($sectionsTree),
            
            //Источник финансирования заказа
            'fundingOrder'                          => form_input('fundingOrder', $this->validation->fundingOrder),
            
            //Дата проведения
            'dateHolding'                           => form_dropdown('dateHolding[day]', $this->municipal_model->DAY).
                                                       form_dropdown('dateHolding[month]', $this->municipal_model->MONTH).
                                                       form_input('dateHolding[year]'),
            
            //Заказчик                                                
            'customer'                              => form_textarea('customer', $this->validation->customer),
            
            //Уполномоченный орган
            'authorisedBody'                        => form_textarea('authorisedBody', $this->validation->authorisedBody),
            
            //Место, порядок и время проведения аукциона
            'placeOrderAndTimeOfAuction'            => form_textarea('placeOrderAndTimeOfAuction', $this->validation->placeOrderAndTimeOfAuction),
            
            //Предмет муниципального контракта
            'subjectMunicipalContract'              => form_textarea('subjectMunicipalContract', $this->validation->subjectMunicipalContract),
            
            //Место, условия и сроки поставок товара, выполнения работ, оказания услуг
            'locationWorksAndServices'              => form_textarea('locationWorksAndServices', $this->validation->locationWorksAndServices),
            
            //Форма, сроки и порядок оплаты товара (работ, услуг)
            'procedureForPaymentOfGoods'            => form_textarea('procedureForPaymentOfGoods', $this->validation->procedureForPaymentOfGoods),
            
            //Начальная (максимальная) цена контракта
            'initialContractPrice'                  => form_input('initialContractPrice', $this->validation->initialContractPrice),
            
            //Преимущества ОИ и УИС
            'advantageOIAndUIS'                     => form_textarea('advantageOIAndUIS', $this->validation->advantageOIAndUIS),
            
            //Срок, место и порядок предоставления документации об аукционе , официальный сайт на котором размещена документация об аукционе
            'termPlaceAndOrderAndOfficialSite'      => form_textarea('termPlaceAndOrderAndOfficialSite', $this->validation->termPlaceAndOrderAndOfficialSite),
            
            //Обеспечение исполнения контракта
            'contractEnforcement'                   => form_textarea('contractEnforcement', $this->validation->contractEnforcement),
            
            //Обеспечение заявки
            'ensuringApplication'                   => form_textarea('ensuringApplication', $this->validation->ensuringApplication),
            
            //Место, порядок, даты начала и окончания подачи заявок на участие в аукционе
            'placeOrderDates'                       => form_textarea('placeOrderDates', $this->validation->placeOrderDates)
        );
        
        $this->display->_content('<h2>Добавление аукциона</h2>');
         
        $this->module->parse($this->moduleName, $this->controllerName.'/form.php', $data);
    }
    
    function saveAuction()
    {
        $this->validation->set_rules($this->confValid['rules']);   
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            
            $sectionAsString = '';
            foreach($this->validation->section as $k => $v){
                if($v == 0) continue;
                $sectionAsString .= $k.':'.$v.';';   
            } 
            
            $insertId = $this->addAuction_model->create(array(
                'number'                            => $this->validation->number,    
                'title'                             => $this->validation->title,    
                'section'                           => $sectionAsString,    
                'fundingOrder'                      => $this->validation->fundingOrder,    
                'dateHolding'                       => mktime(0,0,0, $this->validation->dateHolding['month'], $this->validation->dateHolding['day'], $this->validation->dateHolding['year']),    
                'customer'                          => $this->validation->customer,    
                'authorisedBody'                    => $this->validation->authorisedBody,    
                'placeOrderAndTimeOfAuction'        => $this->validation->placeOrderAndTimeOfAuction,    
                'subjectMunicipalContract'          => $this->validation->subjectMunicipalContract,    
                'locationWorksAndServices'          => $this->validation->locationWorksAndServices,    
                'procedureForPaymentOfGoods'        => $this->validation->procedureForPaymentOfGoods,    
                'initialContractPrice'              => $this->validation->initialContractPrice,    
                'advantageOIAndUIS'                 => $this->validation->advantageOIAndUIS,    
                'termPlaceAndOrderAndOfficialSite'  => $this->validation->termPlaceAndOrderAndOfficialSite,    
                'contractEnforcement'               => $this->validation->contractEnforcement,    
                'ensuringApplication'               => $this->validation->ensuringApplication,    
                'placeOrderDates'                   => $this->validation->placeOrderDates,    
            ));
            
            redirect('admin/'.$this->moduleName.'/municipalFiles/index/auction/'.$insertId);
        } else {
            $this->index();
        }
    }
}
?>