<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Place_model extends Model
{
    var $lang;
    var $sort_column_name;
    var $ltable;

    function Place_model()
    {
        parent::Model();
        $this->ltable = 'th_language_structure';

        $this->langPlace = array(
            'folder'   => 'Раздел',
            'file'     => 'Страница',
            'category' => 'Рубрика'
        );

        $this->sort_column_name = 'sort';
    }

    function _icon($type, $return = false)
    {
        if($return == true){
            return base_url().'administrator/extensions/modules/'.$type.'/icons/'.$this->module->config($type,'icon');
        }
        else {
            return '<img align="absmiddle" style="border:0px;" src="'.base_url().'administrator/extensions/modules/'.$type.'/icons/'.$this->module->config($type,'icon').'" />';
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

	function getLangs () {
		$q = $this->db->get ($this->ltable)->result_array ();
		$langs = array ();
		foreach ($q AS $v) {
			$langs[$v['fid']] = $v['text'];
		}
		return $langs;
	}

    function defaultLang ($id = 0) {
    	if ((int) $id == 0) {
			$where = array ('default' => 1);
    	} else {
			$where = array ('fid' => $id);
    	}
		$q = $this->db->where ($where)->limit (1)->get ($this->ltable)->result_array ();
		return $q[0]['fid'];
    }
    
    function getTreeAsHTML($node = array(), $langId = 0)
    {

		if ($langId == 0) {
			$langId = $this->authorization->group['folder'];
		}

    	//print_r ($this->authorization->group['folder']);
        //$root = $this->folder->getRoot();
        $root = $this->folder->_getExtraById($langId/*$this->authorization->group['folder']*/);
        //print_r ($root);
        $arrTree = $this->getSortingArray($root);
        //print_r ($arrTree);
        //print_r ($root[$this->folder->primary_key_column_name]);
        $tree = '
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
        ';
		
        return $tree;
    }

    

    /**
     * @name onMove
     * @param $node - Элемент(array[id, type]), который перемещаем
     * @param $ref_node - Элемент (array[id, type]), относительно которого перемещаем
     * @param $position - String('after', 'before', 'inside'), как именно мы перемещаем
     */
    function onMove($node, $ref_node, $position)
    {
        /*
         * 1. У $node найти предка и у предка удалить из поля сортировки значение $node
         * 2. Если:
         *      After - Найти предка у $ref_node и добавить в поле сортировки $node после $ref_node
         *      Before - Найти предка у $ref_node и добавить в поле сортировки $node перед $ref_node
         *      Inside - В поле сортировки у $ref_node добавить в конец этого списка, $node
         */

        // Выполняем необходимые проверки
        if( (count($node) == 0)  || (count($ref_node) == 0) ) die;
        if(in_array($position,array('after', 'before', 'inside')) === FALSE) die;


        // ********** Start 1 ************
        //
        // Определяем тип $node для того чтобы узнать предка
        switch($node['type']){
            
            case 'f':
                $parentNode         = $this->folder->getAncestorToFolder($node[$this->folder->primary_key_column_name]);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $newSortString      = $this->inSortingString($sortString, 'remove', $node['type'].$node[$this->folder->primary_key_column_name]);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                break;
            
            case 'c':
                $extraCategory      = $this->category->getExtraCategory($node[$this->folder->primary_key_column_name]);
                if(count($extraCategory) == 0) return FALSE;
                $extraCategory      = $extraCategory[0];
                if(count($extraCategory) == 0) return FALSE;
                $parentNode         = $this->folder->_getNodeById($extraCategory['folder_id']);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $newSortString      = $this->inSortingString($sortString, 'remove', $node['type'].$node[$this->folder->primary_key_column_name]);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                break;
            
            case 'a':
                $extraFile                  = $this->file->getExtraFile($node['id']);
                if(count($extraFile) == 0) return FALSE;
                $extraFile                  = $extraFile[0];
                if(count($extraFile) == 0) return FALSE;
                if($extraFile['folder_id'] > 0){
                    $parentNode             = $this->folder->_getNodeById($extraFile['folder_id']);
                    if(count($parentNode) == 0) return FALSE;
                    $parentNodeExtra        = $this->folder->getExtraFolder($parentNode);
                    $sortString             = $parentNodeExtra[$this->sort_column_name];
                    $newSortString          = $this->inSortingString($sortString, 'remove', $node['type'].$node[$this->folder->primary_key_column_name]);
                    $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);

                }else if($extraFile['category_id'] > 0){
                    $extraParentCategory    = $this->category->getExtraCategory($extraFile['category_id']);
                    if(count($extraParentCategory) == 0) return FALSE;
                    $extraParentCategory    = $extraParentCategory[0];
                    if(count($extraParentCategory) == 0) return FALSE;
                    $sortString             = $extraParentCategory[$this->category->sort_column_name];
                    $newSortString          = $this->inSortingString($sortString, 'remove', $node['type'].$node[$this->folder->primary_key_column_name]);
                    $this->category->modifyExtraCategory(array($this->folder->sort_column_name => $newSortString), $extraFile['category_id']);
                }
                break;
        }
        // ************ End 1 ************

        switch($position){
            
            case 'before':
                $this->onMoveNodeBefore($node, $ref_node);
                break;

            case 'after':
                $this->onMoveNodeAfter($node, $ref_node);
                break;

            case 'inside':
                $this->onMoveNodeInside($node, $ref_node);
                break;
        }

        return TRUE;
    }

    /*
     * ========================
     * function onMoveNodeAfter
     * ========================
     * Перемещаем $node после $ref_node
     * Найти предка у $ref_node и добавить в поле сортировки $node после $ref_node
     */
    function onMoveNodeAfter($node, $ref_node)
    {
        if( (count($node) == 0)  || (count($ref_node) == 0) ) die;

        switch($ref_node['type']){
            
            case 'f':
                $parentNode         = $this->folder->getAncestorToFolder($ref_node[$this->folder->primary_key_column_name]);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $newSortString      = $this->inSortingString($sortString, 'after', $node['type'].$node[$this->folder->primary_key_column_name], $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                switch($node['type']){
                    case 'f':
                        $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                        break;
                    case 'c':
                        $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name], 'category_id' => '0'), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'c':
                $extraCategory      = $this->category->getExtraCategory($ref_node[$this->folder->primary_key_column_name]);
                if(count($extraCategory) == 0) return FALSE;
                $extraCategory      = $extraCategory[0];
                if(count($extraCategory) == 0) return FALSE;
                $parentNode         = $this->folder->_getNodeById($extraCategory['folder_id']);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $newSortString      = $this->inSortingString($sortString, 'after', $node['type'].$node[$this->folder->primary_key_column_name], $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                switch($node['type']){
                    case 'f':
                        $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                        break;
                    case 'c':
                        $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name], 'category_id' => '0'), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'a':
                $extraFile                  = $this->file->getExtraFile($ref_node['id']);
                if(count($extraFile) == 0) return FALSE;
                $extraFile                  = $extraFile[0];
                if(count($extraFile) == 0) return FALSE;
                if($extraFile['folder_id'] > 0){
                    $parentNode             = $this->folder->_getNodeById($extraFile['folder_id']);
                    if(count($parentNode) == 0) return FALSE;
                    $parentNodeExtra        = $this->folder->getExtraFolder($parentNode);
                    $sortString             = $parentNodeExtra[$this->sort_column_name];
                    $newSortString      = $this->inSortingString($sortString, 'after', $node['type'].$node[$this->folder->primary_key_column_name], $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]);
                    $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                    switch($node['type']){
                        case 'f':
                            $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                            break;
                        case 'c':
                            $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                            break;
                        case 'a':
                            $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name], 'category_id' => '0'), $node[$this->folder->primary_key_column_name]);
                            break;
                    }
                }else if($extraFile['category_id'] > 0){
                    $extraParentCategory    = $this->category->getExtraCategory($extraFile['category_id']);
                    if(count($extraParentCategory) == 0) return FALSE;
                    $extraParentCategory    = $extraParentCategory[0];
                    if(count($extraParentCategory) == 0) return FALSE;
                    $sortString             = $extraParentCategory[$this->category->sort_column_name];
                    $newSortString      = $this->inSortingString($sortString, 'after', $node['type'].$node[$this->folder->primary_key_column_name], $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]);
                    $this->category->modifyExtraCategory(array($this->folder->sort_column_name => $newSortString), $extraFile['category_id']);
                    switch($node['type']){
                        case 'f':
                            return FALSE;
                            break;
                        case 'c':
                            return FALSE;
                            break;
                        case 'a':
                            $this->file->modifyExtraFile(array('folder_id'=>'0', 'category_id' => $extraFile['category_id']), $node[$this->folder->primary_key_column_name]);
                            break;
                    }
                }
                break;
        }

        return TRUE;
    }


    /*
     * ========================
     * function onMoveNodeBefore
     * ========================
     * Перемещаем $node перед $ref_node
     * Найти предка у $ref_node и добавить в поле сортировки $node перед $ref_node
     */

    function onMoveNodeBefore($node, $ref_node)
    {
        if( (count($node) == 0)  || (count($ref_node) == 0) ) die;

        switch($ref_node['type']){

            case 'f':
                $parentNode         = $this->folder->getAncestorToFolder($ref_node[$this->folder->primary_key_column_name]);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $sortArray          = explode(";", $sortString);
                
                foreach($sortArray as $item) {
                    if($item == $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]){
                        $newSortArray[] = $node['type'].$node[$this->folder->primary_key_column_name];
                    }
                    $newSortArray[] = $item;
                }
                $newSortString      = implode(';', $newSortArray);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                switch($node['type']){
                    case 'f':
                        $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                        break;
                    case 'c':
                        $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'c':
                $extraCategory      = $this->category->getExtraCategory($ref_node[$this->folder->primary_key_column_name]);
                if(count($extraCategory) == 0) return FALSE;
                $extraCategory      = $extraCategory[0];
                if(count($extraCategory) == 0) return FALSE;
                $parentNode         = $this->folder->_getNodeById($extraCategory['folder_id']);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $sortArray          = explode(";", $sortString);
                foreach($sortArray as $item) {
                    if($item == $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]){
                        $newSortArray[] = $node['type'].$node[$this->folder->primary_key_column_name];
                    }
                    $newSortArray[] = $item;
                }
                $newSortString      = implode(';', $newSortArray);
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                switch($node['type']){
                    case 'f':
                        $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                        break;
                    case 'c':
                        $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'a':
                $extraFile                  = $this->file->getExtraFile($ref_node['id']);
                if(count($extraFile) == 0) return FALSE;
                $extraFile                  = $extraFile[0];
                if(count($extraFile) == 0) return FALSE;
                if($extraFile['folder_id'] > 0){
                    $parentNode             = $this->folder->_getNodeById($extraFile['folder_id']);
                    if(count($parentNode) == 0) return FALSE;
                    $parentNodeExtra        = $this->folder->getExtraFolder($parentNode);
                    $sortString             = $parentNodeExtra[$this->sort_column_name];
                    $sortArray              = explode(";", $sortString);
                    foreach($sortArray as $item) {
                        if($item == $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]){
                            $newSortArray[] = $node['type'].$node[$this->folder->primary_key_column_name];
                        }
                        $newSortArray[] = $item;
                    }
                    $newSortString          = implode(';', $newSortArray);
                    $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                    switch($node['type']){
                        case 'f':
                            $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                            break;
                        case 'c':
                            $this->category->modifyExtraCategory(array('folder_id' => $parentNodeExtra[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                            break;
                        case 'a':
                            $this->file->modifyExtraFile(array('folder_id'=>$parentNodeExtra[$this->folder->primary_key_column_name], 'category_id' => '0'), $node[$this->folder->primary_key_column_name]);
                            break;
                    }
                }else if($extraFile['category_id'] > 0){
                    $extraParentCategory    = $this->category->getExtraCategory($extraFile['category_id']);
                    if(count($extraParentCategory) == 0) return FALSE;
                    $extraParentCategory    = $extraParentCategory[0];
                    if(count($extraParentCategory) == 0) return FALSE;
                    $sortString             = $extraParentCategory[$this->category->sort_column_name];
                    $sortArray = explode(";", $sortString);
                    foreach($sortArray as $item) {
                        if($item == $ref_node['type'].$ref_node[$this->folder->primary_key_column_name]){
                            $newSortArray[] = $node['type'].$node[$this->folder->primary_key_column_name];
                        }
                        $newSortArray[] = $item;
                    }
                    $newSortString          = implode(';', $newSortArray);
                    $this->category->modifyExtraCategory(array($this->folder->sort_column_name => $newSortString), $extraFile['category_id']);
                    switch($node['type']){
                        case 'f':
                            return FALSE;
                            break;
                        case 'c':
                            return FALSE;
                            break;
                        case 'a':
                            $this->file->modifyExtraFile(array('folder_id'=>'0', 'category_id' => $extraFile['category_id']), $node[$this->folder->primary_key_column_name]);
                            break;
                    }
                }
                break;
        }
        
        return TRUE;
    }


    /*
     * ========================
     * function onMoveNodeInside
     * ========================
     * Перемещаем $node последним элементом, в $ref_node
     * В поле сортировки у $ref_node добавить в конец этого списка, $node
     */
    function onMoveNodeInside($node, $ref_node)
    {
        if( (count($node) == 0)  || (count($ref_node) == 0) ) die;

        switch($ref_node['type']){

            case 'f':
                $parentNode         = $this->folder->_getNodeById($ref_node[$this->folder->primary_key_column_name]);
                if(count($parentNode) == 0) return FALSE;
                $parentNodeExtra    = $this->folder->getExtraFolder($parentNode);
                $sortString         = $parentNodeExtra[$this->sort_column_name];
                $newSortString      = $sortString.$node['type'].$node[$this->folder->primary_key_column_name].';';
                $this->folder->modifyExtraFolder(array($this->folder->sort_column_name => $newSortString), $parentNode);
                switch($node['type']){
                    case 'f':
                        $this->folder->setNodeAsLastChild($node[$this->folder->primary_key_column_name], $parentNodeExtra[$this->folder->primary_key_column_name]);
                        break;
                    case 'c':
                        $this->category->modifyExtraCategory(array('folder_id' => $ref_node[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>$ref_node[$this->folder->primary_key_column_name], 'category_id' => '0'), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'c':
                if($node['type'] != 'a') return FALSE;
                $extraParentCategory    = $this->category->getExtraCategory($ref_node[$this->folder->primary_key_column_name]);
                if(count($extraParentCategory) == 0) return FALSE;
                $extraParentCategory    = $extraParentCategory[0];
                if(count($extraParentCategory) == 0) return FALSE;
                $sortString             = $extraParentCategory[$this->category->sort_column_name];
                $newSortString          = $sortString.$node['type'].$node[$this->folder->primary_key_column_name].';';
                $this->category->modifyExtraCategory(array($this->folder->sort_column_name => $newSortString), $ref_node[$this->folder->primary_key_column_name]);
                switch($node['type']){
                    case 'f':
                        return FALSE;
                        break;
                    case 'c':
                        return FALSE;
                        break;
                    case 'a':
                        $this->file->modifyExtraFile(array('folder_id'=>'0', 'category_id' => $ref_node[$this->folder->primary_key_column_name]), $node[$this->folder->primary_key_column_name]);
                        break;
                }
                break;

            case 'a':
                return FALSE;
                break;
        }

        return TRUE;
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
        //print_r ($parentSortAsString);
        if(strlen($parentSortAsString) == 0) return $mas;

        $parentSortAsArray = explode(';', $parentSortAsString);
        //print_r ($parentSortAsArray);
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
                        'icon'      => _icon('folder'),
                        'url'       => 'admin/place/content/folder'
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
                        'icon'      => _icon('folder_brick'),
                        'url'       => 'admin/place/content/category'
                    ));
                    
                    break;

                // File
                case 'a':
                    $extraCurrentFile = $this->file->getExtraFile($next[$this->folder->primary_key_column_name]);
                    $extraCurrentFile = $extraCurrentFile[0];

                    //если изображение то вставляем вместо иконки уменьшеную копию изображения
                    if ($extraCurrentFile['type']=='photo')
                    {
                    $Photo=$this->photo_model->extra(array('file_id'=>$extraCurrentFile['id']));
                    foreach ($Photo as $_photo)
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => '<img src="/uploads/photo/mini/mini_'.$_photo['file_name'].'" align="absmiddle" style="margin: 0 5px 0 0;" />',
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file',
                        'rel'       => 'dialog'
                    ));
                    } else {
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file',
                        'rel'       => 'dialog'
                    ));
                    }

                /*    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file'
                    ));*/
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
        if(count($treeArray) == 0) return '';
        
        $html = "<ul ".$param.">";

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
            $html .= anchor(
                $item['url'],
                $item['icon'].$item['title'],
                'item="'.$item['item_type'].':'.$item[$this->folder->primary_key_column_name].'" title="'.$item['title'].'"'
            );

            if( isset($item['children']) && (count($item['children']) > 0) ) {
                $html .= $this->parseArrayAsHTML($item['children']);
            }
        }
        
        $html .= "</ul>";
        //print_r ($html);
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
                    
                    //если изображение то вставляем вместо иконки уменьшеную копию изображения
                    if ($extraCurrentFile['type']=='photo')
                    {
                    $Photo=$this->photo_model->extra(array('file_id'=>$extraCurrentFile['id']));

		    foreach ($Photo as $_photo)
		    {
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      =>'<img src="/uploads/photo/mini/mini_'.$_photo['file_name'].'" align="absmiddle" />',  //$this->_icon( $extraCurrentFile['type'] ),
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file',
                        'rel'       => 'dialog'
                    ));
                    }
                    } else {
                    $mas[] = array_merge($extraCurrentFile, array(
                        'item_type' => 'a',
                        'icon'      => $this->_icon( $extraCurrentFile['type'] ),
                        'url'       => 'admin/'.$extraCurrentFile['type'].'/'.$extraCurrentFile['type'].'/file',
                        'rel'       => 'dialog'
                    ));
                    }
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
        $mas = [];

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
