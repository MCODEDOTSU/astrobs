<?php
class LegalBody_model extends Model
{
    var $moduleName = 'municipal';
    var $tables = array();
    
    function LegalBody_model()
    {
        parent::Model();
        $this->tables = $this->module->config($this->moduleName, 'TABLES');
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        $this->db->insert($this->tables['LegalBody'], $extrafields);
        return $this->db->insert_id();
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->tables['LegalBody'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->tables['LegalBody'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->tables['LegalBody'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->tables['LegalBody'])->result_array();
    }
}
?>
