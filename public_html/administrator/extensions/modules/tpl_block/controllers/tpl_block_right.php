<?php

class Tpl_block_right extends Admin_Controller
{
    var $moduleName = 'tpl_block';
    var $tables;

    function Tpl_block_right()
    {
        parent::Admin_Controller();
        parent::access('tpl_block');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {
        $Right = $this->db->from($this->tables['tpl_block'])->where('side', 'right')->get()->result_array();

        $this->module->parse($this->moduleName, 'tpl_block_right.php', array(
            'form_open' => form_open('admin/tpl_block/tpl_block_right/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить'),
            'title' => form_input('title', $Right[0]['title'], 'style="width: 300px;"'),
            'body' => form_ckeditor('body', @$Right[0]['body'])
        ));
    }

    function save()
    {
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        $body = $this->input->post('body'); if(!is_string($body)) return FALSE;

        $this->db->delete($this->tables['tpl_block'], array('side' => 'right'));
        $this->db->insert($this->tables['tpl_block'], array('title' => $title, 'body' => $body, 'side'=>'right'));

        redirect('admin/tpl_block/tpl_block_right');
    }
}

?>
