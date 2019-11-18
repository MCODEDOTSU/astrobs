<?php
class PhysicalBody_model extends Model
{
    var $moduleName = 'municipal';
    var $tables = array();
    
    function PhysicalBody_model()
    {
        parent::Model();
        $this->tables = $this->module->config($this->moduleName, 'TABLES');
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        $this->db->insert($this->tables['PhysicalBody'], $extrafields);
        return $this->db->insert_id();
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->tables['PhysicalBody'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->tables['PhysicalBody'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->tables['PhysicalBody'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->tables['PhysicalBody'])->result_array();
    }
}
?>
