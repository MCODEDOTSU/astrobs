<?php
class MY_Loader extends CI_Loader 
{
    var $_ci_modules = array();
    
    function template_view($file, $path)
    {
        return $this->_ci_load(array(
            '_ci_return' => TRUE,
            '_ci_path'   => $path.$file
        ));    
    }
    
    function template_config($type = '', $file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $CI =& get_instance();
        
        $CI->config->template_load($type, $file, $use_sections, $fail_gracefully);
    }
    
    function module_model($type = '', $module = '', $model = '', $name = '', $db_conn = FALSE)
    {        
        
        
        if (is_array($model))
        {
            foreach($model as $babe)
            {
                $this->module_model($babe);    
            }
            return;
        }

        if ($model == '')
        {
            return;
        }
    
        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (strpos($model, '/') === FALSE)
        {
            $path = '';
        }
        else
        {
            $x = explode('/', $model);
            $model = end($x);            
            unset($x[count($x)-1]);
            $path = implode('/', $x).'/';
        }
    
        $model = str_replace('_model', '', str_replace(EXT, '', $model)).'_model';
    
        if ($name == '')
        {
            $name = $model;
        }
        
        if (in_array($name, $this->_ci_models, TRUE))
        {
            return;
        }
        
        $CI =& get_instance();
        
        if (isset($CI->$name))
        {
            show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
        }
    
        $model = strtolower($model);
        
        
        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $pathToFolder = 'extensions/modules/'.$module.'/';
        }
            
        if ( ! file_exists($pathToFolder.'models/'.$path.$model.EXT))
        {
            echo $pathToFolder.'models/'.$path.$model.EXT;
            show_error('Unable to locate the model you have specified: '.$model);
        }
                
        if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
        {
            if ($db_conn === TRUE)
                $db_conn = '';
        
            $CI->load->database($db_conn, FALSE, TRUE);
        }
    
        if ( ! class_exists('Model'))
        {
            load_class('Model', FALSE);
        }

        require_once($pathToFolder.'models/'.$path.$model.EXT);

        $this->_ci_modules[$module]['models'][$model] = $name;
        
        $model = ucfirst($model);
        $CI->$name = new $model();
        $CI->$name->_assign_libraries();
        
