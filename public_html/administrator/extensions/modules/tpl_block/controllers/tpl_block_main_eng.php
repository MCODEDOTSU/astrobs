<?php

class Tpl_block_main_eng extends Admin_Controller
{
    var $moduleName = 'tpl_block';
    var $tables;

    function Tpl_block_main_eng()
    {
        parent::Admin_Controller();
        parent::access('tpl_block');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {
        $Main = $this->db->from($this->tables['tpl_block'])->where('side', 'main_eng')->get()->result_array();

        $this->module->parse($this->moduleName, 'tpl_block_main.php', array(
            'form_open' => form_open('admin/tpl_block/tpl_block_main_eng/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить'),
            'title' => form_input('title', $Main[0]['title'], 'style="width: 300px;"'),
            'body' => form_ckeditor('body', @$Main[0]['body'])
        ));
    }

    function save()
    {
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        $body = $this->input->post('body'); if(!is_string($body)) return FALSE;

        $this->db->delete($this->tables['tpl_block'], array('side' => 'main_eng'));
        $this->db->insert($this->tables['tpl_block'], array('title' => $title,'body' => $body, 'side'=>'main_eng'));

        redirect('admin/tpl_block/tpl_block_main_eng');
    }
}
?>
