<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Parser extends CI_Parser
{
    function module_parse($module, $template, $data, $return = FALSE)
    {
        $CI =& get_instance();
        
        $template = $CI->load->module_view($module, $template, $data, TRUE);
        
        if ($template == '')
        {
            return FALSE;
        }
        
        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                $template = $this->_parse_pair($key, $val, $template);        
            }
            else
            {
                $template = $this->_parse_single($key, (string)$val, $template);
            }
        }
        
        if ($return == FALSE)
        {
            $CI->load->library('Extensions');
            
            $CI->extensions->append_content($template);
            
            $CI->extensions->content();
        }
        
        return $template;
    }  

}

?>