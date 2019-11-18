<?php
class Library_model extends Model
{
    var $TABLE;
    
    function Library_model()
    {
        parent::Model();
        $this->TABLE = 'th_library';
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

    function do_upload($input)
    {
        $config = array(
            'upload_path'   => './uploads/library/',
            'allowed_types' => 'rar|zip|doc|docx|odt|pdf|djvu',
            'max_size' => 0,
            'remove_spaces' => TRUE,
            'encrypt_name' => TRUE

        );

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($input))
        {
            return $this->upload->data();
        }
        else
        {
            return $this->upload->display_errors();
        }
    }
}
?>
