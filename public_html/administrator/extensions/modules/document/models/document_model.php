<?php
class Document_model extends Model
{
    var $TABLE;

    function Document_model()
    {
        parent::Model();
        $this->TABLE = 'th_document';
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
            'upload_path'   => './uploads/document/',     
            'allowed_types' => 'xls|zip|doc|docx|word|pdf',
            'max_size' => 2048000,
            'encrypt_name' => TRUE,
            'remove_spaces' => TRUE    
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
