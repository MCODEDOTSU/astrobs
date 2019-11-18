<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category
{
    // Имя
    var $title;
    
    // CodeIgniter
    var $CI;

    // Таблицы БД
    var $_TABLE = array();
    
    

    // Конструктор
    function Category()
    {
        $this->_TABLE['Category'] = 'th_category';
        $this->CI = &get_instance();
        
    }

    

    function get($where = array())
    {
        if(count($where) == 0) return false;

        return $this
               ->CI
               ->db
               ->from($this->_TABLE['Category'])
               ->where($where)
               ->get()
               ->result_array();
    }
    
    
   
    
    // Получаем все категории в из папки с id = $folderId
    // Возвращает массив
    function getCategoriesToFolder($folderId = null)
    {
        if($folderId == null) return FALSE;
    
        return   $this->CI->db
                      ->from($this->_TABLE['Category'])
                      ->where('folder_id', $folderId)
                      ->get()
                      ->result_array();
    
    }

} 
?>
