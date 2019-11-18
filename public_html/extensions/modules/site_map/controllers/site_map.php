<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Site_map extends Public_Controller
{
    var $root;

    function Site_map()
    {
        parent::Public_Controller();
        $this->lang->setModule ('site_map');
    //    parent::access('place');
    //    $this->root = $this->folder->root;
    }

    function index()
    {
        
        $this->display->_content('<div class="content_desc">');
        $this->display->_content ($this->breadcrumbs->getHome(array ($this->lang->mline ('site_map_text') => site_url (array ('site_map', 'site_map')))));
        $this->display->_content('<h1>' . $this->lang->mline ('site_map_text') . '</h1>');

//       setcookie("place[f]", $this->authorization->group['folder'], time() + 3600);
        $Tree = $this->site_map_model->getTreeAsHTML();

        $this->module->parse('site_map', 'site_map.php', array('tree'=> $Tree));

	$this->display->_content('</div>');
    }
}
?>
