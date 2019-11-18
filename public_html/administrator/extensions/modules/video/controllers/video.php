<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Video extends Admin_Controller
{
    var $moduleName = 'video';
    var $controllerName = 'video';
    var $moduleTitles = '';
    var $confValid = array();
    
    function Video()
    {
        parent::Admin_Controller();
        $this->moduleTitles = $this->module->config($this->moduleName, 'title');
        $this->confValid = $this->module->config($this->moduleName, 'validation');
    }

    function file()
    {
        $this->display->_content('<div class="cms_title"><img src="' . base_url() . 'cms_icons/film.png" align="absmiddle">'.$this->moduleTitles.'</div>');
        
        $fileId = $this->uri->segment(5);  if(!is_numeric($fileId)) return FALSE;

        $this->validation->set_fields($this->confValid['fields']);
        
        $Photo = $this->video_model->extra(array('file_id' => $fileId));

        if(count($Photo) == 0) {
            return FALSE;
        }

        $data = array(
            'title'         => form_input('title', $Photo[0]['title'], 'style="width: 300px;"'),
            'desc'          => form_textarea('desc', $Photo[0]['desc']),
            'path'          => form_input('file', $Photo[0]['file'], 'style="width: 300px;"'),
            'file_id'       => form_hidden('file_id', $fileId),
            'submit'        => form_submit('frmSubmit', 'Сохранить'),
            'form_open'     => form_open_multipart('admin/'.$this->moduleName.'/'.$this->controllerName.'/save'),
            'form_close'    => form_close()
        );
        
        $this->module->parse($this->moduleName, 'form.php', $data);
    }

    function save()
    {
        $fileId  = $this->input->post('file_id'); if(!is_numeric($fileId)) return FALSE;
        
        //$upload_data = $this->video_model->do_upload('path');
        /*if(count($upload_data) == 0) {
            return FALSE;    
        }  */
        
        $this->validation->set_rules($this->confValid['rules']);
        $this->validation->set_fields($this->confValid['fields']);
        
        if($this->validation->run() == TRUE){
            
            $data['title'] = $this->validation->title;
            $data['desc'] = $this->validation->desc;
            $data['file'] = $this->validation->file;
            
            $Photo = $this->video_model->extra(array('file_id' => $fileId));

            if(count($Photo) == 0){
                return FALSE;
            }
            
            $this->video_model->update($data, array('id' => $Photo[0]['id']));
            
            redirect('admin/place/place');
                
        } else {
            $this->index();    
        }
    }
}
?>
