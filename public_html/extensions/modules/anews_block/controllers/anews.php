<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Anews_block extends Public_Controller
{
    function Anews_block()
    {
        parent::Public_Controller();
    }

    function index()
    {
        
        $arrAnews = $this->Anews_block_model->get();

        foreach($arrAnews as $Anews)
        {
            if(strlen($Anews['title']) == 0) continue;

            if(strlen($Anews['body']) == 0) continue;

            $this->module->parse('Anews_block', 'view', array(
                'title' => ucfirst($Anews['title']),
                'body'  => ucfirst($Anews['body']),
	        'date' => ucfirst($Anews['date'])
            ));
        }


    }
}
?>
