<?php
class Random_photo_model extends Model
{
    function Random_photo_model()
    {
        parent::Model();
        $this->TABLE = 'th_photo';
        
    }
    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLE)->result_array();
    }

    function get($limit = 1)
    {
        
        return $this->db->order_by('id', 'random')->limit($limit)->get($this->TABLE)->result_array();
    }

    function block()
    {
        return '

            <div id="random_photo_container" class="block">
                <h4><span>Случайное фото</span></h4>
                '.$this->random_photo_model->getBlock().'
            </div>

        ';
    }

    function getBlock()
    {
        $html = '';
        
        $arrRandom = $this->get(1);

        foreach($arrRandom as $random)
        {            
            if(strlen($random['file_name']) == 0) continue;
            $query = mysql_query ("SELECT * FROM `th_file` WHERE `id` = '" . $random['file_id'] . "'");
            $r     = mysql_fetch_array ($query);
            $fid   = $r['category_id'];
            $html .= $this->module->parse('random_photo', 'block', array(
                'file_name' => str_replace ('.', '_thumb.', $random['file_name']),
                'button'    => $fid
                
            ), true);
        }

        return $html;
    }
}
?>
