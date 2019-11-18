<?php
class Municipal_model extends Model
{
    var $TABLES;       
    var $DAY;       
    var $MONTH;
    var $HOUR;       
    var $MINUTE;       
    
    function Municipal_model()
    {
        parent::Model();
        $this->TABLES = $this->module->config('municipal', 'TABLES');
        $this->DAY = $this->module->config('municipal', 'day');
        $this->MONTH = $this->module->config('municipal', 'month');
        $this->HOUR = $this->module->config('municipal', 'hour');
        $this->MINUTE = $this->module->config('municipal', 'minute');
        
    }
    
    function parseSectionsAsTree($mas = array(), $parent_id = 0)
    {
        $returnArray = array();
        
        foreach($mas as $i){
            if($i['parent_id'] == $parent_id){
                $i['children'] = $this->parseSectionsAsTree($mas, $i['id']);
                
                $returnArray[] = $i;
            }
        }
        
        return $returnArray;
    }
    
    /**
    * Возвращает разделы нуменклатурного справочника в виде dropdown
    * 
    * @name function parseSectionsAsDropdown 
    * @param array $sectionsTree
    * @param integer $parent_id
    * @param string $attr
    */
    function parseSectionsAsDropdown($sectionsTree = array(), $parent_id = 0, $attr = '')
    {
        $attr .= ' onchange="$(this).parent().find(\'div\').find(\'select\').val(0).hide();$(\'select[name=section[\'+$(this).val()+\']]\').show();"';
        $html = '';
        $mas[] = '';
        foreach($sectionsTree as $section){
            $mas[$section['id']] = $section['title'];
            if( (isset($section['children'])) && (count($section['children']) > 0) ){
                $html .= $this->parseSectionsAsDropdown(@$section['children'], @$section['id'], 'style="display:none;"'.$attr);
            }  
        }
        
        return form_dropdown('section['.$parent_id.']', $mas, null,$attr).'<div class="section['.$parent_id.']">'.$html.'</div>'; 
        
                    
    }
    
    
    /**
    * Возвращает уровни нуменклатурного справочника
    * 
    * @param mixed $sectionAsString
    * @return string
    */
    function getSectionsTitleAsPath($sectionAsString = '')
    {
        $path = array();
        
        if($sectionAsString == '') return FALSE;
        
        $sectionAsArray = explode(';',$sectionAsString); if(count($sectionAsArray) == 0) return FALSE;
        
        foreach($sectionAsArray as $sectionLevel){
            
            if(strlen($sectionLevel) < 3) continue;
            
            $section = explode(':',$sectionLevel);
            
            $parentSection = $section[0];    
            $valueSection = $section[1];
            
            $CI = &get_instance();
            
            $extraSection = $CI->nomenclatureDirectory_model->extra(array('id' => $valueSection));
            
            $path[] = $extraSection[0]['title'];    
        }
        
        return implode(' / ',$path);     
    }
    
    function getSections($where = array())
    {
        if(count($where) == 0) {
            return $this->db->get($this->TABLES['Sections'])->result_array();
        }
        
        return $this->db->where($where)->get($this->TABLES['Sections'])->result_array();        
    }
    
    
    
    /**
    * Возвращает файлы у документа
    * 
    * @name function getFiles
    * @param array $param = array(
    *   type => тип документа к которому прикремлены файлы, 
    *   id => идентификатор документа
    * )
    * @return Array(0 => (array) extraFile, 1 => (array) extraFile, 2 => ...... ) - Файлы
    */
    function getFiles($param = array())
    {
        $type = $param['type']; if(!is_string($type)) return FALSE;
        $did = $param['did'];  if(!is_numeric($did)) return FALSE;    
        
        return $this->db
                    ->where(array('type' => $type, 'did' => $did))
                    ->get($this->TABLES['Files'])
                    ->result_array();
    }
    
