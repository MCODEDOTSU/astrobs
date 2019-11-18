<?php
class MY_Router extends CI_Router
{
    function _set_admin_request($segments = array())
    {
        $segments = $this->_validate_admin_request($segments);
        
        if (count($segments) == 0)
        {
            return;
        }
                        
        $this->set_class($segments[0]);
        
        if (isset($segments[1]))
        {
            // A standard method request
            $this->set_method($segments[1]);
        }
        else
        {
            // This lets the "routed" segment array identify that the default
            // index method is being used.
            $segments[1] = 'index';
        }
        
        // Update our "routed" segment array to contain the segments.
        // Note: If there is no custom routing, this array will be
        // identical to $this->uri->segments
        $this->uri->rsegments = $segments;
    }
    
    function _validate_admin_request($segments)
    {
        
        // Не понимаю зачем этот код)) а, понял, да, он нужен)
        if($segments[0] == $this->default_controller)
        {
            // Does the requested controller exist in the root folder?
            if (file_exists('administrator/'.APPPATH.'controllers/'.$segments[0].EXT))
            {
                $this->set_directory('../../administrator/'.APPPATH.'controllers');
                return $segments;
            }
        }
        
        // Первый сегмент всегда должен быть и указывает только на используемый модуль 
        $pathToModulesFolder = 'administrator/extensions/modules/'.$segments[0].'/';
        
        if (is_dir($pathToModulesFolder))
        {
            // Set the directory and remove it from the segment array
            $this->set_directory('../../'.$pathToModulesFolder.'controllers');
            $segments = array_slice($segments, 1);
            
            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( !file_exists($pathToModulesFolder.'controllers/'.$segments[0].EXT))
                {
                    show_404($pathToModulesFolder.'controllers/'.$segments[0]);
                }
            }
            else
            {
                // Can't find the requested controller...
                show_error("Can't find the requested controller...");
            }
            
            return $segments;     
        }
        
        // Can't find the requested controller...
        show_404($segments[0]);
    }
    
    function _set_public_request($segments = array())
    {
        $segments = $this->_validate_public_request($segments);
        
        if (count($segments) == 0)
        {
            return;
        }
                        
        $this->set_class($segments[0]);
        
        if (isset($segments[1]))
        {
            // A standard method request
            $this->set_method($segments[1]);
        }
        else
        {
            // This lets the "routed" segment array identify that the default
            // index method is being used.
            $segments[1] = 'index';
        }
        
        // Update our "routed" segment array to contain the segments.
        // Note: If there is no custom routing, this array will be
        // identical to $this->uri->segments
        $this->uri->rsegments = $segments;
    }
    
    function _validate_public_request($segments)
    {
        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }
        
        // Первый сегмент всегда должен быть и указывает только на используемый модуль 
        $pathToModulesFolder = 'extensions/modules/'.$segments[0].'/';
        
        if (is_dir($pathToModulesFolder))
        {
            // Set the directory and remove it from the segment array
            $this->set_directory('../../'.$pathToModulesFolder.'controllers');
            $segments = array_slice($segments, 1);
            
            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( !file_exists($pathToModulesFolder.'controllers/'.$segments[0].EXT))
                {
                    show_404($pathToModulesFolder.'controllers/'.$segments[0]);
                }
            }
            else
            {
                // Can't find the requested controller...
                show_error("Can't find the requested controller...");
            }
            
            return $segments;     
        }
        
        // Can't find the requested controller...
        show_404($segments[0]);
    }
}
?>
