<?php
class AddLegalBody extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addLegalBody'; 
    var $confValid = array();
    
    function AddLegalBody()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'LegalBody', 'validation');  
    }
    
    function index()
    {
        $this->display->_content('<h2>Добавление юридического лица</h2>');
        
        
        $this->validation->set_fields($this->confValid['fields']);   
        
        $data = array(
            'fullName'                          => form_input('fullName', $this->validation->fullName),
            'shortName'                         => form_input('shortName', $this->validation->shortName),
            'formEntity'                        => form_input('formEntity', $this->validation->formEntity),
            'inn'                               => form_input('inn', $this->validation->inn),
            'kpp'                               => form_input('kpp', $this->validation->kpp),
            'producer'                          => form_checkbox('producer', $this->validation->producer),
            'stateRegistrationCertificate'      => form_input('stateRegistrationCertificate', $this->validation->stateRegistrationCertificate),
            'number'                            => form_input('number', $this->validation->number),
            'dateIssue'                         => form_input('dateIssue', $this->validation->dateIssue),
            'issued'                            => form_input('issued', $this->validation->issued),
            'region'                            => form_input('region', $this->validation->region),
            'city'                              => form_input('city', $this->validation->city),
            'legalAddress'                      => form_input('legalAddress', $this->validation->legalAddress),
            'mailingAddress'                    => form_input('mailingAddress', $this->validation->mailingAddress),
            'phone'                             => form_input('phone', $this->validation->phone),
            'fax'                               => form_input('fax', $this->validation->fax),
            'email'                             => form_input('email', $this->validation->email),
            'nameHead'                          => form_input('nameHead', $this->validation->nameHead),
            'postHead'                          => form_input('postHead', $this->validation->postHead),
            'nameAndPassportDetailsFounders'    => form_textarea('nameAndPassportDetailsFounders', $this->validation->nameAndPassportDetailsFounders),
            'typesActivitiesCharter'            => form_textarea('typesActivitiesCharter', $this->validation->typesActivitiesCharter),
            'shortInfoResourses'                => form_textarea('shortInfoResourses', $this->validation->shortInfoResourses),
            'siteUrl'                           => form_input('siteUrl', $this->validation->siteUrl),
            
            'form_open'                         => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/save'),
            'form_close'                        => form_close(),
            'submit'                            => form_submit('frmSubmit', 'Добавить')
        );
        
        
        $this->module->parse($this->moduleName, $this->controllerName.'/form.php', $data);
    }
    
    function save()
    {
        $this->validation->set_rules($this->confValid['rules']);   
        $this->validation->set_fields($this->confValid['fields']);                           
        
        if($this->validation->run() == TRUE){
            $extrafields = array(
                'fullName'                          => $this->validation->fullName,
                'shortName'                         => $this->validation->shortName,
                'formEntity'                        => $this->validation->formEntity,
                'inn'                               => $this->validation->inn,
                'kpp'                               => $this->validation->kpp,
                'producer'                          => $this->validation->producer,
                'stateRegistrationCertificate'      => $this->validation->stateRegistrationCertificate,
                'number'                            => $this->validation->number,
                'dateIssue'                         => $this->validation->dateIssue,
                'issued'                            => $this->validation->issued,
                'region'                            => $this->validation->region,
                'city'                              => $this->validation->city,
                'legalAddress'                      => $this->validation->legalAddress,
                'mailingAddress'                    => $this->validation->mailingAddress,
                'phone'                             => $this->validation->phone,
                'fax'                               => $this->validation->fax,
                'email'                             => $this->validation->email,
                'nameHead'                          => $this->validation->nameHead,
                'postHead'                          => $this->validation->postHead,
                'nameAndPassportDetailsFounders'    => $this->validation->nameAndPassportDetailsFounders,
                'typesActivitiesCharter'            => $this->validation->typesActivitiesCharter,
                'shortInfoResourses'                => $this->validation->shortInfoResourses,
                'siteUrl'                           => $this->validation->siteUrl
            );
            
            $this->addLegalBody_model->create($extrafields);
            
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
        } else {
            $this->index();    
        }    
    }
}
?>
