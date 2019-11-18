<?php
class AddPhysicalBody extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'addPhysicalBody';
    var $confValid = array();
    
    function AddPhysicalBody()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->confValid = $this->module->config($this->moduleName, 'PhysicalBody', 'validation');
    }
    
    function index()
    {
        $this->validation->set_fields($this->confValid['fields']);
        
        $data = array(
            'surname'       => form_input('surname', $this->validation->surname),
            'name'          => form_input('name', $this->validation->name),
            'patronymic'    => form_input('patronymic', $this->validation->patronymic),
            'dateOfBirth'   => form_input('dateOfBirth', $this->validation->dateOfBirth),
            'inn'           => form_input('inn', $this->validation->inn),
            'documentName'  => form_input('documentName', $this->validation->documentName),
            'series'        => form_input('series', $this->validation->series),
            'number'        => form_input('number', $this->validation->number),
            'dateIssue'     => form_input('dateIssue', $this->validation->dateIssue),
            'issued'        => form_input('issued', $this->validation->issued),
            'region'        => form_input('region', $this->validation->region),
            'city'          => form_input('city', $this->validation->city),
            'address'       => form_input('address', $this->validation->address),
            'phone'         => form_input('phone', $this->validation->phone),
            'fax'           => form_input('fax', $this->validation->fax),
            'email'         => form_input('email', $this->validation->email),
            'activities'    => form_textarea('activities', $this->validation->activities),
            'siteUrl'       => form_input('siteUrl', $this->validation->siteUrl),
            
            'form_open'     => form_open('admin/'.$this->moduleName.'/'.$this->controllerName.'/save'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Добавить')
        );
        
        $this->display->_content('<h2>Добавление физического лица</h2>');
        $this->module->parse($this->moduleName, $this->controllerName.'/form.php', $data);
    }
    
    function save()
    {
        $this->validation->set_rules($this->confValid['rules']);   
        $this->validation->set_fields($this->confValid['fields']);                           
        
        if($this->validation->run() == TRUE){
            $extrafields = array(
                'surname'       => $this->validation->surname,
                'name'          => $this->validation->name,
                'patronymic'    => $this->validation->patronymic,
                'dateOfBirth'   => $this->validation->dateOfBirth,
                'inn'           => $this->validation->inn,
                'documentName'  => $this->validation->documentName,
                'series'        => $this->validation->series,
                'number'        => $this->validation->number,
                'dateIssue'     => $this->validation->dateIssue,
                'issued'        => $this->validation->issued,
                'region'        => $this->validation->region,
                'city'          => $this->validation->city,
                'address'       => $this->validation->address,
                'phone'         => $this->validation->phone,
                'fax'           => $this->validation->fax,
                'email'         => $this->validation->email,
                'activities'    => $this->validation->activities,
                'siteUrl'       => $this->validation->siteUrl
            );
            
            $this->addPhysicalBody_model->create($extrafields);
            
            redirect('admin/'.$this->moduleName.'/'.$this->controllerName);
        } else {
            $this->index();    
        }    
    }
}
?>
