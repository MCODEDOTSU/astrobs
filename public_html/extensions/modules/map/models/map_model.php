<?php
class Map_model extends Model
{
    var $TABLE;

    function Map_model()
    {
        parent::Model();
        $this->TABLE = 'th_map';
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

    function mapsContainer()
    {
        return '<div id="YMapsID" style="width:100%;height:400px;"></div>';
    }

    function map_in_category($fileId)
    {
        $Map = $this->map_model->extra(array('file_id' => $fileId));

        foreach($Map as $_map)
        {
            $data = array(
                'title' => $_map['title'],
                'desc'  => $_map['desc'],
                'map'   => $this->mapsContainer(),
                'point' => $_map['point']
            );
        }

        return $this->module->parse('map', 'view.php', $data, TRUE);
    }
}
?>
