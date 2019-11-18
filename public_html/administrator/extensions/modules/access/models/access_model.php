<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Access_model extends Model
{
    var $TABLE;

    function Access_model()
    {
        parent::Model();
        $this->TABLE = 'th_access';
    }

    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        return $this->db->insert($this->TABLE, $extrafields);
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->TABLE, $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->TABLE, $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->TABLE)->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLE)->result_array();
    }

    function modules()
    {
        $resultArray = array();

        $is_loaded_modules = $this->config->is_loaded_modules;

        if(!is_array($is_loaded_modules)) return $resultArray;

        foreach($is_loaded_modules as $module)
        {
            if(isset($this->config->config_modules[$module][$module]['access']))
            {
                $resultArray[$module] = $this->config->config_modules[$module][$module]['title'];
            }
        }

        return $resultArray;
    }

    function groups()
    {
        return $this->db->get('th_augroup')->result_array();
    }
}
?>
