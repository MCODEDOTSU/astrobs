<?php

class Photo_model extends Model
{
    var $TABLE;

    function Photo_model()
    {
        parent::Model();
        $this->TABLE = 'th_photo';
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
            'upload_path'   => './uploads/photo/',
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => '5000000000',
            'max_width'     => '1024000000',    
            'max_height'    => '7680000000',    
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
    
    //уменьшаем картинку для структуры
    function image_resize_mini($path = '', $filename)
    {
    
    
        $config['image_library'] = 'gd2'; // выбираем библиотеку
        $config['source_image'] = $path;
        $config['new_image'] = 'uploads/photo/mini/mini_'.$filename;
        $config['create_thumb'] = FALSE; // ставим флаг создания эскиза
        $config['maintain_ratio'] = TRUE; // сохранять пропорции
        $config['width'] = 20; // и задаем размеры
        $config['height'] = 20;

        $this->load->library('image_lib', $config); // загружаем библиотеку 
        $this->image_lib->initialize($config);
        $this->image_lib->resize(); // и вызываем функцию
        $this->image_lib->clear();
        return TRUE;
    }
    
    function image_resize_stand ($path = '', $filename) {
    	$config['image_library'] = 'gd2'; // выбираем библиотеку
        $config['source_image'] = $path;
        $config['new_image'] = 'uploads/photo/'.$filename;
        $config['create_thumb'] = FALSE; // ставим флаг создания эскиза
        $config['maintain_ratio'] = TRUE; // сохранять пропорции
        $config['width'] = 800; // и задаем размеры
        $config['height'] = 600;

        $this->load->library('image_lib', $config); // загружаем библиотеку 
        $this->image_lib->initialize($config);
        $this->image_lib->resize(); // и вызываем функцию
        $this->image_lib->clear();
        return TRUE;
    }
}
?>
