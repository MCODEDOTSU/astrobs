<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Map extends Public_Controller
{
    function Map()
    {
        parent::Public_Controller();
    }

    function view()
    {
        $fileId = $this->uri->segment(4);

        $Map = $this->map_model->extra(array('file_id' => $fileId));

        foreach($Map as $_map)
        {
            $data = array(
                'title' => $_map['title'],
                'desc'  => $_map['desc'],
                'map'   => $this->map_model->mapsContainer(),
                'point' => $_map['point']
            );
        }

        $this->module->parse('map', 'view.php', $data);
    }
    
}
?>
