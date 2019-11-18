<?php
class Map_model extends Model
{
    var $TABLE;

    function Map_model()
    {
        parent::Model();
        $this->TABLE = 'th_map';
    }

    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        if(!isset($extrafields['title'])) $extrafields['title'] = 'Без заголовка';
        if(!isset($extrafields['point'])) $extrafields['point'] = '0,0';

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

    function mapsYandex()
    {
        return '<div id="YMapsID" style="width:600px;height:400px"></div>';
    }
}
?>
