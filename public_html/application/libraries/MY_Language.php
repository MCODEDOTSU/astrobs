<?php
class MY_Language extends CI_Language
{
    var $language_modules		= array();
    var $is_loaded_modules		= array();
    var $language_plugins		= array ();
    var $module = '';
    var $plugin = '';
    var $langPlug = '';
    var $dirPlug = '';
    var $CI;
    /*function MY_Language () {
		parent::CI_Language ();
    }*/

	// Function For set Module
	function setModule ($module = '') {
		return $this->module = $module;
	}

	function _getHM () {
		$this->CI = &get_instance ();
		return ($this->CI->session->userdata ('language')) ? $this->CI->session->userdata ('language') : $this->_getDefaultLang ();
	}


	function _getDefaultLang () {
		$q = $this->CI->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
		return $q[0]['language'];
	}

    function module_load($type, $module, $langfile = '', $idiom = '', $return = FALSE)
    {
    	//print_r ($this);
        if($type == 'administrator')
        {
            $path = 'administrator/extensions/modules/'.$module.'/';
        }
        
        if($type == 'public')
        {
            $path = 'extensions/modules/'.$module.'/';
        }
        
        
        //$langfile = str_replace(EXT, '', str_replace('_lang.', '', $langfile)).
        
        $langfile = str_replace('_lang', '', str_replace(EXT, '', $langfile)).'_lang'.EXT; 
        
        
        if (in_array($langfile, $this->is_loaded_modules , TRUE))
        {
            return;
        }
		//print_r ($this->_getHM ());
        if ($idiom == '')
        {
        	$idiom = $this->_getHM ();
            /*$CI =& get_instance();
            $deft_lang = $CI->config->item('language');
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
            print_R ($idiom);*/
            //print_r ($idiom);
        }

        // Determine where the language file is and load it
        if (file_exists($path.'language/'.$idiom.'/'.$langfile))
        {
            include($path.'language/'.$idiom.'/'.$langfile);
        }
        else
        {
            show_error('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
        }

        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
            return;
        }

        if ($return == TRUE)
        {
            return $lang;
        }
		//print_r ($this->language_modules);
        
        $this->is_loaded_modules[] = $langfile;

       // print_R ($this->is_loaded_modules);
        
        //if(count($this->language_modules) == 0) $this->language_modules[$module] = array();

		if (!isset ($this->language_modules[$module])) {
			$this->language_modules[$module] = array();
		}
        
        
        if(count($this->language_modules[$module]) == 0)
        {
            $this->language_modules[$module] = $lang;
        }
        else
        {
            $this->language_modules[$module] = array_merge($this->language_modules[$module], $lang);    
        }
        
        unset($lang);
		//print_R ($this->language_modules);
        log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
        return TRUE;    
    }   

	function setPlugin ($plugin, $dir, $langP, $file) {
		$this->plugin = $plugin;
		$this->dirPlug = $dir;
		$this->langPlug = $langP;

		switch ($dir) {
			case 'public':
				$realPath = 'extensions/plugins/' . $plugin . '/language/' . $langP . '/' . $file;
				break;
			case 'admin':
				$realPath = 'administrator/extensions/plugins/' . $plugin . '/language/' . $langP . '/' . $file;
				break;
			default:
				show_error('Ошибка выбора директории: ' . $dir);
				return false;
				break;
		}
		if (!file_exists ($realPath)) {
			show_error('Файл не найден: ' . $realPath);
		}
		include ($realPath);
		if (count ($this->language_plugins) == 0) {
			$this->language_plugins = $lang;
		} else {
			$this->language_plugins = array_merge ($this->language_plugins, $lang);
		}
		unset ($lang);
	}

	function pline ($line = '') {
		if (isset ($this->language_plugins [$line])) {
			$plang = $this->language_plugins [$line];
		}
		else if (isset ($this->language_plugins [$this->plugin . '_' . $line])) {
			$plang = $this->language_plugins [$this->plugin . '_' . $line];
		}
		else {
			show_error('Ключевое слово не найдено в файле языков: ' . $line);
			return false;
		}
		return $plang;
	}

    function mline ($line = '') {
    	if (!isset ($this->language_modules[$this->module][$line])) return false;
    	return $this->language_modules[$this->module][$line];
		//$this->language_modules[]
    }
}  
?>
