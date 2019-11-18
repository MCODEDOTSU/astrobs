<?php
class Anchor extends Public_Controller
{
    function Anchor()
    {
        parent::Public_Controller();
    }
    
    function view()
    {
        $fileId = $this->uri->segment(4);
        if(!is_numeric($fileId)) return FALSE;
        $Anchor = $this->anchor_model->extra(array('file_id' => $fileId));
        if(count($Anchor) == 0) return FALSE;
        $Anchor = $Anchor[0];    
        if(count($Anchor) == 0) return FALSE;
        if(strlen($Anchor['url']) == 0) return FALSE;
        redirect($Anchor['url']);    
    }
}
?>
