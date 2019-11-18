<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class T_menu_model extends model
{
    var $sort_column_name;

    function T_menu_model()
    {
        parent::Model();
        $this->menu = $this->_getHM ();//131; // Горизонтальное меню
        $this->sort_column_name = 'sort';
        
    }
    
    function block()
    {
    	
        $menuNode = $this->folder->_getNodeById($this->menu);
        
        $menuTree = $this->getTreeAsHTML($menuNode);
		//print_r ($menuTree);
        return $this->module->parse('t_menu', 'block', array('t_menu'=> $menuTree), true);
    }

    

    function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 131;
				break;
			case 'english':
				$result = 239;
				break;
			case 'kazakh':
				$result = 323;
				break;
			default:
				$result = false;
				break;
		}
		return $result;
    }

    function _getDefaultLang () {
		$q = $this->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
		return $q[0]['language'];
    }

    function getFilesToCategory($categoryId = null)
    {
        if($categoryId == null) return FALSE;
		
	if ($this->uri->segment(3) == 'category') 
	{
	   if ($this->uri->segment(5) == '')
	   { 
	      $start_pos = 0;
	   }
	   else
	   {
	      $start_pos  = ($this->uri->segment(5) - 1) * 15;
	   }	
       
       return   $this->db
                      ->from('file')
                      ->where('category_id', $categoryId)
                      ->order_by('created', 'desc')
                      ->limit(60, $start_pos)
		              ->get()
                      ->result_array();
     }
     else
     {
	   return   $this->db
       ->from('file')
       ->where('category_id', $categoryId)
       ->order_by('created', 'desc')        
	   ->get()
	   ->result_array();
	   
	 }
    }

    function getTreeAsHTML($node = array())
    {
		//print_r ($node);
		//print_r ($this->folder->getExtraFolder($node));
        $arrTree = $this->getSortingArray($this->folder->getExtraFolder($node));
        //print_r ($node);

        $tree = '';
        $jd_menu = 'class="jd_menu"';
		if ($this->session->userdata('version_site') == 'ver2') $jd_menu = '';
        if(count($arrTree) > 0) $tree = $this->parseArrayAsHTML($arrTree, $jd_menu);

        return $tree;
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
                    //$childrenCurrentFolder = array ();
					if ($this->session->userdata('version_site') == 'ver2') {
						foreach($childrenCurrentFolder AS $valEx) {
							$mas[] = $valEx;
						}
						break;
					}
                    $mas[] = array_merge($extraCurrentFolder, array(
                        'children'  => $childrenCurrentFolder,
                        'item_type' => 'f',
                        'url'       => '#'//site_url (array ('folders', 'folders', 'get', $next['id']))
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
                        'url'       => base_url().'t_menu/t_menu/category/'.$next[$this->folder->primary_key_column_name]
                    ));

                    break;

                // File
                case 'a':
                    $extraCurrentFile = $this->file->getExtraFile($next[$this->folder->primary_key_column_name]);
                    $extraCurrentFile = $extraCurrentFile[0];

                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'url'       => base_url().$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/view/'.$next[$this->folder->primary_key_column_name]
                    ));
                    break;
            }
        }

        return $mas;
    }

    /**
     * @name parseArrayAsHTML
     * @param $treeArray - Sorting array tree
     * @return HTML element [ul]
     */
    function parseArrayAsHTML($treeArray, $param = '')
    {
        if(count($treeArray) == 0) return '';
		//print_r ($treeArray);
        $html = "<ul ".$param.">";
		
        foreach($treeArray as $item){
			
            switch($item['item_type']){
                case 'f':
                    $rel = 'folder';
                    $iType = 'folder';
                    break;
                case 'c':
                    $rel = 'category';
                    $iType = 'category';
                    break;
                case 'a':
                    $rel = 'file';
                    $iType = $item['type'];
                    break;
            }



            $html .= '<li id="'.$item['item_type'].$item[$this->folder->primary_key_column_name].'" rel="'.$rel.'">';
            
            $html .= anchor(
                $item['url'],
                $item['title'],
                'item="'.$item['item_type'].$item[$this->folder->primary_key_column_name].'" title="'.$item['title'].'"'.$this->getClass ($iType, $item['id'])
            );
            //print_r ($html);

            if($item['item_type'] == 'f'){
                if( isset($item['children']) && (count($item['children']) > 0) ) {
                    $html .= ''.$this->parseArrayAsHTML($item['children']);
                }
            }
            
            $html .= '</li>';
        }

        $html .= "</ul>";

        return $html;
    }

	// Добавление классов для нужных элементов
	function getClass ($item, $id, $link = '') {
		$types = array (
			'article',
			'video',
			'news',
			'document',
			'photo',
			'folders',
			't_menu'
		);
		$cattypes = array (
			'article',
			'video',
			'news',
			'document',
			'photo'
		);
		$result = '';
		switch ($item) {
			case 'anchor':
				$q = $this->db->select ('url')->where ('file_id', $id)->get ('th_anchor')->result_array ();
				$thisURI = $this->input->server ('REQUEST_URI');
				$queryURI = $q[0]['url'];
				if ($queryURI == $thisURI || '/' . $queryURI == $thisURI || '/' . $queryURI . '/' == $thisURI || $queryURI == $thisURI . '/') {
					$result = ' class="active"';
				}
				break;
			case 'article':
			case 'video':
			case 'news':
			case 'document':
			case 'photo':
				if ($this->uri->segment (1) == $item && $this->uri->segment (2) == $item && $this->uri->segment (3) == 'view' && $this->uri->segment (4) == $id) {
					$result = ' class="active"';
				}
				break;
			case 'folder':

				if (in_array ($this->uri->segment (1), $types)) {
					$arrDirs = $this->getDirs ($this->uri->segment (4), $this->uri->segment (1));
					$fid = 'f' . $id . ';';
					if (in_array ($fid, $arrDirs)) {
						$result = ' class="active"';
					}
				}
				break;
			case 'category':
				if (in_array ($this->uri->segment (1), $cattypes)) {
					$q = $this->db->where ('id', $this->uri->segment (4))->get ('th_file')->result_array ();
					if (count ($q) > 0 && $q[0]['category_id'] == $id) {
						$result = ' class="active"';
						break;
					}
				}
				if ($this->uri->segment (1) == 't_menu' && $this->uri->segment (2) == 't_menu' && $this->uri->segment (3) == 'category' && $this->uri->segment (4) == $id) {
					$result = ' class="active"';
				}
				break;
			default:
				break;
		}
		return $result;
	}

	// Распределение по типам для дальнейшего получения ID-шников в массиве
	function getDirs ($id, $type) {
		switch ($type) {
			case 'article':
			case 'video':
			case 'news':
			case 'document':
			case 'photo':
				$iType = 'a';
				$q = $this->db->select ('category_id')->where ('id', $id)->get ('th_file')->result_array ();
				if ($q[0]['category_id'] > 0) {
					$iType = 'c';
					$id = $q[0]['category_id'];
				}
				break;
			case 'folders':
				$iType = 'f';
				break;
			case 't_menu':
				$iType = 'c';
				break;
			default:
				break;
		}
		return $this->arrDirs ($iType.$id.';');		// (.)(.) Dreams...
	}

	// Получаем массив ID-шников директорий для проверки текущей директории
	function arrDirs ($id) {
		$arr = array ();
		$arr[] = $id;
		$q = $this->db->select ('id')->like ('sort', $id)->get ('th_folder')->result_array ();
		if (count ($q) > 0) {
			$tempArr = $this->arrDirs ('f' . $q[0]['id'] . ';');
			foreach ($tempArr AS $v) {
				$arr[] = $v;
			}
		}
		return $arr;
	}

    function substr($string)
    {
        if(strlen($string) == 0) return array();

        return array(
            $this->folder->primary_key_column_name => substr($string, 1),
            'type' => substr($string, 0, 1)
        );
    }
}
?>
