<?php
class Search_model extends Model
{
    var $moduleName = 'search';
    
    function Search_model()
    {
        parent::Model();
    }
    
    function search_block()
    {
    	if ($this->session->userdata('version_site') == 'ver2') return false;
    	$this->lang->setModule ('search');
        return $this->module->parse($this->moduleName, 'block.php', array(
            'search' => form_input('search', '', 'id="search_text" placeholder="Поиск по сайту"'),
            'form_open' => form_open($this->moduleName.'/'.$this->moduleName),
            'form_close' => form_close()
        ), TRUE);
    }
    
    function searchInArticles($keywords = '')
    {
        if($keywords == '') return FALSE;
        
        return $this->db->from('th_article')->like('body', $keywords)->get()->result_array();
        
    }
    
    function searchInNews($keywords = '')
    {
        if($keywords == '') return FALSE;
        
        return $this->db->from('th_news')->like('body', $keywords)->get()->result_array();    
    }
    
    function parseText($string = '',$needle = '')
    {
        if($string == '') return FALSE;
        if($needle == '') return FALSE;
        
        //string strpos(string haystack, string needle[, int offset]). Эта функция обеспечивает действие, обратное функции substr. Т.е. она возвращает позицию в строке haystack, в которой найдена переданная ей подстрока needle.
        $startKeyword = strpos($string, $needle);
        
        $startKeyword;
        
        $s = ($startKeyword == 0)? 0: $startKeyword; 
        $e = 150;
        
        
        //string substr(string string, int start[, int length]). Эта функция возвращает часть строки. Первый аргумент – исходная строка; второй – положение в строке, которую надо вернуть, первого символа (отсчет начинается с нуля); третий – длина строки в символах, которую надо вернуть. Если третий аргумент не указан, то возвращается вся оставшаяся часть строки.
        $text = _substr(substr($string, $s), $e, 10);
        
        return $text;
    }
    
    function delTags($string = '')
    {
        if($string == '') return FALSE; 
        
        return strip_tags($string);
    }
}
?>
