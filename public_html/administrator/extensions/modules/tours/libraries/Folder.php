<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Folder
{
    // Имя папки
    var $title;
    
    // CodeIgniter
    var $CI;
    var $user;
    var $root;

    var $table_name;
    var $left_column_name;
    var $right_column_name;
    var $primary_key_column_name;
    var $sort_column_name;
  
    // Конструктор
    function Folder()
    {
        // Initialize CodeIgniter
        $this->CI = &get_instance();

        // Get session User data
        $this->user = $this->CI->authorization->user;

        // Get Nested Sets params
        $this->table_name = $this->CI->nested_sets_model->table_name;
        $this->left_column_name = $this->CI->nested_sets_model->left_column_name;
        $this->right_column_name = $this->CI->nested_sets_model->right_column_name;
        $this->primary_key_column_name = $this->CI->nested_sets_model->primary_key_column_name;
        $this->sort_column_name = 'sort';

        // Get root primary key
        $root = $this->getRoot();
        $this->root = $root[$this->primary_key_column_name];
    }
    
    // Удаление папки
    function deleteFolder($id)
    {
        if(!is_numeric($id)) return FALSE;

        $parentNode = $this->getAncestorToFolder($id);

        $extraParentNode = $this->getExtraFolder($parentNode);

        $newSortString = str_replace('f'.$id.';','',$extraParentNode[$this->sort_column_name]);

        $this->modifyExtraFolder(array(
            $this->sort_column_name => $newSortString
        ), $parentNode);

        $node = $this->_getNodeById($id);
        
        return $this->CI->nested_sets_model->deleteNode($node);
    }
    
    /**
     * Create folder
     * Same as insertNewChild except the new node is added as the last child
     * @param array $parentNode The node array of the parent to use
     * @param array $extrafields An associative array of fieldname=>value for the other fields in the recordset
     * @return array $childNode An associative array representing the new node
     */
    function createFolder($extrafields = array(), $parentId = null)
    {
        if($parentId == null){
            $node = $this->getRoot();
        } else {
            $node = $this->_getNodeById($parentId);
        }

        // Добавляет в начало списка
        $childNode = $this->CI->nested_sets_model->insertNewChild($node, $extrafields);

        // Добавляет в конец списка
        //$childNode = $this->CI->nested_sets_model->appendNewChild($node, $extrafields);

        $extraChild = $this->getExtraFolder($childNode);

        
        if($parentId == null){
            $node = $this->getRoot();
        } else {
            $node = $this->_getNodeById($parentId);
        }
        
        $extraParentFolder = $this->getExtraFolder($node);
        
        $this->modifyExtraFolder(array(
            $this->sort_column_name => 'f'.$extraChild[$this->primary_key_column_name].';'.$extraParentFolder[$this->sort_column_name]
        ), $node);

        return $childNode;
    }

    function getExtraFolder($node = array())
    {
        if(count($node) == 0) return FALSE;

        $folderId = $this->CI->db
                    ->select($this->primary_key_column_name)
                    ->from($this->table_name)
                    ->where(array(
                        $this->left_column_name => $node[$this->left_column_name],
                        $this->right_column_name => $node[$this->right_column_name]
                      ))
                    ->get()
                    ->result_array();

        return $this->_getExtraById($folderId[0][$this->primary_key_column_name]);
    }
    
    function createRoot($extrafields = array('title'=>'root', 'visible' => 0))
    {
        return $this->CI->nested_sets_model->initialiseRoot($extrafields);           
    }
    
    // Переименование папки
    function renameFolder($title, $id)
    {
        if(!is_numeric($id)) return FALSE;
        
        if( (strlen($title) == ' ') && (strlen($title) < 1) ) return FALSE;

        $node = $this->_getNodeById($id);

        if(!count($node)) return FALSE;

        return $this->CI->db->update($this->table_name, array('title' => $title), array($this->primary_key_column_name => $id));
    }
    
    function _getNodeById($id)
    {
        if(!is_numeric($id)) return FALSE;

        $targetFolder = $this->CI->nested_sets_model->getNodeFromId($id);
        
        return array(
            $this->left_column_name => $targetFolder[$this->left_column_name],    
            $this->right_column_name => $targetFolder[$this->right_column_name]    
        );
    }
    
    function _getExtraById($id)
    {
        if(!is_numeric($id)) return FALSE;
        
        return $this->CI->nested_sets_model->getNodeFromId($id);
    }
    
    function deleteRoot()
    {
        $this->CI->nested_sets_model->deleteTree();
        $this->createRoot();
        
        return TRUE;
    }
    
    function getRoot()
    {
        return $this->CI->nested_sets_model->getRoot();
    }
    
    function getLevel($id = null)
    {
        $resultArray = array();
        
        if(!isset($id)){
            $rootFolder = $this->getRoot();
            $id = $rootFolder[$this->primary_key_column_name];
        }
        
        $targetFolder = $this->_getNodeById($id);
        
        $lft_next = $targetFolder[$this->left_column_name];

        //Получаем всех предков у выбранного раздела
        while(TRUE){
            $lft_next ++;
            
            $nextFolder = $this->CI->nested_sets_model->getNodeWhere($this->left_column_name.' = '.$lft_next);
            
            if(!$nextFolder[$this->right_column_name]) break;
            
            $lft_next = $nextFolder[$this->right_column_name];
            
            $resultArray[] = $nextFolder;
            
        }
        
        return $resultArray;
    }
    
    function getAncestorToFolder($folderId = null)
    {
        if($folderId == null) return $this->getRoot();
        
        return $this->CI->nested_sets_model->getAncestor($this->_getNodeById($folderId));
    }

    function getFolderTreeAsArray($node = array())
    {
        if(count($node) == 0)
        {
            $root = $this->getRoot();

            $node = $this->_getNodeById($root[$this->primary_key_column_name]);
        }

        return $this->CI->nested_sets_model->getTreeAsArray($node);
    }

    function modifyExtraFolder($extrafields, $node = array())
    {
        if(count($node) == 0) return FALSE;
        
        $extraFolder = $this->getExtraFolder($node);

        return $this->CI->db->update(
            $this->table_name,
            $extrafields,
            array($this->primary_key_column_name => $extraFolder[$this->primary_key_column_name])
        );
    }

    function setNodeAsNextSibling($id = null, $target_id = null)
    {
        $node = $this->_getNodeById($id);
        if(count($node) == 0) return FALSE;
        $target = $this->_getNodeById($target_id);
        if(count($target) == 0) return FALSE;

        return $this->CI->nested_sets_model->setNodeAsNextSibling($node, $target);
    }

    function setNodeAsPrevSibling($id = null, $target_id = null)
    {
        $node = $this->_getNodeById($id);
        if(count($node) == 0) return FALSE;
        $target = $this->_getNodeById($target_id);
        if(count($target) == 0) return FALSE;

        return $this->CI->nested_sets_model->setNodeAsPrevSibling($node, $target);
    }

    function setNodeAsFirstChild($id = null, $target_id = null)
    {
        $node = $this->_getNodeById($id);
        if(count($node) == 0) return FALSE;
        $target = $this->_getNodeById($target_id);
        if(count($target) == 0) return FALSE;

        return $this->CI->nested_sets_model->setNodeAsFirstChild($node, $target);
    }

    function setNodeAsLastChild($id = null, $target_id = null)
    {
        $node = $this->_getNodeById($id);
        if(count($node) == 0) return FALSE;
        $target = $this->_getNodeById($target_id);
        if(count($target) == 0) return FALSE;

        return $this->CI->nested_sets_model->setNodeAsLastChild($node, $target);
    }
    
}  

?>