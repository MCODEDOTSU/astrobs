<?php
class News_model extends Model
{
    var $TABLE;

    function News_model()
    {
        parent::Model();
        $this->TABLE = 'th_news';
    }
    function do_upload($input)
    {
        $config = array(
            'upload_path'   => './news_imgs/',
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => '50000000',
            'max_width'     => '10240000',    
            'max_height'    => '76800000',    
            'encrypt_name'  => true    
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
    
    function image_resize($path = '')
    {
        if($path == '') return FALSE;
        
        $config['image_library'] = 'gd2'; // выбираем библиотеку
        $config['source_image'] = $path; 
        $config['create_thumb'] = TRUE; // ставим флаг создания эскиза
        $config['maintain_ratio'] = TRUE; // сохранять пропорции
        $config['width'] = 150; // и задаем размеры
        $config['height'] = 150;

        $this->load->library('image_lib', $config); // загружаем библиотеку 
//        $this->image_lib->initialize($config);
        $this->image_lib->resize(); // и вызываем функцию
        $this->image_lib->clear();
    
        return TRUE;
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

    function datetime($mas = array())
    {
        return mktime(0, 0, 0, $mas['month'], $mas['day'], $mas['year']);
    }
}
?>
