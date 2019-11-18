<?php
class AddEAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addEAuction';
    var $confValid = array();
    
    function AddEAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'EAuction', 'validation');
    }
    
    function index()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $Sections = $this->municipal_model->getSections();
        $sectionsTree = $this->municipal_model->parseSectionsAsTree($Sections);
        
        $data = array(
            'form_open'     => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/save'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Добавить'),
            
            // Номер
            'number'                        => form_input('number', $this->validation->number),
            
            //Раздел номенклатурного справочника
            'section'                       => $this->municipal_model->parseSectionsAsDropdown($sectionsTree),
            
            //Наименование
            'title'                         => form_input('title', $this->validation->title),
            
            // Дата начала работы
            'dateStartWork'                 => form_dropdown('dateStartWork[day]', $this->municipal_model->DAY).'&nbsp;'.
                                               form_dropdown('dateStartWork[month]', $this->municipal_model->MONTH).'&nbsp;'.
                                               form_input('dateStartWork[year]'),
            
            // Время старта и окончания в течении суток
            'timeStartOrEnd'                => 'с&nbsp;'.form_dropdown('timeStartOrEnd[sHour]', $this->municipal_model->HOUR, '09').'&nbsp;'.form_dropdown('timeStartOrEnd[sMinute]', $this->municipal_model->MINUTE).
                                               '&nbsp;&nbsp;по&nbsp;'.form_dropdown('timeStartOrEnd[eHour]', $this->municipal_model->HOUR, '18').'&nbsp;'.form_dropdown('timeStartOrEnd[eMinute]', $this->municipal_model->MINUTE),
            
            // Заказчик
            'customer'                      => form_textarea('customer', $this->validation->customer),
            
            // Уполномоченный орган
            'authorisedBody'                => form_textarea('authorisedBody', $this->validation->authorisedBody),
            
            // Источник финансирования заказа
            'fundingOrder'                  => form_input('fundingOrder', $this->validation->fundingOrder),
            
            // Предмет муниципального контракта
            'subjectMunicipalContract'      => form_textarea('subjectMunicipalContract', $this->validation->subjectMunicipalContract),
            
            // Место, условия и сроки поставок товара, выполнения работ, оказания услуг
            'locationWorksAndServices'      => form_textarea('locationWorksAndServices', $this->validation->locationWorksAndServices),
            
            // Форма, сроки и порядок оплаты товара, работ или услуг
            'procedureForPaymentOfGoods'    => form_textarea('procedureForPaymentOfGoods', $this->validation->procedureForPaymentOfGoods),
            
            // Начальная (максимальная) цена контракта
            'initialContractPrice'          => form_input('initialContractPrice', $this->validation->initialContractPrice),
            
            // Преимущества ОИ и УИС
            'advantageOIAndUIS'             => form_textarea('advantageOIAndUIS', $this->validation->advantageOIAndUIS),
            
            // Срок, место и порядок предоставления документации
            'termPlaceOrderDocumentation'   => form_textarea('termPlaceOrderDocumentation', $this->validation->termPlaceOrderDocumentation)
        );
        
        $this->display->_content('<h2>Добавление e-аукциона</h2>');
         
        $this->module->parse($this->moduleName, $this->controllerName.'/form.php', $data);    
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
            
            $insertId = $this->addEAuction_model->create(array(
                'number'                        => $this->validation->number,
                'section'                       => $sectionAsString,
                'title'                         => $this->validation->title,
                'dateStartWork'                 => mktime(0,0,0, $this->validation->dateStartWork['month'],$this->validation->dateStartWork['day'],$this->validation->dateStartWork['year']),
                'timeStartOrEnd'                => $this->validation->timeStartOrEnd['sHour'].':'.$this->validation->timeStartOrEnd['sMinute'].';'.$this->validation->timeStartOrEnd['eHour'].':'.$this->validation->timeStartOrEnd['eMinute'],
                'customer'                      => $this->validation->customer,
                'authorisedBody'                => $this->validation->authorisedBody,
                'fundingOrder'                  => $this->validation->fundingOrder,
                'subjectMunicipalContract'      => $this->validation->subjectMunicipalContract,
                'locationWorksAndServices'      => $this->validation->locationWorksAndServices,
                'procedureForPaymentOfGoods'    => $this->validation->procedureForPaymentOfGoods,
                'initialContractPrice'          => $this->validation->initialContractPrice,
                'advantageOIAndUIS'             => $this->validation->advantageOIAndUIS,
                'termPlaceOrderDocumentation'   => $this->validation->termPlaceOrderDocumentation
            ));
            
            redirect('admin/'.$this->moduleName.'/municipalFiles/index/eauction/'.$insertId);
                 
        } else {
            
            $this->index();
                
        }   
    } 
}

?>
