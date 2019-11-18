<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config
{
    var $is_loaded_modules   = array();
    var $is_loaded_plugins   = array();
    
    var $config_modules      = array();
    var $config_plugins      = array();
    /**
    * Load Template Config File
    *
    * @access    public
    * @param    string    the config file name
    * @return    boolean    if the file was loaded correctly
    */
    function template_load($type = '', $file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        
        if($type == 'administrator') 
        {
            $template_name = $this->config['template_administrator']; 
            
            $template_folder = 'administrator/'.$this->config['template_folder'];
        }
        else
        {
            $template_name = $this->config['template_public'];
        
            $template_folder = $this->config['template_folder'];    
        }
        
        
        $file = $template_name;
        
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);

        if (in_array($file, $this->is_loaded, TRUE))
        {
            return TRUE;
        }
        
        if ( ! file_exists($template_folder.'/'.$template_name.'/config/'.$file.EXT))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('The configuration file '.$file.EXT.' does not exist.');
        }

        include($template_folder.'/'.$template_name.'/config/'.$file.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
        }

        if ($use_sections === TRUE)
        {
            if (isset($this->config[$file]))
            {
                $this->config[$file] = array_merge($this->config[$file], $config);
            }
            else
            {
                $this->config[$file] = $config;
            }
        }
        else
        {
            $this->config = array_merge($this->config, $config);
        }

        $this->is_loaded[] = $file;
        unset($config);

        log_message('debug', 'Config file loaded: config/'.$file.EXT);
        
        return TRUE;
    } 
    
    function module_config($type = '', $module, $file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
    
        if($type == 'administrator')
        {
            $path = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $path = 'extensions/modules/'.$module.'/';
        }   
        

        if ( ! file_exists($path.'config/'.$file.EXT))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('The configuration file '.$file.EXT.' does not exist.');
        }
    
        include($path.'config/'.$file.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
        }
        
        if(count($this->config_modules) == 0) $this->config_modules[$module] = array();
        
        if(count($this->config_modules[$module]) == 0)
        {
            $this->config_modules[$module] = $config;
        }
        else
        {
            $this->config_modules[$module] = array_merge($this->config_modules[$module], $config);    
        }
        
        log_message('debug', 'Config file loaded: config/'.$file.EXT);
    }

    function module($type = 'public', $module, $file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
    
        if($type == 'administrator')
        {
            $path = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $path = 'extensions/modules/'.$module.'/';
        }   
        
        // Если этот модуль уже загружен то возвращаем TRUE
        if (in_array($module, $this->is_loaded_modules, TRUE))
        {
            return TRUE;
        }

        if ( ! file_exists($path.'config/'.$file.EXT))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('The configuration file '.$file.EXT.' does not exist.');
        }
    
        include($path.'config/'.$file.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
        }

        
        
        $this->config_modules[$module] = $config;
        
        $this->is_loaded_modules[] = $module;
        unset($config);

        log_message('debug', 'Config file loaded: config/'.$file.EXT);
        return TRUE;
    }
    
    function module_item($module, $item, $index = '')
    {    
        
        if ($index == '')
        {    
            if ( ! isset($this->config_modules[$module][$item]))
            {
                return FALSE;
            }

            $pref = $this->config_modules[$module][$item];
        }
        else
        {
            if ( ! isset($this->config_modules[$module][$item]))
            {
                return FALSE;
            }

            if ( ! isset($this->config_modules[$module][$item][$index]))
            {
                return FALSE;
            }

            $pref = $this->config_modules[$module][$item][$index];
        }

        return $pref;
    }
    
    function plugin($type = 'public', $plugin, $file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
    
        if($type == 'administrator')
        {
            $path = 'administrator/extensions/plugins/'.$plugin.'/';
        }
        
        if($type == 'public')
        {
            $path = 'extensions/plugins/'.$plugin.'/';
        }   
        
        // Если этот модуль уже загружен то возвращаем TRUE
        if (in_array($plugin, $this->is_loaded_plugins, TRUE))
        {
            return TRUE;
        }

        if ( ! file_exists($path.'config/'.$file.EXT))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('The configuration file '.$file.EXT.' does not exist.');
        }
    
        include($path.'config/'.$file.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
        }

        
        
        $this->config_plugins[$plugin] = $config;
        
        $this->is_loaded_plugins[] = $plugin;
        unset($config);

        log_message('debug', 'Config file loaded: config/'.$file.EXT);
        return TRUE;
    }
    
    function plugin_item($plugin, $item, $index = '')
    {    
        
        if ($index == '')
        {    
            if ( ! isset($this->config_plugins[$plugin][$item]))
            {
                return FALSE;
            }

            $pref = $this->config_plugins[$plugin][$item];
        }
        else
        {
            if ( ! isset($this->config_plugins[$plugin][$item]))
            {
                return FALSE;
            }

            if ( ! isset($this->config_plugins[$plugin][$item][$index]))
            {
                return FALSE;
            }

            $pref = $this->config_plugins[$plugin][$item][$index];
        }

        return $pref;
    }
}

?>