        $this->_ci_models[] = $name;
        
            
    } 
    
    function module_library($type = '', $module = '', $library = '', $params = NULL, $object_name = NULL)
    {
        if ($library == '')
        {
            return FALSE;
        }

        if ( ! is_null($params) AND ! is_array($params))
        {
            $params = NULL;
        }

        if (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->_ci_load_module_class($type, $module, $class, $params, $object_name);
            }
        }
        else
        {
            $this->_ci_load_module_class($type, $module, $library, $params, $object_name);
        }
        
        $this->_ci_assign_to_models();
    }
    
    function module_view($type = '', $module = '', $view = '', $vars = array())
    {
        $_ci_ext = pathinfo($view, PATHINFO_EXTENSION);
        $_ci_file = ($_ci_ext == '') ? $view.EXT : $view;
        
        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $pathToFolder = 'extensions/modules/'.$module.'/';
        }
            
        $this->_ci_modules[$module]['view'][$_ci_file] = $this->_ci_load(array(
        
            '_ci_return' => TRUE,
            '_ci_path'   => $pathToFolder.'views/'.$_ci_file
            
        ));
        
        return TRUE;
    }
    
    function module_language($type = '', $module = '', $file = array(), $lang = '')
    {
        $CI =& get_instance();

        if ( ! is_array($file))
        {
            $file = array($file);
        }

        foreach ($file as $langfile)
        {    
            $CI->lang->module_load($type, $module, $langfile, $lang);
        }
    }   
    
    function module_config($type, $module, $file = '')
    {
        $CI =& get_instance();
        $CI->config->module_config($type, $module, $file);
    } 
    
    function _ci_load_module_class($type = '', $module = '', $class = '', $params = NULL, $object_name = NULL)
    {    
        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $pathToFolder = 'extensions/modules/'.$module.'/';
        }
        
        // Get the class name, and while we're at it trim any slashes.  
        // The directory path can be included as part of the class name, 
        // but we don't want a leading slash
        $class = str_replace(EXT, '', trim($class, '/'));
    
        // Was the path included with the class name?
        // We look for a slash to determine this
        $subdir = '';
        if (strpos($class, '/') !== FALSE)
        {
            // explode the path so we can separate the filename from the path
            $x = explode('/', $class);    
            
            // Reset the $class variable now that we know the actual filename
            $class = end($x);
            
            // Kill the filename from the array
            unset($x[count($x)-1]);
            
            // Glue the path back together, sans filename
            $subdir = implode($x, '/').'/';
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class)
        {
            // Lets search for the requested library file and load it.
            $is_duplicate = FALSE;        
            $path = $pathToFolder;    
            $filepath = $path.'libraries/'.$subdir.$class.EXT;
            
            // Does the file exist?  No?  Bummer...
            if ( ! file_exists($filepath))
            {
                continue;
            }
            
            // Safety:  Was the class already loaded by a previous call?
            if (in_array($filepath, $this->_ci_loaded_files))
            {
                // Before we deem this to be a duplicate request, let's see
                // if a custom object name is being supplied.  If so, we'll
                // return a new instance of the object
                if ( ! is_null($object_name))
                {
                    $CI =& get_instance();
                    if ( ! isset($CI->$object_name))
                    {
                        return $this->_ci_init_class($class, '', $params, $object_name);
                    }
                }
            
                $is_duplicate = TRUE;
                log_message('debug', $class." class already loaded. Second attempt ignored.");
                return;
            }
            
            include_once($filepath);
            
            
            $this->_ci_modules[$module]['libraries'][strtolower($class)] = strtolower($class);
            
            $this->_ci_loaded_files[] = $filepath;
            
            return $this->_ci_init_class($class, '', $params, $object_name);
        } // END FOREACH

        // One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '')
        {
            $path = strtolower($class).'/'.$class;
            return $this->_ci_load_class($path, $params);
        }
        
        // If we got this far we were unable to find the requested class.
        // We do not issue errors if the load call failed due to a duplicate request
        if ($is_duplicate == FALSE)
        {
            log_message('error', "Unable to load the requested class: ".$class);
            show_error("Unable to load the requested class: ".$class);
        }
    }   
    
    function module_js($type = '', $module = '', $file = '')
    {
        if($type == 'administrator')
        {
            $path = base_url().'administrator/extensions/modules/'.$module.'/js/'.$file;
            if(substr($file, 0, 7) == "http://") $path = $file;
        }
        
        if($type == 'public')
        {
            $path = base_url().'extensions/modules/'.$module.'/js/'.$file;
            if(substr($file, 0, 7) == "http://") $path = $file;
        }
        
        $output = '<script type="text/javascript" src="'.$path.'"></script>'."\n";
        
        $CI =& get_instance();
        
        $CI->output->set_output( 
            str_replace(
                '<th:include></th:include>',
                $output.'<th:include></th:include>',
                $CI->output->get_output()
            ) 
        );
    }
    
    function module_css($type = '', $module = '', $file = '')
    {
        if($type == 'administrator')
        {
            $path = base_url().'administrator/extensions/modules/'.$module.'/css/'.$file;
            if(substr($file, 0, 7) == "http://") $path = $file;
        }
        
        if($type == 'public')
        {
            $path = base_url().'extensions/modules/'.$module.'/css/'.$file;
            if(substr($file, 0, 7) == "http://") $path = $file;
        }
        
        $output = '<link rel="stylesheet" type="text/css" href="'.$path.'" />'."\n";
        
        $CI =& get_instance();
        $CI->output->set_output( 
            str_replace(
                '<th:include></th:include>',
                $output.'<th:include></th:include>',
                $CI->output->get_output()
            ) 
        );
        
    }
    
    function plugin_js($type = '', $plugin = '', $file = '')
    {
        if($type == 'administrator')
        {
            $path = base_url().'administrator/extensions/plugins/'.$plugin.'/js/'.$file;
        }
        
        if($type == 'public')
        {
            $path = base_url().'extensions/plugins/'.$plugin.'/js/'.$file;
        }
        
        $output = '<script type="text/javascript" src="'.$path.'"></script>'."\n";
        
        $CI =& get_instance();
        
        $CI->output->set_output( 
            str_replace(
                '<th:include></th:include>',
                $output.'<th:include></th:include>',
                $CI->output->get_output()
            ) 
        );
    }
    
    function plugin_css($type = '', $plugin = '', $file = '')
    {
        if($type == 'administrator')
        {
            $path = base_url().'administrator/extensions/plugins/'.$plugin.'/css/'.$file;
        }
        
        if($type == 'public')
        {
            $path = base_url().'extensions/plugins/'.$plugin.'/css/'.$file;
        }
        
        $output = '<link rel="stylesheet" type="text/css" href="'.$path.'" />'."\n";
        
        $CI =& get_instance();
        $CI->output->set_output( 
            str_replace(
                '<th:include></th:include>',
                $output.'<th:include></th:include>',
                $CI->output->get_output()
            ) 
        );
        
    }
    
	function plugin_php($type = '', $plugin = '', $file = '')
	{
        $plugin = strtolower(str_replace(EXT, '', $plugin));

        if (isset($this->_ci_plugins[$plugin]))
        {
            return TRUE;
        }

        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/plugins/'.$plugin.'/php/';
        }

        if($type == 'public')
        {
            $pathToFolder = 'extensions/plugins/'.$plugin.'/php/';
        }

        if (file_exists($pathToFolder.$plugin.EXT))
        {
            include_once($pathToFolder.$plugin.EXT);
        }
        else
        {
            
            show_error('Unable to load the requested file: plugins/'.$plugin.EXT);
        }

        $this->_ci_plugins[$plugin] = TRUE;
	}

    function plugin_model($type = '', $plugin = '', $name = '', $db_conn = FALSE)
    {

        if ($name == '')
        {
            return;
        }

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (strpos($name, '/') === FALSE)
        {
            $path = '';
        }
        else
        {
            $x = explode('/', $name);
            $name = end($x);
            unset($x[count($x)-1]);
            $name = implode('/', $x).'/';
        }

        $name = str_replace('_model', '', str_replace(EXT, '', $name)).'_model';

        

        if (in_array($name, $this->_ci_models, TRUE))
        {
            return;
        }

        $CI =& get_instance();

        if (isset($CI->$name))
        {
            show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
        }

        $name = strtolower($name);


        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/plugins/'.$plugin.'/models/';
        }

        if($type == 'public')
        {
            $pathToFolder = 'extensions/plugins/'.$plugin.'/models/';
        }

        if ( ! file_exists($pathToFolder.$path.$name.EXT))
        {
            show_error('Unable to locate the model you have specified: '.$plugin);
        }

        if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
        {
            if ($db_conn === TRUE)
                $db_conn = '';

            $CI->load->database($db_conn, FALSE, TRUE);
        }

        if ( ! class_exists('Model'))
        {
            load_class('Model', FALSE);
        }

        require_once($pathToFolder.$path.$plugin.EXT);

        $this->_ci_plugins[$plugin]['models'][$plugin] = $name;

        $class = ucfirst($name);

        $CI->$name = new $class();
        $CI->$name->_assign_libraries();

        $this->_ci_models[] = $name;


    }

    function plugin_library($type = '', $plugin = '', $library = '', $params = NULL, $object_name = NULL)
    {
        if ($library == '')
        {
            return FALSE;
        }

        if ( ! is_null($params) AND ! is_array($params))
        {
            $params = NULL;
        }

        if (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->_ci_load_plugin_class($type, $plugin, $class, $params, $object_name);
            }
        }
        else
        {
            $this->_ci_load_plugin_class($type, $plugin, $library, $params, $object_name);
        }

        $this->_ci_assign_to_models();
    }

    function _ci_load_plugin_class($type = '', $plugin = '', $class = '', $params = NULL, $object_name = NULL)
    {
        if($type == 'administrator')
        {
            $pathToFolder = 'administrator/extensions/plugins/'.$plugin.'/';
        }

        if($type == 'public')
        {
            $pathToFolder = 'extensions/plugins/'.$plugin.'/';
        }

        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace(EXT, '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        $subdir = '';
        if (strpos($class, '/') !== FALSE)
        {
            // explode the path so we can separate the filename from the path
            $x = explode('/', $class);

            // Reset the $class variable now that we know the actual filename
            $class = end($x);

            // Kill the filename from the array
            unset($x[count($x)-1]);

            // Glue the path back together, sans filename
            $subdir = implode($x, '/').'/';
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class)
        {
            // Lets search for the requested library file and load it.
            $is_duplicate = FALSE;
            $path = $pathToFolder;
            $filepath = $path.'libraries/'.$subdir.$class.EXT;

            // Does the file exist?  No?  Bummer...
            if ( ! file_exists($filepath))
            {
                continue;
            }

            // Safety:  Was the class already loaded by a previous call?
            if (in_array($filepath, $this->_ci_loaded_files))
            {
                // Before we deem this to be a duplicate request, let's see
                // if a custom object name is being supplied.  If so, we'll
                // return a new instance of the object
                if ( ! is_null($object_name))
                {
                    $CI =& get_instance();
                    if ( ! isset($CI->$object_name))
                    {
                        return $this->_ci_init_class($class, '', $params, $object_name);
                    }
                }

                $is_duplicate = TRUE;
                log_message('debug', $class." class already loaded. Second attempt ignored.");
                return;
            }

            include_once($filepath);


            $this->_ci_plugins[$plugin]['libraries'][strtolower($class)] = strtolower($class);

            $this->_ci_loaded_files[] = $filepath;

            return $this->_ci_init_class($class, '', $params, $object_name);
        } // END FOREACH

        // One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '')
        {
            $path = strtolower($class).'/'.$class;
            return $this->_ci_load_class($path, $params);
        }

        // If we got this far we were unable to find the requested class.
        // We do not issue errors if the load call failed due to a duplicate request
        if ($is_duplicate == FALSE)
        {
            log_message('error', "Unable to load the requested class: ".$class);
            show_error("Unable to load the requested class: ".$class);
        }
    }
} 
?>
