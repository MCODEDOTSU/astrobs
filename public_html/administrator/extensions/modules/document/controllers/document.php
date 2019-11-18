<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
        
class Document extends Admin_Controller
{
    var $moduleName = 'document';
    var $controllerName = 'document';
    var $moduleTitles = '';
    var $confValid = array();
    
    function Document()
    {
        parent::Admin_Controller();
        $this->moduleTitles = $this->module->config($this->moduleName, 'title');
        $this->confValid = $this->module->config($this->moduleName, 'validation');    
    }
    
    function file()
    {
        $this->display->_content('<div class="cms_title"><img src="' . base_url() . 'cms_icons/page_white.png" align="absmiddle">'.$this->moduleTitles.'</div>');
        
        $id_file = $this->uri->segment(5);
        
        if(!is_numeric($id_file)) {
            return FALSE;
        }

        $extraDocument = $this->document_model->extra(array('file_id' => $id_file)); 
        
        if(count($extraDocument) == 0){
            return FALSE;
        }
        
        foreach($extraDocument as $document){
            $data = array(
                'title'         => form_input('title',$document['title'], 'style="width: 300px;"'),
                'description'   => form_ckeditor('description',$document['description']),
                'document'      => form_upload('document'),
                'form_open'     => form_open_multipart('admin/document/document/save/'.$id_file),
                'form_close'    => form_close(),
                'form_submit'   => form_submit('frmSubmit', 'Сохранить')
            );    
        }
        
        $this->module->parse($this->moduleName, 'view.php', $data);
    }
    
    function save()
    {
        $id_file = $this->uri->segment(5);
        
        if(!is_numeric($id_file)) {
            return FALSE;
        }
        
        $title          = $this->input->post('title');
        $description    = $this->input->post('description');
        $document       = $this->input->post('document');

        $upload_data    = $this->document_model->do_upload('document');    
       
        if(is_string($upload_data)){
            $this->display->_content($upload_data);
            return FALSE;             
        }
        
        $Document = $this->document_model->extra(array('file_id' => $id_file));

        $this->document_model->update(array(
            'title'         => $title, 
            'description'   => $description, 
            'file_id'       => $id_file,
            'documentName'  => $upload_data['file_name'],
            'created'       => date('Y-m-d h:m:s')
        ), array(
            'id'            => $Document[0]['id']
        ));
        
        redirect('admin/place/place');
    }
}
?>
