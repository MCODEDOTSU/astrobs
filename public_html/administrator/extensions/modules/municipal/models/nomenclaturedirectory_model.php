<?php
class NomenclatureDirectory_model extends Model
{
    var $TABLES;
    
    function NomenclatureDirectory_model()
    {
        parent::Model();
        $this->TABLES = $this->module->config('municipal', 'TABLES');
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        if(!isset($extrafields['title'])) $extrafields['title'] = 'Без заголовка';

        return $this->db->insert($this->TABLES['Sections'], $extrafields);
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->TABLES['Sections'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->TABLES['Sections'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->TABLES['Sections'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLES['Sections'])->result_array();
    }
}
?>
