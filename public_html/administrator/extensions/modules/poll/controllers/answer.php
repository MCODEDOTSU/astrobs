<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Answer extends Admin_Controller 
{
    var $moduleName = 'poll';
    var $controllerName = 'answer';
    
    function Answer()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);

    }
    
    function index()
    {
        $pollId = $this->uri->segment(5);
        if(!isset($pollId)) return FALSE;
        
        $this->display->_content('<div class="cms_title">'._icon('comment').'Список ответов</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/poll/answer/add/'.$pollId, _icon('comment_add').'Добавить ответ', 'class="cms_btn" rel="facebox"').'</div>');
        
        $Ansers = $this->answer_model->extra(array('poll_id'=>$pollId));
        
        if(count($Ansers) == 0) return FALSE;
        
        foreach($Ansers as $answer)
        {
            $data['answers'][] = array(
                'id'        => $answer['id'],
                'text'      => $answer['text'],
                'count'     => $answer['count'],
                'actions'   => anchor('admin/poll/answer/form/'.$answer['id'],_icon('comment_edit'), 'rel="facebox" title="Редактировать"').anchor('admin/poll/answer/delete/'.$answer['id'],_icon('comment_delete'), 'title="Удалить"')
            );
        }
        
        $this->_show('answers.php', $data);
    }
    
    function form()
    {
        $answerId = $this->uri->segment(5);
        
        if(!is_numeric($answerId)) return FALSE;
        
        $Answer = $this->answer_model->extra(array('id'=>$answerId));
        if(count($Answer)) $Answer = $Answer[0];
        
        if(count($Answer)==0) return FALSE;
        
        $data = array(
            'answer'        => form_hidden('answer', @$Answer['id']),
            'poll'          => form_hidden('poll', 0),
            'text'          => form_textarea('text', @$Answer['text']),
            'count'         => form_input('count', @$Answer['count']),
            'submit'        => form_submit('btmSubmit', 'Сохранить'),
            'form_open'     => form_open(),
            'form_close'    => form_close()    
        );
        
        $this->_show('form_answer.php', $data);
    }
    
    function add()
    {
        $pollId = $this->uri->segment(5);
        
        if(!isset($pollId)) return FALSE;
        
        $data = array(
            'answer'        => form_hidden('answer', 0),
            'poll'          => form_hidden('poll', $pollId),
            'text'          => form_textarea('text'),
            'count'         => form_input('count', 0),
            'submit'        => form_submit('btmSubmit', 'Сохранить'),
            'form_open'     => form_open('admin/poll/answer/create'),
            'form_close'    => form_close()    
        );
        
        $this->_show('form_answer.php', $data); 
    }
    
    function create()
    {
        $pollId = $this->input->post('poll');
        if(!is_numeric($pollId)) return FALSE;
        
        $this->answer_model->create(array(
            'text' => $this->input->post('text'),
            'count' => $this->input->post('count'),
            'poll_id' => $this->input->post('poll')
        ));
        
        redirect('admin/poll/answer/index/'.$pollId);
    }
    
	function delete()
    {
		$answerId = $this->uri->segment(5);

	    if(!is_numeric($answerId)) return FALSE;
        
        
  		$extraAnswer = $this->answer_model->extra(array('id'=> $answerId));
		if(count($extraAnswer) == 0) return FALSE;
	    $extraAnswer = $extraAnswer[0];
		if(count($extraAnswer) == 0) return FALSE;
	    $this->answer_model->delete(array('id'=> $answerId));

        redirect('admin/poll/answer/index/'.$extraAnswer['poll_id']);
    }

    function _show($view = '', $data = array(), $return = FALSE)
    {
        return $this->module->parse('poll', $view, $data, $return);
    }
}
?>
