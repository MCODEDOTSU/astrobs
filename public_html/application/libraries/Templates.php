<?php
class Templates
{
    var $template_name;
    
    var $template_public;
    
    var $template_administrator;
    
    var $template_folder;
    
    // Page title    
    var $page_title; 
    
    // Meta tag array  
    var $meta_tags = array();   
    
    // Style and JavaScript include file 
    var $assets = array();   
    
    // JS code   
    var $variables = array();     
    
    // Constructor
    function Templates()
    {
        // Load CI instance
        $this->CI = &get_instance();
        
        $config = &get_config();
        
        $this->template_public = $config['template_public'];

        $this->template_administrator = $config['template_administrator'];
        
        $this->template_folder = $config['template_folder'];
        
        
    }
    
    function output_metatags() {
        $this->meta_tags = $this->CI->config->item('meta_tags');
        if(count($this->meta_tags) == 0) return '';
        
        $output = '';
        foreach($this->meta_tags as $name => $tag)
        {
            $output .= "<meta ".$tag['type']."=\"".$name."\" content =\"".$tag['content']."\" />\n";
        }
        
        return $output;                             
    }
    
    function output_pagetitle()
    {
        $this->page_title = $this->CI->config->item('page_title');
        
        if(strlen($this->page_title) == 0) 
        {
            return '';
        }    
        else 
        {
            return $this->page_title;
        }       
    }
    
    function output_variables()
    {
        $this->meta_tags = $this->CI->config->item('variables');
        if(count($this->variables) == 0) return '';
        
        $output = "<script type=\"text/javascript\">\n<!--\n";
        foreach($this->variables as $type => $code)
        {
            switch($type)
            {
                case 'JS':
                    $output .= $code;
                    break;
            }
            
        }
        $output .= "// -->\n</script>\n";
        
        return $output;
    }
    
    function output_assets()
    {
        $this->assets = $this->CI->config->item('assets');
        if(count($this->assets) == 0) return '';
        
        $output = '';
        foreach($this->assets as $type => $file)
        {
            switch($type)
            {
                case 'js':
                    $output .= $this->_include_js($file);
                    break;
                case 'css':
                    $output .= $this->_include_css($file);
                    break;    
            }
        }
        
        return $output;
    }
    
    function output_special_assets () {
    	$this->assets = $this->_get_version();
    	if (count($this->assets) == 0) return false;
    	$output = '';
    	foreach($this->assets as $type => $file)
        {
            switch($type)
            {
                case 'js':
                    $output .= $this->_include_js($file);
                    break;
                case 'css':
                    $output .= $this->_include_css($file);
                    break;    
            }
        }
        
        return $output;
    }
    
    function body_classes () {
    	$ses = $this->CI->session;
    	
    	if ($ses->userdata('version_site') != 'ver2') return false;
    	$color = 'color1';
    	$font = '100%';
    	
    	if ($ses->userdata('site_color') !== false) $color = $ses->userdata('site_color');
    	if ($ses->userdata('site_font') !== false) $font = $ses->userdata('site_font');
    	
    	return 'class="'.$color.'" style="font-size:'.$font.';"';
    }
    
    function _get_version () {
    	$versions = $this->CI->config->item('special_assets');
    	if ($this->CI->session->userdata('version_site') === false) {
    		$this->CI->session->set_userdata(array('version_site' => 'ver1'));
    		return $versions['ver1'];
    	}
    	$ver = $this->CI->session->userdata('version_site');
    	return $versions[$ver];
    }
    
    function _include_js($Files)
    {
        $output = '';
        foreach($Files as $file)
        {
            $output .= '<script type="text/javascript" src="'.base_url().$this->template_folder.'/'.$this->template_name.'/js/'.$file.'"></script>'."\n";
        }
        return $output;
    }
    
    function _include_css($Files)
    {
        $output = '';
        foreach($Files as $file)
        {
            $output .= '<link rel="stylesheet" type="text/css" href="'.base_url().$this->template_folder.'/'.$this->template_name.'/css/'.$file.'" />'."\n";    
        }
        return $output;
    }
  
    function _get_template($type = '', $file = 'index.php')
    {
        if($type == 'administrator')
        {
            $this->_administrator();
        }
        
        if($type == 'public')
        {
            $this->_public();    
        }
        
        return $this->CI->load->template_view($file, $this->template_folder.'/'.$this->template_name.'/');
    }
    
    function _public()
    {
        $this->template_name = $this->template_public;
        
        // Load Config file for current template
        $this->CI->load->template_config();
    }
    
    function _administrator()
    {
        $this->template_name = $this->template_administrator;
        
        $this->fetch_template_folder('administrator/'.$this->template_folder);
        
        // Load Config file for current template
        $this->CI->load->template_config('administrator');
    }
    
    function fetch_template_folder($path = '')
    {
        $this->template_folder = $path;           
    } 
}  
?>
