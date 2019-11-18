<?php

class Tpl_block_bottom_eng extends Admin_Controller
{
    var $moduleName = 'tpl_block';
    var $tables;

    function Tpl_block_bottom_eng()
    {
        parent::Admin_Controller();
        parent::access('tpl_block');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {
        $Bottom = $this->db->from($this->tables['tpl_block'])->where('side', 'bottom_eng')->get()->result_array();

        $this->module->parse($this->moduleName, 'tpl_block_bottom.php', array(
            'form_open' => form_open('admin/tpl_block/tpl_block_bottom_eng/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить'),
            'body' => form_ckeditor('body', @$Bottom[0]['body'])
        ));
    }

    function save()
    {
        $body = $this->input->post('body'); if(!is_string($body)) return FALSE;

        $this->db->delete($this->tables['tpl_block'], array('side' => 'bottom_eng'));
        $this->db->insert($this->tables['tpl_block'], array('body' => $body, 'side'=>'bottom_eng'));

        redirect('admin/tpl_block/tpl_block_bottom_eng');
    }
}
?>
