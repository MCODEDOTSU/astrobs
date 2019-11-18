<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Poll extends Admin_Controller 
{
    function Poll()
    {
        parent::Admin_Controller();
        parent::access('poll');

    }
    
    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('chart_curve_add').'Голосования</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/poll/poll/form', _icon('chart_curve_add').'Добавить голосование', 'class="cms_btn" rel="facebox"').'</div>');
        
        $Polls = $this->poll_model->get();
        
        if(count($Polls) == 0) return FALSE;
		
        foreach($Polls as $poll)
        {

		if ($poll['archive'] == 1) {$archive = 'style="background: #ffdbf0;"';} else {$archive = '';}
		
		$data['polls'][] = array(
                'archive'   => $archive,
		'id'        => $poll['id'],
                'title'     => $poll['title'],
                'desc'      => $poll['desc'],
                'created'   => $poll['created'],
                'actions'   => anchor('admin/poll/answer/index/'.$poll['id'], _icon('chart_curve_go'), 'title="Просмотр"').
			       anchor('admin/poll/poll/archive/'.$poll['id'], _icon('chart_curve_link'), 'title="Поместить в архив"').
                               anchor('admin/poll/poll/remove/'.$poll['id'], _icon('chart_curve_delete'), 'title="Удалить"')
            );
        }
        
        $this->_show('polls.php', $data);
    }
    
    function form()
    {
        $pollId = $this->uri->segment(5);
        
        $Poll = $this->poll_model->extra(array('id'=>$pollId));
        if(count($Poll)) $Poll = $Poll[0];
        
        $data = array(
            'poll' => form_hidden('poll', @$Poll['id']),
            'title' => form_input('title', @$Poll['title']),
            'desc'  => form_textarea('desc', @$Poll['desc']),
            'form_open' => form_open('admin/poll/poll/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить')
        );
        
        $this->_show('form_poll.php', $data);
    }
    
    function _show($view = '', $data = array(), $return = FALSE)
    {
        return $this->module->parse('poll', $view, $data, $return);
    }
    
    function save()
    {
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');
        $poll = $this->input->post('poll');
        
        if(isset($poll)){
            $pollId = $this->poll_model->create(array(
                'title' => $title,
                'desc' => $desc
            ));
        } else {
            $this->poll_model->update(array(
                'title' => $title,
                'desc' => $desc
            ), array('id'=> $poll));
            $pollId = $poll;    
        }
        
        redirect('admin/poll/poll');
        
    }

	function archive()
    {
		$pollId = $this->uri->segment(5);
		if(!is_numeric($pollId)) return FALSE;
		$this->poll_model->update(array('archive' => '1'), array('id'=> $pollId));
		
        redirect('admin/poll/poll');
    }
	
    function remove()
    {
        $pollId = $this->uri->segment(5);
        if(!is_numeric($pollId)) return FALSE;
        $this->answer_model->delete(array('poll_id'=> $pollId));
        $this->poll_model->delete(array('id'=>$pollId));
        redirect('admin/poll/poll');
    }
    
     
}

?>