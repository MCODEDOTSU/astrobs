<?php
class BudgetPeoples extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'budgetPeoples';
    var $confValid = array();
    
    function BudgetPeoples()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'BudgetPeoples', 'validation');
    }
    
    function index()
    {
        $this->display->_content('<h2>Бюджетополучатели</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/create_form', _icon('add').'Добавить бюджетополучателя','title="Добавить бюджетополучателя" class="cms_btn"').'</div>');
        
        $BudgetPeoples = $this->budgetPeoples_model->get();
        
        if(count($BudgetPeoples) == 0) {
            return FALSE;
        }
        
        foreach($BudgetPeoples as $budgetPeoples){
            $data['budgetPeoples'][] = array(
                'title'     => $budgetPeoples['title'],
                'actions'   => anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/update_form/'.$budgetPeoples['id'], _icon('pencil'), 'title="Редактировать"').
                               anchor('admin/'.$this->moduleName.'/'.$this->controllerName.'/delete/'.$budgetPeoples['id'], _icon('delete'), 'title="Удалить"')
            );
        }
        
        $this->module->parse($this->moduleName, 'budgetPeoples/index.php', $data);
            
    }
    
    function create_form()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $data = array(
            'title'                 => form_input('title', $this->validation->title),
            'shortTitle'            => form_input('shortTitle', $this->validation->shortTitle), 
            'inn'                   => form_input('inn', $this->validation->inn), 
            'kpp'                   => form_input('kpp', $this->validation->kpp), 
            'bik'                   => form_input('bik', $this->validation->bik), 
            'settlementAccount'     => form_input('settlementAccount', $this->validation->settlementAccount), 
            'correspondentAccount'  => form_input('correspondentAccount', $this->validation->correspondentAccount), 
            'bank'                  => form_input('bank', $this->validation->bank), 
            'okonk'                 => form_input('okonk', $this->validation->okonk), 
            'okpo'                  => form_input('okpo', $this->validation->okpo), 
            'phone'                 => form_input('phone', $this->validation->phone), 
            'fax'                   => form_input('fax', $this->validation->fax), 
            'nameHead'              => form_input('nameHead', $this->validation->nameHead), 
            'email'                 => form_input('email', $this->validation->email), 
            'legalAddress'          => form_input('legalAddress', $this->validation->legalAddress), 
            'mailingAddress'        => form_input('mailingAddress', $this->validation->mailingAddress),
            
            'form_open'             => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/create'),
            'form_close'            => form_close(),
            'submit'                => form_submit('frmSubmit', 'Добавить', 'title="Добавить"')
        );
        
        $this->display->_content('<h2>Добавление бюджетополучателя</h2>');
        $this->module->parse($this->moduleName, 'budgetPeoples/form.php', $data);    
    }
    
    function create()
    {
        $this->validation->set_rules($this->confValid['rules']);    
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            $this->budgetPeoples_model->create(array(
                'title'                 => $this->validation->title,
                'shortTitle'            => $this->validation->shortTitle, 
                'inn'                   => $this->validation->inn, 
                'kpp'                   => $this->validation->kpp, 
                'bik'                   => $this->validation->bik, 
                'settlementAccount'     => $this->validation->settlementAccount, 
                'correspondentAccount'  => $this->validation->correspondentAccount, 
                'bank'                  => $this->validation->bank, 
                'okonk'                 => $this->validation->okonk, 
                'okpo'                  => $this->validation->okpo, 
                'phone'                 => $this->validation->phone, 
                'fax'                   => $this->validation->fax, 
                'nameHead'              => $this->validation->nameHead, 
                'email'                 => $this->validation->email, 
                'legalAddress'          => $this->validation->legalAddress, 
                'mailingAddress'        => $this->validation->mailingAddress    
            ));
            
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
        } else {
            $this->create_form();
        }    
    }
    
    function update_form()
    {
        $id_budgetPeoples = $this->uri->segment(5);
        
        if(!is_numeric($id_budgetPeoples)){
            return FALSE;
        }
        
        $extraBudgetPeoples = $this->budgetPeoples_model->extra(array('id' => $id_budgetPeoples));
        
        if(count($extraBudgetPeoples) == 0) return FALSE;
        
        foreach($extraBudgetPeoples as $body){
            $data = array(
                'title'                 => form_input('title', $body['title']),
                'shortTitle'            => form_input('shortTitle', $body['shortTitle']), 
                'inn'                   => form_input('inn', $body['inn']), 
                'kpp'                   => form_input('kpp', $body['kpp']), 
                'bik'                   => form_input('bik', $body['bik']), 
                'settlementAccount'     => form_input('settlementAccount', $body['settlementAccount']), 
                'correspondentAccount'  => form_input('correspondentAccount', $body['correspondentAccount']), 
                'bank'                  => form_input('bank', $body['bank']), 
                'okonk'                 => form_input('okonk', $body['okonk']), 
                'okpo'                  => form_input('okpo', $body['okpo']), 
                'phone'                 => form_input('phone', $body['phone']), 
                'fax'                   => form_input('fax', $body['fax']), 
                'nameHead'              => form_input('nameHead', $body['nameHead']), 
                'email'                 => form_input('email', $body['email']), 
                'legalAddress'          => form_input('legalAddress', $body['legalAddress']), 
                'mailingAddress'        => form_input('mailingAddress', $body['mailingAddress']),
                
                'form_open'             => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/update/'.$id_budgetPeoples),
                'form_close'            => form_close(),
                'submit'                => form_submit('frmSubmit', 'Сохранить')    
            );
        }
        
        $this->display->_content('<h2>Редактирование информации бюджетополучателя</h2>');
        $this->module->parse($this->moduleName, 'budgetPeoples/form.php', $data);
    }
    
    function update()
    {
        $id_budgetPeoples = $this->uri->segment(5);
        
        if(!is_numeric($id_budgetPeoples)){
            return FALSE;
        }
        
        $this->validation->set_rules($this->confValid['rules']);    
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            $this->budgetPeoples_model->update(array(
                'title'                 => $this->validation->title,
                'shortTitle'            => $this->validation->shortTitle, 
                'inn'                   => $this->validation->inn, 
                'kpp'                   => $this->validation->kpp, 
                'bik'                   => $this->validation->bik, 
                'settlementAccount'     => $this->validation->settlementAccount, 
                'correspondentAccount'  => $this->validation->correspondentAccount, 
                'bank'                  => $this->validation->bank, 
                'okonk'                 => $this->validation->okonk, 
                'okpo'                  => $this->validation->okpo, 
                'phone'                 => $this->validation->phone, 
                'fax'                   => $this->validation->fax, 
                'nameHead'              => $this->validation->nameHead, 
                'email'                 => $this->validation->email, 
                'legalAddress'          => $this->validation->legalAddress, 
                'mailingAddress'        => $this->validation->mailingAddress    
            ), array('id' => $id_budgetPeoples));
            
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
        } else {
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName.'/update_form/'.$id_budgetPeoples);
        }    
    }
    
    function delete()
    {
        $id_budgetPeoples = $this->uri->segment(5);
        
        if(!is_numeric($id_budgetPeoples)){
            return FALSE;
        }
        
        $this->budgetPeoples_model->delete(array('id' => $id_budgetPeoples));
        
        redirect('admin/'.$this->moduleName.'/'.$this->controllerName);    
    }
}
?>