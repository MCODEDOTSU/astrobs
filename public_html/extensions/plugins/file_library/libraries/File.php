<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File
{
    // Имя файла
    var $title;
    
    // Тип файла
    var $type;
    
    // CodeIgniter
    var $CI;
    
    var $TABLE;

    // Конструктор
    function File()
    {
        $this->CI = &get_instance();
        $this->TABLE = 'file';
    }
    
    // Удаление
    function deleteFile($id = null)
    {
        if(!is_numeric($id)) return FALSE;
        
        return $this->CI->db->delete($this->TABLE, array('id' => $id));
    }
    
    // Создание
    function createFile($extrafields = array())
    {
        if(strlen($extrafields['type']) < 1) return FALSE;
        if(strlen($extrafields['title']) < 1) $extrafields['title'] = 'Без наименования';
        
        $this->CI->db->insert($this->TABLE, $extrafields);

        $insertId =  $this->CI->db->insert_id();

        //=======================================================
        if($extrafields['folder_id'] > 0){
            $parentFolderNode = $this->CI->folder->_getNodeById($extrafields['folder_id']);
            $extraParentFolder = $this->CI->folder->getExtraFolder($parentFolderNode);
            $this->CI->folder->modifyExtraFolder(array(
                $this->CI->folder->sort_column_name => 'a'.$insertId.';'.$extraParentFolder[$this->CI->folder->sort_column_name]
            ), $parentFolderNode);
        } else if($extrafields['category_id'] > 0){
            $extraParentCategory = $this->CI->category->getExtraCategory($extrafields['category_id']);
            $this->CI->category->modifyExtraCategory(array(
                $this->CI->category->sort_column_name => 'a'.$insertId.';'.$extraParentCategory[0][$this->CI->category->sort_column_name]
            ), $extrafields['category_id']);
        }

        return $insertId;
    }

    function getExtraFile($fileId = null)
    {
        if(!is_numeric($fileId)) return false;

        return $this->CI->db
               ->from($this->TABLE)
               ->where(array('id' => $fileId))
               ->get()
               ->result_array();
    }

    function createFileInCategory($extrafields = array())
    {
        if(!is_numeric($extrafields['category_id'])) return FALSE;
        if(strlen($extrafields['type']) < 1) return FALSE;
        if(strlen($extrafields['title']) < 1) $extrafields['title'] = 'Без наименования';

        return $this->CI->db->insert($this->TABLE, $extrafields);
    }
    
    // Переименование файла
    function renameFile($title, $id)
    {
        if(!is_numeric($id)) return FALSE;
        
        if( (strlen($title) == ' ') && (strlen($title) < 1) ) return FALSE;
        
        return $this->CI->db->update($this->TABLE, array('title' => $title), array('id'=>$id));
    }
    
    // Получаем все файлы в из папки с id = $folderId
    // Возвращает массив
    function getFilesToFolder($folderId = null)
    {
        if(!is_numeric($folderId)) return FALSE;
    
        return   $this->CI->db
                      ->from($this->TABLE)
                      ->where('folder_id', $folderId)
                      ->get()
                      ->result_array();
    
    }

    function getFilesToCategory($categoryId = null)
    {
        if(!is_numeric($categoryId)) return FALSE;
        
        return   $this->CI->db
                      ->from($this->TABLE)
                      ->where('category_id', $categoryId)
                      ->get()
                      ->result_array();
    }

    function modifyExtraFile($extrafields = array(), $fileId = null)
    {
        if(count($extrafields) == 0) return FALSE;
        if(!is_numeric($fileId)) return FALSE;

        return $this->CI->db->update($this->TABLE, $extrafields, array('id'=> $fileId));
    }

} 
?>
