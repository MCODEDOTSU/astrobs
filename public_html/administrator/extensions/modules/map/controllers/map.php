<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Map extends Admin_Controller
{
    function Map()
    {
        parent::Admin_Controller();
    }

    function file()
    {
        //$fileId = $this->input->post('id');
        $fileId = $this->uri->segment(5);
        if(!is_numeric($fileId)) return false;
        
        // return $mas[0] = array(... ... ..)
        $map = $this->map_model->extra( array( 'file_id' => $fileId) );
        if(count($map)) $map = $map[0];

        $data = array(
            'title'         => form_input('title', $map['title']),
            'desc'          => form_textarea('desc', $map['desc']),
            'point'         => form_input(array('name' => 'map_point', 'value'=>$map['point'], 'id'=>'map_point', 'type'=>'hidden')),
            'mapsYandex'    => $this->map_model->mapsYandex(),
            'form_open'     => form_open('admin/map/map/save', 'id="mapForm"'),
            'file'          => form_hidden('file', $fileId),
            'form_close'    => form_close(),
            'submit'        => form_submit('frmSubmit', 'Сохранить')
        );

        $this->module->parse('map', 'form.php', $data);

    }

    function save()
    {
        $fileId = $this->input->post('file');
        
        $point = $this->input->post('map_point');
        $title = $this->input->post('title');
        $desc  = $this->input->post('desc');

        if(is_numeric($fileId))
        {
            $Map = $this->map_model->extra(array('file_id' => $fileId));

            foreach($Map as $_map)
            {
                $map_id = $_map['id'];
            }

            if(!is_numeric($map_id)) return FALSE;

            $this->map_model->update(array(
                'title' => $title,
                'desc'  => $desc,
                'point' => $point
            ), array('id' => $map_id));
        }
        
        redirect('admin/place/place');

    }
}
?>
