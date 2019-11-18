<?php
class Document_model extends Model
{
    function Document_model()
    {
        parent::Model();
        $this->TABLE = 'th_document';   
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return FALSE;
        
        return $this->db->from($this->TABLE)->where($where)->get()->result_array();    
    }

    function document_in_category($id_document)
    {
        
        
        
        $Document = $this->extra(array('file_id' => $id_document));
        
        if(count($Document) == 0) return;
        
        $data = array(
            'title'         => ucfirst($Document[0]['title']),
            'description'   => ucfirst($Document[0]['description']),
            'document'      => anchor('../uploads/document/'.$Document[0]['documentName'], 'Скачать'),
            'created'       => date('d.m.Y', strtotime($Document[0]['created'])),
            'breadCrumbs'	=> ''
           
        );
        
        return $this->module->parse('document', 'view.php', $data, TRUE);    
    }
}  
?>
