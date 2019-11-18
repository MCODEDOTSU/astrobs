<?php
class Content_model extends Model
{
    var $moduleName = 'mod_content';
    var $tables = array();
    
    function Content_model()
    {
        parent::Model();
        
    }
    
    function get()
    {
        return $this->db->get('file')->result_array();
    }
}
?>
