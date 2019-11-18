<?php
class Left_menu_model extends Model
{
    var $menu;
    var $sort_column_name;

    function Left_menu_model()
    {
        parent::Model();
        $this->menu = $this->_getHM ();//132; // Вертикальное меню
        $this->sort_column_name = 'sort'; // Поле сортировки
    }

	function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 132;
				break;
			case 'english':
				$result = 240;
				break;
			case 'kazakh':
				$result = 322;
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
    
    function init()
    {
    	//echo 'start';
        $html = '
            <div id="menu" class="block">
                <div id="left_menu_nav"></div>
                <div id="left_menu_content">';
        
        $html .= $this->get();
        
        $html .= '</div>
            </div>
        ';
        
        return $html;
    }
    
    function get()
    {
        //$folderId = $this->input->post('folder')? $this->input->post('folder'):null;
        
        //$this->session->unset_userdata('left_menu_folderId');die;


        //if(!isset($folderId) || $folderId == 'undefined')
        //{
            //$sessFolderId = $this->session->userdata('left_menu_folderId');
            
            //$folderId = (isset($sessFolderId) && is_numeric($sessFolderId))? $sessFolderId: $this->menu;
            
            //$folderId =  $this->menu;     

        //}
        //elseif(is_numeric($folderId))
        //{

            //$this->session->set_userdata('left_menu_folderId', $folderId);
        //}   

        
        //$ANCESTOR[0] = $this->getAncestor($folderId);
        
        //$ITEMS = $this->getItemsToFolder($folderId);

        //return $this->array_to_xml(array('item'=>$ITEMS, 'ancestor' => $ANCESTOR));
        //echo 'get ()';
        $menuNode = $this->folder->_getNodeById($this->menu);
        //print_r ($this->getTreeAsHTML($menuNode));
        //print_r ($menuNode);
        return $this->getTreeAsHTML($menuNode);
    }
    
   
    
