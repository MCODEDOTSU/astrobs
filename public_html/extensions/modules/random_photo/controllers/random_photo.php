<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Random_photo extends Public_Controller
{
    function Random_photo()
    {
        parent::Public_Controller();
    }

    function index()
    {
        
        $arrRandom = $this->Random_photo->get();

        foreach($arrRandom as $Random)
        {
            if(strlen($Random['file_name']) == 0) continue;

            $this->module->parse('Random_photo', 'view', array(
                 'file_name' => ucfirst($Random['file_name'])
            ));
        }


    }
}
?>
