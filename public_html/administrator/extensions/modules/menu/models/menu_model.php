<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends Model
{
    
    function Menu_model()
    {
        parent::Model();
        
    }
    
    function get_menu()
    {
        return $this->module->view('menu', 'jdMenu.php');
    }
}
?>