    function getAncestor($parentFolderId = null)
    {
        $arrFolders = $this->lmenu->getAncestorToFolder($parentFolderId);
        
        if(count($arrFolders) == 0) return array();
    
        return $arrFolders; 
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
                        'url'       => '#',
                        'rel'       => 'folder'
                    ));
                    break;

                // Category
                case 'c':
                    $extraCurrentCategory = $this->category->getExtraCategory($next[$this->folder->primary_key_column_name]);
                    $extraCurrentCategory = $extraCurrentCategory[0];
                    if(count($extraCurrentCategory) == 0) continue;

                    $mas[] = array_merge($extraCurrentCategory, array(
                        'item_type' => 'c',
                        'url'       => 'left_menu/left_menu/category',
                        'rel'       => 'file'

                    ));
                    break;

                // File
                case 'a':
                    $extraCurrentFile = $this->file->getExtraFile($next[$this->folder->primary_key_column_name]);
                    $extraCurrentFile = $extraCurrentFile[0];
                    if(count($extraCurrentFile) == 0) continue;

                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'url'       => ''.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/view',
                        'rel'       => 'file'
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

        return $this->sort($extraParent[$this->sort_column_name]);
    }

    function array_to_xml($array, $level=1) {
            $xml = '';
        if ($level==1) {
            $xml .= "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n
                    <data>\n";
        }
        foreach ($array as $key=>$value) {
            $key = strtolower($key);
            if (is_array($value)) {
                $multi_tags = false;
                foreach($value as $key2=>$value2) {
                    if (is_array($value2)) {
                        $xml .= str_repeat("\t",$level)."<$key>\n";
                        $xml .= $this->array_to_xml($value2, $level+1);
                        $xml .= str_repeat("\t",$level)."</$key>\n";
                        $multi_tags = true;
                    } else {
                        if (trim($value2)!='') {
                            if (htmlspecialchars($value2)!=$value2) {
                                $xml .= str_repeat("\t",$level).
                                        "<$key><![CDATA[$value2]]>".
                                        "</$key>\n";
                            } else {
                                $xml .= str_repeat("\t",$level).
                                        "<$key>$value2</$key>\n";
                            }
                        }
                        $multi_tags = true;
                    }
                }
                if (!$multi_tags and count($value)>0) {
                    $xml .= str_repeat("\t",$level)."<$key>\n";
                    $xml .= $this->array_to_xml($value, $level+1);
                    $xml .= str_repeat("\t",$level)."</$key>\n";
                }
            } else {
                if (trim($value)!='') {
                    if (htmlspecialchars($value)!=$value) {
                        $xml .= str_repeat("\t",$level)."<$key>".
                                "<![CDATA[$value]]></$key>\n";
                    } else {
                        $xml .= str_repeat("\t",$level).
                                "<$key>$value</$key>\n";
                    }
                }
            }
        }
        if ($level==1) {
            $xml .= "</data>\n";
        }
        return $xml;
    }

    function substr($string)
    {
        if(strlen($string) == 0) return array();

        return array(
            $this->folder->primary_key_column_name => substr($string, 1),
            'type' => substr($string, 0, 1)
        );
    }
    
    function getTreeAsHTML($node = array())
    {

        $arrTree = $this->getSortingArray($this->folder->getExtraFolder($node));
        
		//print_r ($arrTree);
        $tree = '';
		$rel = 'rel="treeview"';
		
		if ($this->session->userdata('version_site') == 'ver2') $rel = ''; 
		
        if(count($arrTree) > 0) $tree = $this->parseArrayAsHTML($arrTree, $rel);
		//print_r ($tree);
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

		//print_r ($extraParentElement);

        if(count($extraParentElement) == 0) return $mas;

        $parentSortAsString = $extraParentElement[$this->sort_column_name];

        if(strlen($parentSortAsString) == 0) return $mas;

        $parentSortAsArray = explode(';', $parentSortAsString);

        foreach($parentSortAsArray as $nextElementAsString){
            $next = $this->substr($nextElementAsString);

            if(count($next) == 0) continue;
			//print_r ($next);
            switch($next['type']){
                // Folder
                case 'f':
                    $extraCurrentFolder = $this->folder->_getExtraById($next[$this->folder->primary_key_column_name]);
					//print_r ($this->folder->_getExtraById($next[$this->folder->primary_key_column_name]));
                    if(count($extraCurrentFolder) == 0) continue;

                    if(strlen($extraCurrentFolder[$this->folder->sort_column_name]) > 0) {
                        $childrenCurrentFolder = $this->getSortingArray($extraCurrentFolder);
                    } else {
                        $childrenCurrentFolder = array();
                    }
                    //$childrenCurrentFolder = array();
					//print_r ($childrenCurrentFolder);
					if ($this->session->userdata('version_site') == 'ver2') {
						foreach($childrenCurrentFolder AS $valEx) {
							$mas[] = $valEx;
						}
						break;
					}
                    $mas[] = array_merge($extraCurrentFolder, array(
                        'children'  => $childrenCurrentFolder,
                        'item_type' => 'f',
                        'url'       => site_url (array ('folders', 'folders', 'get', $next['id']))
                    ));
                    
                    //print_r($mas[count($mas) -1]);

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
                    ));//print_r($mas[count($mas) -1]);
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
					$param = 'onclick="return false;"';
					$iType = 'folder';
                    break;
                case 'c':
                    $rel = 'category';
                    $param = '';
                    $iType = 'category';
                    break;
                case 'a':
                    $rel = 'file';
                    $param = '';
                    $iType = $item['type'];
                    break;
            }
            //print_r ($iType);
            $html .= '<li'.$this->getClass ($iType, $item['id']).'>';
			
            $html .= '<span>'.anchor(
                $item['url'],
                $item['title'],
                'title="'.$item['title'].'"'.$param
            ).'</span>';
			//print_r ($item['url']);
			//echo '<br>';
            if($item['item_type'] == 'f'){
                if( isset($item['children']) && (count($item['children']) > 0) ) {
                    $html .= $this->parseArrayAsHTML($item['children']);
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
		return $this->arrDirs ($iType.$id.';');		
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
}
?>
