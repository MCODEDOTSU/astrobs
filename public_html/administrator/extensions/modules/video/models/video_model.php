<?php

class Video_model extends Model
{
    var $TABLE;

    function Video_model()
    {
        parent::Model();
        $this->TABLE = 'th_video';
    }

    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        if(!isset($extrafields['title'])) $extrafields['title'] = 'Без заголовка';
        
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

    function do_upload($input)
    {
        $config = array(
            'upload_path'   => './uploads/video/',
            'allowed_types' => 'flv',
            'max_size'      => '200000',
            'encrypt_name'  => true    
        );
        
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($input))
        {
            return $this->upload->data();
        }
        else
        {
            $this->display->_content($this->upload->display_errors());
        }
    }
    
    
}
?>
