<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Folder
{
    // Имя папки
    var $title;
    
    // CodeIgniter
    var $CI;
    
    var $table_name;
    var $left_column_name;
    var $right_column_name;
    var $primary_key_column_name;
  
    // Конструктор
    function Folder()
    {
        $this->CI = &get_instance();
    }
    
    function setControlParams()
    {
        $this->table_name = $this->CI->nested_sets_model->table_name;
        $this->left_column_name = $this->CI->nested_sets_model->left_column_name;
        $this->right_column_name = $this->CI->nested_sets_model->right_column_name;
        $this->primary_key_column_name = $this->CI->nested_sets_model->primary_key_column_name;  
        
        return "";
    }
  
    
//    function get($node = array())
//    {
//        if(count($node) == 0) return FALSE;
//
//        $folderId = $this
//                    ->CI
//                    ->db
//                    ->select($this->primary_key_column_name)
//                    ->from($this->table_name)
//                    ->where(array(
//                        $this->left_column_name => $node[$this->left_column_name],
//                        $this->right_column_name => $node[$this->right_column_name]
//                      ))
//                    ->get()
//                    ->result_array();
//
//        return $this->_getExtraById($folderId[0][$this->primary_key_column_name]);
//    }
    
       
    function _getNodeById($id)
    {
        if(!is_numeric($id)) return FALSE;

        $targetFolder = $this->CI->nested_sets_model->getNodeFromId($id);
        
        return array(
            $this->left_column_name => $targetFolder[$this->left_column_name],    
            $this->right_column_name => $targetFolder[$this->right_column_name]    
        );
    }
    
//    function _getExtraById($id)
//    {
//        if(!is_numeric($id)) return FALSE;
//
//        return $this->CI->nested_sets_model->getNodeFromId($id);
//    }
    
    
    
    function getRoot()
    {
        return $this->CI->nested_sets_model->getRoot();
    }
    
//    function getLevel($id = null)
//    {
//        $resultArray = array();
//
//        if(!$id)
//        {
//            $rootFolder = $this->getRoot();
//            $id = $rootFolder[$this->primary_key_column_name];
//        }
//
//        $targetFolder = $this->_getNodeById($id);
//
//        $lft_next = $targetFolder[$this->left_column_name];
//
//        //Получаем всех предков у выбранного раздела
//        while(TRUE)
//        {
//            $lft_next ++;
//
//            $nextFolder = $this->CI->nested_sets_model->getNodeWhere($this->left_column_name.' = '.$lft_next.' AND user = '.$this->user);
//
//            if(!$nextFolder[$this->right_column_name]) break;
//
//            $lft_next = $nextFolder[$this->right_column_name];
//
//            $resultArray[] = $nextFolder;
//
//        }
//
//        return $resultArray;
//    }
    
//    function getAncestorToFolder($folderId = null)
//    {
//        if($folderId == null)
//        {
//            return $this->getRoot();
//        }
//
//        return $this->CI->nested_sets_model->getAncestor($this->_getNodeById($folderId));
//    }

    function getFolderTreeAsArray($node = array())
    {
        if(count($node) == 0)
        {
            $root = $this->getRoot();
            
            $node = $this->_getNodeById($root[$this->primary_key_column_name]);
        }

        return $this->CI->nested_sets_model->getTreeAsArray($node);
    }

    
}  

?>
