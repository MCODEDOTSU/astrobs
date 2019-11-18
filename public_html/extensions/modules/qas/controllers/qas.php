<?php
class Qas extends Public_Controller
{
    function Qas()
    {
        parent::Public_Controller();
    }
    
    function _show($view = '', $data = array(), $return = false)
    {
        return $this->module->parse('qas', $view, $data, $return);
    }
    
    function index() {

	$error = '';

        if( $this->uri->segment(4) == 'success' ) {
	     $error = '<p><font color="green">Ваш вопрос успешно отправлен и в скором времени на него будет дан ответ.</font></p>';
	}
        if( $this->uri->segment(4) == 'failed' ) {
	     $error = '<p><font color="red">Ошибка! Проверьте правильность заполнения формы.</font></p>';
	}

        $data = array(
            'form_open' => form_open('qas/qas/save', 'class="form-horizontal"'),
            'form_close' => form_close(),
            'author_to' => form_input('author_to', '', 'class="form-control"'),
            'author_from' => form_input('author_from', '', 'class="form-control"'),
            'question' => form_textarea('question', '', 'class="form-control" rows="3" style="width: 100%;"'),
            'submit' => form_submit('frmSubmit', 'отправить', 'class="btn btn-danger pull-right"'),
			'error' => $error
        );
        
        
        
        $arrayQas = $this->qas_model->get();
        //if(count($arrayQas) == 0) return FALSE;
        
        foreach($arrayQas as $qas)
        {
            if(strlen($qas['answer']) == 0) continue;

            $data['answers'][] = array(
                
                'answer' => $this->_show('view.php', array(
                                'author_to' => $qas['author_to'],
                                'author_from' => $qas['author_from'],
                                'question' => $qas['question'],
                                'answer' => $qas['answer']
                            ), true)
            );
        }

        $this->_show('form.php', $data);

    }
    
    function save()
    {
        $author_to   = $this->input->post('author_to');
        $author_from = $this->input->post('author_from');
        $question    = $this->input->post('question');
        
        if(strlen($question) && strlen($author_to) && strlen($author_from))
        {
            $this->qas_model->create(array(
                'author_to' => $author_to,
                'author_from' => $author_from,
                'question' => $question
            ));

            redirect('qas/qas/index/success');
        }
        else
        {
            redirect('qas/qas/index/failed');
        }
        
    }
}
?>
