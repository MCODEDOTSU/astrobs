<?php
class AddConcurs extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addConcurs';
    var $confValid = array();
    
    function AddConcurs()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'Concurs', 'validation');
    }
    
    function index()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $Sections = $this->municipal_model->getSections();
        $sectionsTree = $this->municipal_model->parseSectionsAsTree($Sections);
       
        $data = array(
            'form_open'     => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/saveConcurs'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Добавить'),
            
            //Номер конкурса
            'number'                                => form_input('number', $this->validation->number),
            
            //Название
            'title'                                 => form_input('title', $this->validation->title),
            
            //Раздел номенклатурного справочника
            'section'                               => $this->municipal_model->parseSectionsAsDropdown($sectionsTree),
            
            //Дата вскрытия конвертов с заявками
            'dataToOpen'                            => form_dropdown('dataToOpen[day]', $this->municipal_model->DAY).
                                                       form_dropdown('dataToOpen[month]', $this->municipal_model->MONTH).
                                                       form_input('dataToOpen[year]'),
            
            //Начальная (максимальная) цена контракта
            'startPrice'                            => form_input('startPrice', $this->validation->startPrice),
            
            //Заказчик                                                
            'customer'                              => form_textarea('customer', $this->validation->customer),
            
            //Уполномоченный орган
            'authorisedBody'                        => form_textarea('authorisedBody', $this->validation->authorisedBody),
            
            //Обеспечение заявки
            'demandMaintenance'                     => form_textarea('demandMaintenance', $this->validation->demandMaintenance),
            
            //Обеспечение исполнения контракта
            'maintenanceOfExecutionOfTheContract'   => form_textarea('maintenanceOfExecutionOfTheContract', $this->validation->maintenanceOfExecutionOfTheContract),
            
            //Предмет конкурса
            'competitionSubject'                    => form_textarea('competitionSubject', $this->validation->competitionSubject),
            
            //Преимущества ОИ и УИС
            'advantageOIAndUIS'                     => form_textarea('advantageOIAndUIS', $this->validation->advantageOIAndUIS),
            
            //Место поставки товара (выполнения работ, оказания услуг)
            'placeOfDeliveryOfTheGoods'             => form_textarea('placeOfDeliveryOfTheGoods', $this->validation->placeOfDeliveryOfTheGoods),
            
            //Сроки (периоды) поставок товара (выполнения работ, оказания услуг)
            'termsOfDeliveriesOfTheGoods'           => form_textarea('termsOfDeliveriesOfTheGoods', $this->validation->termsOfDeliveriesOfTheGoods),
            
            //Срок, место и порядок предоставления конкурсной документации, официальный сайт, на котором размещена конкурсная документация
            'termPlaceAndOrderAndOfficialSite'      => form_textarea('termPlaceAndOrderAndOfficialSite', $this->validation->termPlaceAndOrderAndOfficialSite),
            
            //Место, порядок, даты начала и окончания подачи заявок на участие в конкурсе
            'placeOrderDates'                       => form_textarea('placeOrderDates', $this->validation->placeOrderDates),
            
            //Место, дата и время вскрытия конвертов с заявками на участие в конкурсе
            'placeDateAndOpeningTime'               => form_textarea('placeDateAndOpeningTime', $this->validation->placeDateAndOpeningTime)
        );
        
        $this->display->_content('<h2>Добавление конкурса</h2>');
         
        $this->module->parse('municipal', 'addConcurs/form.php', $data);
    }
    
    function saveConcurs()
    {
        $this->validation->set_rules($this->confValid['rules']);   
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            
            $sectionAsString = '';
            foreach($this->validation->section as $k => $v){
                if($v == 0) continue;
                $sectionAsString .= $k.':'.$v.';';   
            } 
            
            $insertId = $this->addConcurs_model->create(array(
                'number'                                => $this->validation->number,    
                'title'                                 => $this->validation->title,    
                'section'                               => $sectionAsString,    
                'dataToOpen'                            => mktime(0,0,0,$this->validation->dataToOpen['month'], $this->validation->dataToOpen['day'],$this->validation->dataToOpen['year']),    
                'startPrice'                            => $this->validation->startPrice,    
                'customer'                              => $this->validation->customer,    
                'authorisedBody'                        => $this->validation->authorisedBody,    
                'demandMaintenance'                     => $this->validation->demandMaintenance,    
                'maintenanceOfExecutionOfTheContract'   => $this->validation->maintenanceOfExecutionOfTheContract,    
                'competitionSubject'                    => $this->validation->competitionSubject,    
                'advantageOIAndUIS'                     => $this->validation->advantageOIAndUIS,    
                'placeOfDeliveryOfTheGoods'             => $this->validation->placeOfDeliveryOfTheGoods,    
                'termsOfDeliveriesOfTheGoods'           => $this->validation->termsOfDeliveriesOfTheGoods,    
                'termPlaceAndOrderAndOfficialSite'      => $this->validation->termPlaceAndOrderAndOfficialSite,    
                'placeOrderDates'                       => $this->validation->placeOrderDates,    
                'placeDateAndOpeningTime'               => $this->validation->placeDateAndOpeningTime,    
            ));
            
            redirect('admin/'.$this->moduleName.'/municipalFiles/index/concurs/'.$insertId);
        } else {
            $this->index();
        }
    }
}
?>