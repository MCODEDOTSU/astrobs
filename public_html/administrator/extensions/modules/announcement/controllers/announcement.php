<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Announcement extends Admin_Controller
{

    function Announcement()
    {
        parent::Admin_Controller();
    }
    
    function file()
    {
        //$fileId = $this->input->post('id');
        $fileId = $this->uri->segment(5);
        
        if(!is_numeric($fileId)) die;

        // return $mas[0] = array(... ... ..)   
        $announcement = $this->announcement_model->extra( array( 'file_id' => $fileId) );
        if(count($announcement)) $announcement = $announcement[0];
        
        $data = array(
        
            'form_open'     => form_open('admin/announcement/announcement/save', array('id' => 'announcementForm')),
            'file'          => form_hidden('file', $fileId),
            'announcement'  => form_hidden('announcement', $announcement['id']),
            'title'         => form_input('title', $announcement['title']),
            'body'          => form_ckeditor('body', $announcement['body']),
            'form_close'    => form_close()
        );
        
        //echo $this->module->parse('announcement', 'form.php', $data, true);
        
        $this->module->parse('announcement', 'form.php', $data);
        
        //die;
    }
    
    function save()
    {
        $title   = $this->input->post('title');
        $body    = $this->input->post('body');
        $file    = $this->input->post('file');
        $announcement = $this->input->post('announcement');

        $announcement = $this->announcement_model->extra(array('file_id' => $file));

        $this->announcement_model->update(array('title' => $title, 'body' => $body, 'file_id' => $file), array('id'=>$announcement[0]['id']));

        //echo 'success';

        //die;
        
        redirect('admin/place/place');
    }
}  
?>
