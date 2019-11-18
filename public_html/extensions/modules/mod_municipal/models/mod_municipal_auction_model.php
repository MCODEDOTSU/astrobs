<?php
class Mod_municipal_auction_model extends Model
{
    var $moduleName = 'mod_municipal';
    var $tables = array();
    
    function Mod_municipal_auction_model()
    {
        parent::Model();
        $this->tables = $this->module->config($this->moduleName, 'tables');
    }
    
    function get()
    {
        return $this->db->get($this->tables['Auction'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->tables['Auction'])->result_array();
    } 
}
?>
