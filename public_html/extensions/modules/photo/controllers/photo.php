<?php
class Photo extends Public_Controller
{
    var $moduleName = 'photo';
    
    function Photo()
    {
    	
        parent::Public_Controller();
    }
    
    function index()
    {
        //die;
        $photoId = $this->uri->segment(5);  if(!is_numeric($photoId)) return FALSE;
        
        $Photo = $this->photo_model->extra(array('id' => $photoId));
        
        if(count($Photo) == 0) {
            return FALSE;
        }
        
        $data = array(
            /*'title' => ucfirst($Photo[0]['title']),
            'body'  => ucfirst($Photo[0]['body']),
            'created' => date('l dS \of\ F Y h:I:s A', time($Article[0]['created'])),
            'modifed' => $Article[0]['modifed']  */
        );
        
        $this->module->parse($this->moduleName, 'view.php', $data);    
    }
    
    function view()
    {
        $photoId = $this->uri->segment(4); if(!is_numeric($photoId)) return FALSE; 
        
        $Photo = $this->photo_model->extra(array('file_id' => $photoId));
        
        if(count($Photo) == 0) {
            return FALSE;
        }
        
        $data = array(
            'title' => ucfirst($Photo[0]['title']),
            'desc'  => ucfirst($Photo[0]['desc']),
            'image' => '<a href="/uploads/photo/'.$Photo[0]['file_name'].'"><img src="/uploads/photo/'.$Photo[0]['raw_name'].'_thumb'.$Photo[0]['file_ext'].'" '/*.$Photo[0]['image_size_str']*/.'/></a>', 
            'created' => date('d.m.Y', strtotime($Photo[0]['created'])),
            'breadCrumbs'	=> $this->breadcrumbs->get ($photoId),
			//'catTours'		=> $this->central_menu_model->get ()
        );
        
        $this->module->parse($this->moduleName, 'view.php', $data);
            
    }
}  
?>
