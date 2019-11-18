<?php
class Article extends Public_Controller
{
    function Article()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $articleId = $this->uri->segment(5);
        
        $Article = $this->article_model->getArticle(array('id' => $articleId));
        
        if(count($Article) == 0) return;
        
        $data = array(
            'title' => ucfirst($Article[0]['title']),
            'body'  => ucfirst($Article[0]['body']),
            'created' => date('d.m.Y', strtotime($Article[0]['created'])),
            'modifed' => $Article[0]['modifed']
        );
        
        $this->module->parse('article', 'view.php', $data);    
    }
    
    function view()
    {
		// print_r ($this->flinks->get ());
    	
        $articleId = $this->uri->segment(4);
        
        $Article = $this->article_model->getArticle(array('file_id' => $articleId));
        
        if(count($Article) == 0) return;

		
        
        $data = array(
            'title'			=> ucfirst($Article[0]['title']),
            'body' 			=> ucfirst($Article[0]['body']),
            'created'		=> date('d.m.Y', strtotime($Article[0]['created'])),
            'modifed'		=> $Article[0]['modifed'],
            'is_tour'		=> ((int) $Article[0]['tid'] > 0) ? $this->article_model->_getOrdFrm () : '',
            'breadCrumbs'	=> $this->breadcrumbs->get ($articleId),
            //'catTours'		=> $this->central_menu_model->get ()
        );
        
        $this->module->parse('article', 'view.php', $data);
    }

    function setOrder () {
    	if ($this->input->post ('fbut') == false) {
			if ($this->input->server ('HTTP_REFERER') !== false) {
				redirect ($this->input->server ('HTTP_REFERER'));
			} else {
				redirect (base_url ());
			}
		}

		$tid = (int) $this->input->post ('ftid');

		$data = array (
			'name'		=>	$this->input->post ('fname'),
			'email'		=>	$this->input->post ('femail'),
			'phone'		=>	$this->input->post ('fphone'),
			'nums'		=>	(int) $this->input->post ('fnums'),
			'sday'		=>	(int) $this->input->post ('fdays'),
			'smoon'		=>	(int) $this->input->post ('fmoons'),
			'eday'		=>	(int) $this->input->post ('fdaye'),
			'emoon'		=>	(int) $this->input->post ('fmoone'),
			'needs'		=>	$this->input->post ('fneeds'),
			'more'		=>	$this->input->post ('fmore'),
			'tid'		=>	$tid,
			'file_id'	=>	(int) $this->input->post ('file_id')
		);

		$email = $this->article_model->getOrdTour ($data['tid']);
		$tName = $this->article_model->getTourName ($data['file_id']);

		$moons = $this->article_model->_getArrs ('moons');
		$nums = $this->article_model->_getArrs ('nums');

		$this->article_model->mailTo ($data['name'], $data['email'], $data['phone'], $tName, $nums[$data['nums']], $data['sday'], $moons[$data['smoon']], $data['eday'], $moons[$data['emoon']], $data['needs'], $data['more'], $email);
		$this->module->parse('article', 'ordresult.php', array ('link' => anchor (site_url (array ('article', 'article', 'view', $data['file_id'])), 'Вернуться на страницу тура')));

    }
}
?>
