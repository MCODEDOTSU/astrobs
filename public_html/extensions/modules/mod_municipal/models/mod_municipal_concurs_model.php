<?php
class Mod_municipal_concurs_model extends Model
{
    var $moduleName = 'mod_municipal';
    var $tables = array();
    
    function Mod_municipal_concurs_model()
    {
        parent::Model();
        $this->tables = $this->module->config($this->moduleName, 'tables');
    }
    
    function get()
    {
        return $this->db->get($this->tables['Concurs'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->tables['Concurs'])->result_array();
    } 
}
?>
