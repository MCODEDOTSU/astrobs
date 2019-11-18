<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package            BackendPro
 * @author             Adam Price
 * @copyright          Copyright (c) 2008
 * @license            http://www.gnu.org/licenses/lgpl.html
 * @tutorial           BackendPro.pkg
 */

/**
 * Site_Controller
 *
 * Extends the default CI Controller class so I can declare special site controllers
 *
 * @package         BackendPro
 * @subpackage      Controllers
 */
class Site_Controller extends Controller
{
  
    function Site_Controller()
    {
        parent::Controller();
        
        $this->load->library('Module');

        $this->load->library('Display');

        log_message('debug','Site_Controller Class Initialized');
    }
}

/**
 * Public_Controller
 *
 * Extends the Site_Controller class so I can declare special Public controllers
 *
 * @package        BackendPro
 * @subpackage     Controllers
 */
class Public_Controller extends Site_Controller
{
    function Public_Controller()
    {
        parent::Site_Controller();
        
        _Template();

        
        
        _Extensions();
        
        log_message('debug','Public_Controller Class Initialized');
    }
}

/**
 * Admin_Controller
 *
 * Extends the Site_Controller class so I can declare special Admin controllers
 *
 * @package            BackendPro
 * @subpackage        Controllers
 */
class Admin_Controller extends Site_Controller
{
    function Admin_Controller()
    {
        parent::Site_Controller();
        
        _Template('administrator');

        _Authorization();
        
        _Extensions('administrator');
        
        _prevButton();

        log_message('debug','Admin_Controller Class Initialized');
    }
    
    function access($module)
    {
        $this->authorization->access($module);
    }
}

?>
