<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Announcement_block extends Public_Controller
{
    function Announcement_block()
    {
        parent::Public_Controller();
    }

    function index()
    {
        
        $arrAnnouncement = $this->announcement_block_model->get();

        foreach($arrAnnouncement as $announcement)
        {
            if(strlen($announcement['title']) == 0) continue;

            if(strlen($announcement['body']) == 0) continue;

            $this->module->parse('announcement_block', 'view', array(
                'title' => ucfirst($announcement['title']),
                'body'  => ucfirst($Aannouncement['body']),
	        'date' => ucfirst($announcement['created'])
            ));
        }


    }
}
?>
