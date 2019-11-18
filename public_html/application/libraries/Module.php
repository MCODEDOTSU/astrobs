<?php
class Module
{
    var $CI;
    
    function Module()
    {
        $this->CI = &get_instance();
    }
         
    function view($module, $file)
    {
        $_ci_ext = pathinfo($file, PATHINFO_EXTENSION);
        $file = ($_ci_ext == '') ? $file.EXT : $file;
        
        return $this->CI->load->_ci_modules[$module]['view'][$file];
    }
    
    function language($module, $item)
    {
        return $this->CI->lang->language_modules[$module][$item];
    }
    
    function config($module, $item, $index = '')
    {
        if ($index == '')
        {    
            if ( ! isset($this->CI->config->config_modules[$module][$module][$item]))
            {
                return FALSE;
            }

            $pref = $this->CI->config->config_modules[$module][$module][$item];
        }
        else
        {
            if ( ! isset($this->CI->config->config_modules[$module][$module][$index]))
            {
                return FALSE;
            }

            if ( ! isset($this->CI->config->config_modules[$module][$module][$index][$item]))
            {
                return FALSE;
            }

            $pref = $this->CI->config->config_modules[$module][$module][$index][$item];
        }

        return $pref;
        
    }
    
    function parse($module, $item, $data = array(), $return = FALSE)
    {
        $this->CI->load->library('parser');
        
        $template = $this->view($module, $item);
        
        if ($template == '')
        {
            return FALSE;
        }
        
        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                $template = $this->CI->parser->_parse_pair($key, $val, $template);        
            }
            else
            {
                $template = $this->CI->parser->_parse_single($key, (string)$val, $template);
            }
        }
        
        if ($return == FALSE)
        {
            //$this->CI->load->library('Extensions');
            
            //$this->CI->extensions->set_content($template);
            
            //$this->CI->extensions->content();
            $this->CI->display->_content($template);
        }
        
        return $template;
    }
}  
?>
