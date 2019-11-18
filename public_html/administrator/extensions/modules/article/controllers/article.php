<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Article extends Admin_Controller
{

    function Article()
    {
        parent::Admin_Controller();
    }
    
    function file()
    {
        //$fileId = $this->input->post('id');
        $fileId = $this->uri->segment(5);
        
        if(!is_numeric($fileId)) die;

        // return $mas[0] = array(... ... ..)
        $article = $this->article_model->extra( array( 'file_id' => $fileId) );
        if(count($article)) $article = $article[0];
		$tours = $this->db->get ('th_tours')->result_array ();
		$dataTours = array ();
		//print_r ($article);
		$selected = false;
		$checked = false;
		if ($article['tid'] > 0) {
			$selected = $article['tid'];
			$checked = true;
		}
		foreach ($tours AS $v) {
			$dataTours[$v['id']] = $v['name'];
		}
        $data = array(
        
            'form_open'     => form_open('admin/article/article/save', array('id' => 'articleForm')),
            'file'          => form_hidden('file', $fileId),
            'article'       => form_hidden('article', $article['id']),
            'title'         => form_input('title', $article['title'], 'style="width: 300px;"'),
            'body'          => form_ckeditor('body', $article['body']),
            'form_close'    => form_close(),
            'form_select'	=> form_dropdown ('selTour', $dataTours, $selected, 'id="selTour"'),
            'isTour'		=> form_checkbox ('isTour', 1, $checked, 'id="isTour"')
        );
        
        //echo $this->module->parse('article', 'form.php', $data, true);
        
        $this->module->parse('article', 'form.php', $data);
        
        //die;
    }
    
    function save()
    {
        $title   = $this->input->post('title');
        $body    = $this->input->post('body');
        $file    = $this->input->post('file');           
        $article = $this->input->post('article');

        $isTour  = ($this->input->post ('isTour') == 1) ? (int) $this->input->post ('selTour') : 0;
		
        $Article = $this->article_model->extra(array('file_id' => $file));

        $this->article_model->update(array('title' => $title, 'body' => $body, 'file_id' => $file, 'tid' => $isTour), array('id'=>$Article[0]['id']));

        //echo 'success';

        //die;
        
        redirect('admin/place/place');
    }
}  
?>
