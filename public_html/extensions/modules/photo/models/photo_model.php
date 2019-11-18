<?php
class Photo_model extends Model
{
    var $moduleName = 'photo';
    var $controllerName = 'photo';
    
    function Photo_model()
    {
        parent::Model();
        $this->TABLE = 'th_photo';   
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return FALSE;
        
        return $this->db->from($this->TABLE)->where($where)->get()->result_array();    
    }

    function photo_in_category($photoId = null)
    {
        if(!is_numeric($photoId)) return FALSE; 
        
        $Photo = $this->extra(array('file_id' => $photoId));
        
        if(count($Photo) == 0) {
            return FALSE;    
        }
        
        $img = explode('.',$Photo[0]['file_name']);
        $img[0] = $img[0].'_thumb';
        
        
        
        $img = implode($img, '.');
        
        $data = array(
            'title' => ucfirst($Photo[0]['title']),
            'desc'  => ucfirst($Photo[0]['desc']),
            'image' => '<a href="/uploads/photo/'.$Photo[0]['file_name'].'"><img src="/uploads/photo/'.$img.'" title="'.ucfirst($Photo[0]['desc']).'" /></a>',
            'created' => date('d.m.Y', strtotime($Photo[0]['created'])),
            'breadCrumbs'	=> ''
        );
        
        return $this->module->parse($this->moduleName, 'view.php', $data, TRUE);
    }
}  
?>
