<?php
class Display
{
	var $CI;

	var $labels;
	
	function Display()
	{
		$this->CI = &get_instance();
		
		$this->CI->load->config('templates');
		
		$this->labels = $this->CI->config->item('labels');
	}
	
	function _include($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    
	    $label = $this->labels['include'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _right($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    
	    $label = $this->labels['right'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _left($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    
	    $label = $this->labels['left'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _header($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    
	    $label = $this->labels['header'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}

	function _printer($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    //echo 'work';
	    
	    $label = $this->labels['printer'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _versite($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    //echo 'work';
	    
	    $label = $this->labels['versite'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _fontpanel($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    //echo 'work';
	    
	    $label = $this->labels['fontpanel'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}

	function _central_menu($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    //echo 'work';
	    
	    $label = $this->labels['central_menu'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}

	function _minimap($string = '')
	{
		//print_R ($string);
		
	    if(strlen($string) == 0) return FALSE;
	    //echo 'work';
	    
	    $label = $this->labels['minimap'];
	    
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _footer($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
        
        $label = $this->labels['footer'];
        if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}

	function _tourface($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
        
        $label = $this->labels['tourface'];
        if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}
	
	function _content($string = '')
	{
	    if(strlen($string) == 0) return FALSE;
	    
	    $label = $this->labels['content'];
	    if(strlen($label) == 0) return FALSE;
	    
	    $this->CI->output->set_output( 
            str_replace(
                $label, 
                $string.$label, 
                $this->CI->output->get_output()
            ) 
        );
        
        return TRUE;
	}

    function _display()
    {
        $this->CI->output->_display();
        return TRUE;
    }
}
?>
