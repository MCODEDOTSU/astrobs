<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Comments extends Admin_Controller
{
    var $moduleName = 'comments';
    var $tables = array();

    function Comments()
    {
        parent::Admin_Controller();
        parent::access('comments');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {


		$this->module->parse($this->moduleName, 'index.php', array(
            '1' => '',
        ));
    }
	function delete () {
		mysql_query ("DELETE FROM `th_comments` WHERE `id` = '" . (int) $this->uri->segment(5) . "'");
		$this->module->parse($this->moduleName, 'index.php', array(
            '1' => '',
		'fileid' => $this->uri->segment(6)
        ));	

	}

	function active () {
		mysql_query ("UPDATE `th_comments` SET `active` = '" . (int) $this->uri->segment(6) . "'  WHERE `id` = '" . (int) $this->uri->segment(5) . "'");
		
		$this->module->parse($this->moduleName, 'index.php', array(
            'fileid' => $this->uri->segment(7)
        ));	

	}

}
?>
