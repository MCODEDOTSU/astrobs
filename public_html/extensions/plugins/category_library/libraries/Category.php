<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category
{
    // Имя
    var $title;
    
    // CodeIgniter
    var $CI;

    // Таблицы БД
    var $TABLE;
    
    var $sort_column_name;

    // Конструктор
    function Category()
    {
        $this->CI = &get_instance();
        $this->TABLE = 'category';
        $this->sort_column_name = 'sort';
    }

    // Создание
    function createCategory($extrafields = array(), $folderId = NULL)
    {
        if(!is_numeric($folderId)) return FALSE;
        
        if(strlen($extrafields['title']) < 1) $extrafields['title'] = 'Без наименования';
        
        if(strlen($extrafields['desc']) < 1) $extrafields['desc'] = 'Без краткого описания';

        $extrafields['folder_id'] = $folderId;

        $this->CI->db->insert($this->TABLE, $extrafields);

        $insertId = $this->CI->db->insert_id();

        //=======================================================
        $parentFolderNode = $this->CI->folder->_getNodeById($folderId);
        
        $extraParentFolder = $this->CI->folder->getExtraFolder($parentFolderNode);

        $this->CI->folder->modifyExtraFolder(array(
            $this->CI->folder->sort_column_name => 'c'.$insertId.';'.$extraParentFolder[$this->CI->folder->sort_column_name]
        ), $parentFolderNode);

        return $insertId;
    }
    
    // Удаление
    function deleteCategory($id = null)
    {
        if(!is_numeric($id)) return FALSE;
        
        return $this->CI->db->delete($this->TABLE, array('id' => $id) );
    }

    function getExtraCategory($categoryId = null)
    {
        if(!is_numeric($categoryId)) return false;

        return $this->CI->db
               ->from($this->TABLE)
               ->where(array('id' => $categoryId))
               ->get()
               ->result_array();
    }
    
    
    // Переименование файла
    function renameCategory($title, $id)
    {
        if(!is_numeric($id)) return FALSE;
        
        if( (strlen($title) == ' ') && (strlen($title) < 1) ) return FALSE;
        
        return $this->CI->db->update($this->TABLE, array('title' => $title), array('id'=>$id));
    }
    
    // Получаем все категории в из папки с id = $folderId
    // Возвращает массив
    function getCategoriesToFolder($folderId = null)
    {
        if($folderId == null) return FALSE;
    
        return   $this->CI->db
                      ->from($this->TABLE)
                      ->where('folder_id', $folderId)
                      ->get()
                      ->result_array();
    
    }

    function modifyExtraCategory($extrafields, $id)
    {
        if(!is_numeric($id)) return FALSE;

        if(count($extrafields) == 0) return FALSE;

        return $this->CI->db->update($this->TABLE, $extrafields, array('id' => $id));
    }

} 
?>
