<?php
class Lmenu
{
    var $CI;
    var $table_name;
    var $left_column_name;
    var $right_column_name;
    var $primary_key_column_name;
    
    function Lmenu()
    {
        $this->CI = &get_instance();

        $this->table_name = $this->CI->nested_sets_model->table_name;
        $this->left_column_name = $this->CI->nested_sets_model->left_column_name;
        $this->right_column_name = $this->CI->nested_sets_model->right_column_name;
        $this->primary_key_column_name = $this->CI->nested_sets_model->primary_key_column_name;

    }
    
    
    function _getNodeById($id)
    {
        $targetFolder = $this->CI->nested_sets_model->getNodeFromId($id);
        
        return array(
            $this->left_column_name => $targetFolder[$this->left_column_name],    
            $this->right_column_name => $targetFolder[$this->right_column_name]    
        );
    }
    
    function _getExtraById($id)
    {
        return $this->CI->nested_sets_model->getNodeFromId($id);
    }
    
    
    // Получаем самую начальную папку т.е. корень структуры
    // Возвращает массив
    function getRoot()
    {
        return $this->CI->nested_sets_model->getRoot();
    }
    
    
    // Получаем все папки в из папки с id = $folderId
    // Возвращает массив
    function getFoldersToFolder($folderId = null)
    {
        $resultArray = array();
        
        if(!$folderId) 
        {
            $rootFolder = $this->getRoot();
            $folderId = $rootFolder[$this->primary_key_column_name];
        }
        
        $targetFolder = $this->_getNodeById($folderId);
        
        $lft_next = $targetFolder[$this->left_column_name];

        //Получаем всех предков у выбранного раздела
        while(TRUE)
        {
            $lft_next ++;
            
            $nextFolder = $this->CI->nested_sets_model->getNodeWhere($this->left_column_name.' = '.$lft_next);
            
            if(!$nextFolder[$this->right_column_name]) break;
            
            $lft_next = $nextFolder[$this->right_column_name];
            
            $resultArray[] = $nextFolder;
            
        }
        
        return $resultArray;
    }
    
    
    // Получаем все файлы в из папки с id = $folderId
    // Возвращает массив
    function getFilesToFolder($folderId = null)
    {
        if($folderId == null)
        {
            $rootFolder = $this->getRoot();
            $folderId = $rootFolder[$this->primary_key_column_name];
        }
    
        return   $this->CI->db
                      ->from('file')
                      ->where('folder_id', $folderId)
                      ->where('category_id', 0)
                      ->get()
                      ->result_array();
    
    }

    // Получаем все категории в из папки с id = $folderId
    // Возвращает массив
    function getCategoriesToFolder($folderId = null)
    {
        if($folderId == null) return FALSE;

        return   $this->CI->db
                      ->from('category')
                      ->where('folder_id', $folderId)
                      ->get()
                      ->result_array();

    }
    
    function getAncestorToFolder($folderId = null)
    {
        if($folderId == null)
        {
            return $this->getRoot();
        }
        
        return $this->CI->nested_sets_model->getAncestor($this->_getNodeById($folderId));
    }

    function getFilesToCategory($categoryId = null)
    {
        if($categoryId == null) return FALSE;

        return   $this->CI->db
                      ->from('file')
                      ->where('category_id', $categoryId)
                      ->get()
                      ->result_array();
    }
}
?>
