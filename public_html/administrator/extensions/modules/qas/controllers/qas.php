<?php if (!defined('BASEPATH')) exit('No direct script access allowed');        
class Qas extends Admin_Controller
{
    function Qas()
    {
        parent::Admin_Controller();
        parent::access('qas');
    }
    
    function _show($view = '', $data = array())
    {
        $this->module->parse('qas', $view, $data);
    }
    
    function index()
    {
        $Qas = $this->qas_model->get();

        if(count($Qas) == 0) return FALSE;

        $this->display->_content('<div class="cms_title">'._icon('keyboard').'Вопрос - ответ</div>');

        foreach($Qas as $_qas)
        {
            $data['qas'][] = array(
                'id'            => $_qas['id'],
                'author_to'     => $_qas['author_to'],
                'author_from'   => $_qas['author_from'],
                'question'      => $_qas['question'],
                'answer'        => $_qas['answer'],
                'created'       => $_qas['created'],
                'state'         => $_qas['state'],
                'actions'       => anchor('admin/qas/qas/form/'.$_qas['id'], _icon('keyboard_magnify'), 'title="Просмотр"').anchor('admin/qas/qas/delete/'.$_qas['id'], _icon('keyboard_delete'), 'title="Удалить"')
            );
        }

        $this->_show('qas.php', $data);
    }
    
    function form()
    {
        $qasId = $this->uri->segment(5);
        
        if(!isset($qasId)) return FALSE;

        $this->display->_content('<div class="cms_title">'._icon('keyboard').'Вопрос - ответ</div>');

        $Qas = $this->qas_model->extra(array('id'=>$qasId));
        if(count($Qas)) $Qas = $Qas[0];
        
        $data = array(
            'qas'           => form_hidden('qas', $Qas['id']),
            'author_to'     => $Qas['author_to'],
            'author_from'   => $Qas['author_from'],
            'question'      => $Qas['question'],
            'answer'        => form_textarea('answer', $Qas['answer']),
            'created'       => $Qas['created'],
            'form_open'     => form_open('admin/qas/qas/answer'),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Сохранить')
        );
        
        $this->_show('form_qas.php', $data);
    }

	function delete()
	{
		$qasId = $this->uri->segment(5);

	    if(!is_numeric($qasId)) return FALSE;
       
        
        $this->qas_model->delete(array('id'=> $qasId));
        
        redirect('admin/qas/qas');
	}
    
    function answer()
    {
        $qasId = $this->input->post('qas');
        $answer = $this->input->post('answer');
        
        $this->qas_model->update(array('answer'=> $answer), array('id'=> $qasId));
        
        redirect('admin/qas/qas');
    }
} 
?>
