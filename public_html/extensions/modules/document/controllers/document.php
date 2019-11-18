<?php
class Document extends Public_Controller
{
    function Document()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $id_document = $this->uri->segment(5);
        
        $Document = $this->document_model->extra(array('id' => $id_document));
        
        if(count($Document) == 0) return;
        
        $data = array(
            'title'         => ucfirst($Document[0]['title']),
            'description'   => ucfirst($Document[0]['description']),
            'document'      => anchor('../uploads/document/'.$Document[0]['documentName'], 'Скачать'),
            'created'       => date('d.m.Y', strtotime($Document[0]['created'])),
           
        );
        
        $this->module->parse('document', 'view.php', $data);    
    }
    
    function view()
    {
        $id_document = $this->uri->segment(4);
        
        $Document = $this->document_model->extra(array('file_id' => $id_document));
        
        
        
        if(count($Document) == 0) return;
        
        $data = array(
            'title'         => ucfirst($Document[0]['title']),
            'description'   => ucfirst($Document[0]['description']),
            'document'      => anchor('../uploads/document/'.$Document[0]['documentName'], 'Скачать'),
            'created'       => date('d.m.Y', strtotime($Document[0]['created'])),
            'breadCrumbs'	=> $this->breadcrumbs->get ($id_document),
            //'catTours'		=> $this->central_menu_model->get ()
            
           
        );
        
        $this->module->parse('document', 'view.php', $data);    
            
    }
}  
?>
