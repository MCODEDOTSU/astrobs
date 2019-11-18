<?php
class Extensions
{
    var $modules = array();
    var $plugins = array();
    
    var $header;
    
    var $footer;
    
    var $left;
    
    var $right;
    
    var $content;

    var $printer;

    var $minimap;
    
    var $_loadded = array();
    
    var $type;
     
    function Extensions()
    {
        // Load CI instance
        $this->CI = &get_instance();
        
        $this->CI->load->config('extensions');
        
        //Получаем список модулей
        $this->modules = $this->CI->config->item('modules');
        
        //Получаем список плагинов
        $this->plugins = $this->CI->config->item('plugins');

        
    }
    
    // $type = 'public' or 'administrator'
    function load($type = 'public')
    {
        $this->type = $type;
        
        if(count($this->plugins) != 0) 
        {
            //Перебор списка плагинов
            foreach($this->plugins[$this->type] as $plugin)
            {
                //Загрузка конфига текущего плагина
                $this->CI->config->plugin($this->type, $plugin, $plugin);
                
                //Загружаем текущий плагин
                $this->load_plugin($plugin);
            }
        }
        
        if(count($this->modules) != 0) 
        {
            //Перебор списка модулей
            foreach($this->modules[$this->type] as $module)
            {
                //Загрузка конфига текущего модуля
                $this->CI->config->module($this->type, $module, $module);
                
                //Загружаем текущий модуль
                $this->load_module($module);
            }
        }
        
        
        
//        $this->_header();
//
//        $this->_footer();
//
//        $this->_left();
//
//        $this->_right();
            
    }
        
        
        
    /*
    * Loaded module
    */
    function load_module($module)
    {
        $includes = $this->CI->config->module_item($module, $module, 'includes');
       
        if(!$includes) return FALSE;
        
        foreach($includes as $type => $files)
        {
            switch($type)
            {
                case "config":
                    $this->_module_config($module, $files);
                    break;
                
                case "libraries":
                    $this->_module_libraries($module, $files);
                    break;
                    
                case "models":
                    $this->_module_models($module, $files);
                    break; 
                
                case "language":
                    $this->_module_language($module, $files);
                    break; 
                    
                case "views":
                    $this->_module_views($module, $files);
                    break;
                    
                case "js":
                    $this->_module_js($module, $files);
                    break;
                    
                case "css":
                    $this->_module_css($module, $files);
                    break;            
            }
        }
        
        //Получаем имя функции которая будет выводить содержание в блок на сайте
        $function    = $this->CI->config->module_item($module, $module, 'function');
        $module_type = '_'.$this->CI->config->module_item($module, $module, 'type');
        
        if(!$function || !$module_type) return;

        //print_r ($module_type);
        
        $model = str_replace('_model', '', str_replace(EXT, '', $module)).'_model';

        //print_R ($function);
        //$this->CI->display->_printer($this->CI->printer_model->printer());
        //echo '$this->CI->display->'.$module_type.'($this->CI->'.$model.'->'.$function.'())<br><br>';
        $this->CI->display->$module_type($this->CI->$model->$function());
        //print_R ($this->CI->t_menu_model->block());
        //$this->CI->display->_minimap($this->CI->minimap_menu_model->get());
    }
    
    
    
    
    /*
    * Loaded plugin
    */
    function load_plugin($plugin)
    {
        $includes = $this->CI->config->plugin_item($plugin, $plugin, 'includes');
       
        if(!$includes) return FALSE;
        
        foreach($includes as $type => $files)
        {
            switch($type)
            {
                case "js":
                    $this->_plugin_js($plugin, $files);
                    break;
                    
                case "css":
                    $this->_plugin_css($plugin, $files);
                    break;
                
                case "php":
                    $this->_plugin_php($plugin, $files);
                    break;

                case "models":
                    $this->_plugin_model($plugin, $files);
                    break;

                case 'libraries':
                    $this->_plugin_libraries($plugin, $files);
                    break;
            }               
        }
    }

    
    
    
    /*
    * Includes module
    */
    function _module_config($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_config($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_config($this->type, $module, $files);
        }
    }
    
    function _module_libraries($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_library($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_library($this->type, $module, $files);
        }
        
    }
    
    function _module_models($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_model($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_model($this->type, $module, $files);
        }
        
    }
    
    function _module_language($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_language($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_language($this->type, $module, $files);
        }
        
        
    }
    
    function _module_views($module, $files) 
    {
        
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_view($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_view($this->type, $module, $files);
        }
    }
    
    function _module_js($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_js($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_js($this->type, $module, $files);
        }
    }
    
    function _module_css($module, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->module_css($this->type, $module, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->module_css($this->type, $module, $files);
        }
    }
    
    
    
    
    /*
    * Includes plugin 
    */
    function _plugin_js($plugin, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->plugin_js($this->type, $plugin, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->plugin_js($this->type, $plugin, $files);
        }
    }
    
    function _plugin_css($plugin, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->plugin_css($this->type, $plugin, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->plugin_css($this->type, $plugin, $files);
        }
    }

    function _plugin_php($plugin, $files)
    {
        if(is_array($files))
        {
            if(count($files) == 0) return FALSE;
            
            foreach($files as $file)
            {
                $this->CI->load->plugin_php($this->type, $plugin, $file);
            }
        }
        
        if(is_string($files))
        {
            if(strlen($files) == 0) return FALSE;
            
            $this->CI->load->plugin_php($this->type, $plugin, $files);
        }
    }

    function _plugin_model($plugin, $files)
    {
        if(is_array($files)){
            if(count($files) == 0) return FALSE;

            foreach($files as $file){
                $this->CI->load->plugin_model($this->type, $plugin, $file);
            }
        }

        if(is_string($files)){
            if(strlen($files) == 0) return FALSE;

            $this->CI->load->plugin_model($this->type, $plugin, $files);
        }
    }

    function _plugin_libraries($plugin, $files)
    {
        if(is_array($files)){
            if(count($files) == 0) return FALSE;

            foreach($files as $file){
                $this->CI->load->plugin_library($this->type, $plugin, $file);
            }
        }

        if(is_string($files)){
            if(strlen($files) == 0) return FALSE;

            $this->CI->load->plugin_library($this->type, $plugin, $files);
        }
    }
    
} 
?>
