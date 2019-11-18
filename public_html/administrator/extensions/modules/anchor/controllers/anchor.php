<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Anchor extends Admin_Controller
{

    function Anchor(){
        parent::Admin_Controller();
    }
    
    function file()
    {
        //$fileId = $this->input->post('id');
        $fileId = $this->uri->segment(5);
        
        if(!is_numeric($fileId)) die;

        // return $mas[0] = array(... ... ..)   
        $anchor = $this->anchor_model->extra( array( 'file_id' => $fileId) );
        if(count($anchor)) $anchor = $anchor[0];

		$q = $this->db->get ('th_tours')->result_array ();
		foreach ($q AS $v) $Tours[$v['id']] = $v['name'];
        $data = array(
        
            'form_open'     => form_open('admin/anchor/anchor/save', array('id' => 'anchorForm')),
            'file'          => form_hidden('file', $fileId),
            'anchor'        => form_hidden('anchor', $anchor['id']),
            'title'         => form_input('title', $anchor['title']),
            'url'           => form_input('url', $anchor['url']),
            'is_tour'		=> form_checkbox ('is_tour', '1', false, 'onchange="obj.checkTour ();" id="is_tour"'),
            'selTour'		=> form_dropdown ('selTour', $Tours, false, 'id="selTour" style="display: none"'),
            'tourName'		=> form_checkbox ('tourName', '1', false, 'id="tourName" style="display: none"'),
            'form_close'    => form_close()
        );
        
        $this->module->parse('anchor', 'form.php', $data);
        
    }
    
    function save()
    {
        $title   = $this->input->post('title');
        $url    = $this->input->post('url');
        $file    = $this->input->post('file');
        $anchor = $this->input->post('anchor');

        $anchor = $this->anchor_model->extra(array('file_id' => $file));

        $this->anchor_model->update(array('title' => $title, 'url' => $url, 'file_id' => $file), array('id'=>$anchor[0]['id']));
        if ($this->input->post ('saveTour') == 'yes') {
        	$tour = (int) $this->input->post ('selTour');
			$this->anchor_model->update (array ('url' => 'cattours/tours/categories/' . $tour), array ('id' => $anchor[0]['id']));
        }

        redirect('admin/place/place');
    }
}  
?>
