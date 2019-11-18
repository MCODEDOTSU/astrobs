<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File
{
    // Имя файла
    var $title;
    
    // Тип файла
    var $type;
    
    // CodeIgniter
    var $CI;
    
    var $_TABLE;

    
    // Конструктор
    function File()
    {
        $this->_TABLE['File'] = 'th_file';
        $this->CI = &get_instance();
        
    }
    
    
    
    

    function get($where = array())
    {
        if(count($where) == 0) return false;

        return $this
               ->CI
               ->db
               ->from($this->_TABLE['File'])
               ->where($where)
               ->get()
               ->result_array();
    }

    
    
    
    
    // Получаем все файлы в из папки с id = $folderId
    // Возвращает массив
    function getFilesToFolder($folderId = null)
    {
        if($folderId == null) return FALSE;
    
        return   $this->CI->db
                      ->from($this->_TABLE['File'])
                      ->where('folder_id', $folderId)
                      ->get()
                      ->result_array();
    
    }

    function getFilesToCategory($categoryId = null)
    {
        if($categoryId == null) return FALSE;
        
        return   $this->CI->db
                      ->from($this->_TABLE['File'])
                      ->where('category_id', $categoryId)
                      ->get()
                      ->result_array();
    }

} 
?>
