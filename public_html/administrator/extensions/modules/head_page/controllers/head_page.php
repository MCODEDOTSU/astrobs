<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Head_page extends Admin_Controller
{
    var $moduleName = 'head_page';
    var $tables = array();

    function Head_page()
    {
        parent::Admin_Controller();
        parent::access('head_page');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {
        $mainPage = $this->db->get($this->tables['Head_page'])->result_array();

        $this->module->parse($this->moduleName, 'index.php', array(
            'form_open' => form_open('admin/'.$this->moduleName.'/'.$this->moduleName.'/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить'),
            'body' => form_ckeditor('body', @$mainPage[0]['body'])
        ));
    }

    function save()
    {
        $body = $this->input->post('body'); if(!is_string($body)) return FALSE;

        $this->db->empty_table($this->tables['Head_page']);
        $this->db->insert($this->tables['Head_page'], array('body' => $body));

        redirect('admin/'.$this->moduleName.'/'.$this->moduleName);
    }
}
?>
