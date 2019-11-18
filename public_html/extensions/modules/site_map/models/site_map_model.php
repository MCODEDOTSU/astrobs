<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Site_map_model extends Model
{
    var $lang;
    var $sort_column_name;

    function Site_map_model()
    {
        parent::Model();

             $this->langPlace = array(
            'folder'   => 'Раздел',
            'file'     => 'Страница',
            'category' => 'Категория'
        );

        $this->sort_column_name = 'sort';
    }

    function _icon($type, $return = false)
    {
        if($return == true){
            //return base_url().'administrator/extensions/modules/'.$type.'/icons/'.$this->module->config($type,'icon');
        }
        else {
            //return '<img align="absmiddle" style="border:0px;" src="'.base_url().'administrator/extensions/modules/'.$type.'/icons/'.$this->module->config($type,'icon').'" />';
        }
    }
    
    function item($item = '', $attr = '')
    {
        return $this->module->parse('place', 'item.php', array('item' => $item, 'attr' => $attr), TRUE);
    }

    function ftype($returnType = array())
    {
        $resultArray = array();

        foreach($returnType as $type)
        {
            $resultArray[$type] = $this->langPlace[$type];
        }

        $is_loaded_modules = $this->config->is_loaded_modules;

        if(!is_array($is_loaded_modules)) return FALSE;

        foreach($is_loaded_modules as $module)
        {
            if(isset($this->config->config_modules[$module][$module]['place']))
            {
                $resultArray[$module] = $this->config->config_modules[$module][$module]['title'];
            }
        }

        return $resultArray;
    }

	function _getHM () {
		$plang = ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		list ($q) = $this->db->query ("SELECT `th_folder`.* FROM `th_folder`, `th_language_structure` WHERE `th_language_structure`.`language` = ? AND `th_language_structure`.`fid` = `th_folder`.`id`", array ($plang))->result_array ();
		return $q;
		
	}

	function _getDefaultLang () {
		$q = $this->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
		return $q[0]['language'];
	}
    
    function getTreeAsHTML($node = array())
    {
//    $this->load->library('Folder');
        $root = $this->_getHM (); //$this->folder->getRoot();
        //print_R ($root);
    //    $root = $this->folder->_getExtraById($this->authorization->group['folder']);
        
        $arrTree = $this->getSortingArray($root);
        
    /*    $tree = '
            <ul>
                <li class="open" rel="root" id="f'.$root[$this->folder->primary_key_column_name].'">

                    '.anchor(
                        'admin/place/content/folder', // URL
                        '<ins style="background:url('.base_url().'cms_icons/drive.png);">&nbsp;</ins>', // title
                        'title="root" item="f:'.$root[$this->folder->primary_key_column_name].'"' // extra
                    ).'

                    ';

        if(count($arrTree) > 0) $tree .= $this->parseArrayAsHTML($arrTree);
        
        $tree .= '
                </li>
            </ul>
        ';*/
        $tree = ' <ul> ';
        if(count($arrTree) > 0) $tree .= $this->parseArrayAsHTML($arrTree);
        $tree .= '
                </li>
            </ul>
        ';
        //print_R ($tree);
        return $tree;
    }

    


    /**
     * @name addInSortingString
     * @param $sortAsString - Куда надо вставить
     * @param $node - Что надо вставить
     * @param $target - Относительно чего надо вставить
     * @param $position - Как именно надо вставить
     * @return String sorting
     */
    function inSortingString($sortAsString = '', $position = '', $node = '', $target = '')
    {
        $newSortString = '';
        $sortAsArray = array();
        $newSortArray = array();

        if(strlen($node) == 0) return FALSE;


        switch($position){
            case 'before':
                $sortAsArray = explode(";", $sortAsString);
                foreach($sortAsArray as $item) {
                    if(strlen($item) == 0) continue;
                    if($item == $target) {$newSortArray[] = $node;}
                    $newSortArray[] = $item;
                }
                $newSortString .= implode(';', $newSortArray).';';
                break;
            case 'after':
                $sortAsArray = explode(";", $sortAsString);
                foreach($sortAsArray as $item) {
                    if(strlen($item) == 0) continue;
                    $newSortArray[] = $item;
                    if($item == $target) {$newSortArray[] = $node;}
                }
                $newSortString .= implode(';', $newSortArray).';';
                break;
            case 'inside':
                $newSortString .= $node.';'.$sortAsString;
                break;
            case 'remove';
                $sortAsArray = explode(";", $sortAsString);
                foreach($sortAsArray as $item) {
                    if(strlen($item) == 0) continue;
                    if($item == $node) {continue;}
                    $newSortArray[] = $item;
                }
                $newSortString .= implode(';', $newSortArray).';';
                break;
        }

        return (strlen($newSortString) < 2)? '':$newSortString;
    }

    /**
     * function getStructureTreeAsHTML
     * @param Array - parent element
     * @return Sorting array
     */
    function getSortingArray($extraParentElement = array())
    {
        $mas = array();
        
        if(count($extraParentElement) == 0) return $mas;

        $parentSortAsString = $extraParentElement[$this->sort_column_name];
        
        if(strlen($parentSortAsString) == 0) return $mas;

        $parentSortAsArray = explode(';', $parentSortAsString);
        
        foreach($parentSortAsArray as $nextElementAsString){
            $next = $this->substr($nextElementAsString);

            if(count($next) == 0) continue;

            switch($next['type']){
                // Folder
                case 'f':
                    $extraCurrentFolder = $this->folder->_getExtraById($next[$this->folder->primary_key_column_name]);

                    if(count($extraCurrentFolder) == 0) continue;

                    if(strlen($extraCurrentFolder[$this->folder->sort_column_name]) > 0) {
                        $childrenCurrentFolder = $this->getSortingArray($extraCurrentFolder);
                    } else {
                        $childrenCurrentFolder = array();
                    }

                    $mas[] = array_merge($extraCurrentFolder, array(
                        'children'  => $childrenCurrentFolder,
                        'item_type' => 'f',
                        'icon'      => _icon('folder_blue'),
                        'url'       => ''
                    ));
                    
                    break;

                // Category
                case 'c':
                    $extraCurrentCategory = $this->category->getExtraCategory($next[$this->folder->primary_key_column_name]);
                    $extraCurrentCategory = $extraCurrentCategory[0];

                    if(count($extraCurrentCategory) == 0) continue;

                    if(strlen($extraCurrentCategory[$this->category->sort_column_name]) > 0) {
                        $childrenCurrentCategory = $this->getSortingArray($extraCurrentCategory);
                    } else {
                        $childrenCurrentCategory = array();
                    }

                    $mas[] = array_merge($extraCurrentCategory, array(
                        'children'  =>$childrenCurrentCategory,
                        'item_type' => 'c',
                        'icon'      => '', //_icon('folder_brick'),
                        'url'       =>  't_menu/t_menu/category/'.$extraCurrentCategory['id']
                    ));
                    
                    break;

                // File
                case 'a':
                    $extraCurrentFile = $this->file->getExtraFile($next[$this->folder->primary_key_column_name]);
                    $extraCurrentFile = $extraCurrentFile[0];
                    
                    if ($extraCurrentFile['type']=='article')
                    {
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                        'url'       => $extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/view/'.$extraCurrentFile['id']
                    ));
                    }/* else {
                     $mas[] = array_merge($extraCurrentFile, array(
                         'item_type' => 'a',
                         'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                         'url'       => 'site_map/site_map'
                     ));
                    }*/
                    break;
            }
        }

        return $mas;
    }


    function substr($string)
    {
        if(strlen($string) == 0) return array();
        
        return array(
            $this->folder->primary_key_column_name => substr($string, 1),
            'type' => substr($string, 0, 1)
        );
    }


    /**
     * @name parseArrayAsHTML
     * @param $treeArray - Sorting array tree
     * @return HTML element [ul]
     */
    function parseArrayAsHTML($treeArray, $param = '')
    {
    	//print_r ($treeArray);
        if(count($treeArray) == 0) return '';
        
        $html = "<ul ".$param.">";
		//print_R ($this->folder->primary_key_column_name);
        foreach($treeArray as $item){
            
            switch($item['item_type']){
                case 'f':
                    $rel = 'folder';
                    break;
                case 'c':
                    $rel = 'category';
                    break;
                case 'a':
                    $rel = 'file';
                    break;
            }


            $html .= '<li id="'.$item['item_type'].$item[$this->folder->primary_key_column_name].'" rel="'.$rel.'">';

            if ($rel!='folder')
            {
            $html .= anchor(
                $item['url'],
                $item['icon'].$item['title'],
                'item="'.$item['item_type'].':'.$item[$this->folder->primary_key_column_name].'" title="'.$item['title'].'"'
            );
            }
            else  $html .=  $item['icon'].$item['title'];

            if( isset($item['children']) && (count($item['children']) > 0) ) {
                $html .= $this->parseArrayAsHTML($item['children']);
            }
        }
        
        $html .= "</ul>";
        
        return $html;
    }

    function sort($condition = '')
    {
        $mas = array();
        
        if(strlen($condition) == 0) return $mas;

        $conditionAsArray = explode(';', $condition);

        foreach($conditionAsArray as $nextElementAsString){
            $next = $this->substr($nextElementAsString);

            if(count($next) == 0) continue;

            switch($next['type']){
                // Folder
                case 'f':
                    $extraCurrentFolder = $this->folder->_getExtraById($next[$this->folder->primary_key_column_name]);
                    if(count($extraCurrentFolder) == 0) continue;

                    $mas[] = array_merge($extraCurrentFolder, array(
                        'item_type' => 'f',
                        'icon'      => _icon('folder'),
                        'url'       => 'admin/place/content/folder',
                        'rel'       => ''
                    ));
                    break;

                // Category
                case 'c':
                    $extraCurrentCategory = $this->category->getExtraCategory($next[$this->folder->primary_key_column_name]);
                    $extraCurrentCategory = $extraCurrentCategory[0];
                    if(count($extraCurrentCategory) == 0) continue;

                    $mas[] = array_merge($extraCurrentCategory, array(
                        'item_type' => 'c',
                        'icon'      => _icon('folder_brick'),
                        'url'       => 'admin/place/content/category',
                        'rel'       => ''

                    ));
                    break;

                // File
                case 'a':
                    $extraCurrentFile = $this->file->getExtraFile($next[$this->folder->primary_key_column_name]);
                    $extraCurrentFile = $extraCurrentFile[0];
                    if(count($extraCurrentFile) == 0) continue;
                    
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file',
                        'rel'       => 'dialog'
                    ));
                    
                    break;
            }
        }

        return $mas;
    }

    function getItemsToFolder($parentFolderId = null)
    {
        $extraParentFolder = $this->folder->_getExtraById($parentFolderId);
        if(count($extraParentFolder) == 0) return array();

        $mas = $this->getItems($extraParentFolder);
        
        return $mas;
    }

    function getItemsToCategory($parentCategoryId = null)
    {
        $extraParentCategory = $this->category->getExtraCategory($parentCategoryId);
        $extraParentCategory = $extraParentCategory[0];
        
        if(count($extraParentCategory) == 0) return array();

        $mas = $this->getItems($extraParentCategory);

        return $mas;
    }

    function getItems($extraParent = array())
    {
        if(count($extraParent) == 0) return array();
        
        if(strlen($extraParent[$this->sort_column_name]) == 0) return array();

        $arrFiles = $this->sort($extraParent[$this->sort_column_name]);

        foreach($arrFiles as $item)
        {
            $mas[] = array(
                'item'   => $this->item(anchor(
                    $item['url'],
                    $item['icon'].$item['title'],
                    'item="'.$item['item_type'].':'.$item[$this->folder->primary_key_column_name].'" rel="'.$item['rel'].'"'
                ))
            );
        }

        return $mas;
    }
}
?>