    /**
    * Добавляет файл к документу
    * 
    * @name function addFile
    * @param mixed $param Array(
    *   type => тип документа к которому прикрепляем файлы,
    *   id => идентификатор документа,
    *   title => название файла,
    *   desc => описание файла
    * )
    * @param mixed $fileParam Array(
    *   file_name => Имя загруженного файла, включая расширение.
    *   file_type => MIME-тип файла
    *   file_path => Абсолютный путь к файлу на сервере.
    *   full_path => Абсолютный путь до файла на сервере, включая имя файла
    *   raw_name => Имя файла без расширения
    *   orig_name => Первоначальное имя файла. Используется только при включенной опции encrypted_name.
    *   file_ext => Расширение файла с точкой
    *   file_size => Размер файла в килобайтах
    *   is_image => Проверка на предмет является ли файл изображением. 1 = изображение. 0 = нет.
    *   image_width => Ширина изображения.
    *   image_heigth => Высота изображения
    *   image_type => Тип изображения. Как правило - расширение файла без точки.
    *   image_size_str => Строка, включающая в себя параметры width и height. Полезно использовать внутри тэга img.
    * )
    * @return Boolean
    */
    function addFile($param = array(), $fileParam = array())
    {
        $type = $param['type']; if(!isset($type)) return FALSE;
        $did = $param['did']; if(!isset($did)) return FALSE;
        $desc = $param['desc']; if(!isset($desc)) return FALSE;
        $title = $param['title']; if(!isset($title)) return FALSE;
        
        $file_name = $fileParam['file_name']; if(!isset($file_name)) return FALSE;
        $file_type = $fileParam['file_type']; if(!isset($file_type)) return FALSE;
        $file_path = $fileParam['file_path']; if(!isset($file_path)) return FALSE;
        $full_path = $fileParam['full_path']; if(!isset($full_path)) return FALSE;
        $raw_name = $fileParam['raw_name']; if(!isset($raw_name)) return FALSE;
        $orig_name = $fileParam['orig_name']; if(!isset($orig_name)) return FALSE;
        $file_ext = $fileParam['file_ext']; if(!isset($file_ext)) return FALSE;
        $file_size = $fileParam['file_size']; if(!isset($file_size)) return FALSE;
        $is_image = $fileParam['is_image']; if(!isset($is_image)) return FALSE;
        $image_width = isset($fileParam['image_width'])?$fileParam['image_width']:''; if(!isset($image_width)) return FALSE;
        $image_heigth = isset($fileParam['image_heigth'])?$fileParam['image_heigth']:''; if(!isset($image_heigth)) return FALSE;
        $image_type = isset($fileParam['image_type'])?$fileParam['image_type']:''; if(!isset($image_type)) return FALSE;
        $image_size_str = isset($fileParam['image_size_str'])?$fileParam['image_size_str']:''; if(!isset($image_size_str)) return FALSE;
        
        $this->db->insert($this->TABLES['Files'], array(
            'type' => $type,
            'did' => $did,
            'desc' => $desc,
            'title' => $title,
            'file_name' => $file_name,
            'file_type' => $file_type,
            'file_path' => $file_path,
            'full_path' => $full_path,
            'raw_name' => $raw_name,
            'orig_name' => $orig_name,
            'file_ext' => $file_ext,
            'file_size' => $file_size,
            'is_image' => $is_image,
            'image_width' => $image_width,
            'image_heigth' => $image_heigth,
            'image_type' => $image_type,
            'image_size_str' => $image_size_str,
        ));
        
        return $this->db->insert_id();            
    }
    
    /**
    *  Удаляет файл  
    * 
    * @name function deleteFile
    * @param mixed $fileId - Ид файла
    * @return boolean
    */
    function deleteFile($fileId)
    {
        if(!is_numeric($fileId)) return FALSE;
        
        return $this->db->delete($this->TABLES['Files'], array('id' => $fileId));    
    }
    
    
    /**
    * Возвращает информацию о файле
    * 
    * @name function getExtraFile
    * @param mixed $fileId - id файла
    * @return array()
    */
    function getExtraFile($fileId)
    {
        if(!is_numeric($fileId)) return FALSE;    
        
        return $this->db
                    ->where(array('id' => $fileId))
                    ->get($this->TABLES['Files'])
                    ->result_array();
    }
    
    
    /**
    * Изменяет информацию о файле
    * 
    * @name function updateExtraFile
    * @param mixed $extrafields
    * @param mixed $where
    * @return boolean
    */
    function updateExtraFile($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return FALSE;    
        if(count($where) == 0) return FALSE;
        
        return $this->db->update($this->TABLES['Files'], $extrafields, $where);    
    } 
}
?>
