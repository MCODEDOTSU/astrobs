<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class News_block extends Public_Controller
{
    function News_block()
    {
        parent::Public_Controller();
    }

    function index()
    {
       
        $arrNews = $this->news_block_model->get();

        foreach($arrNews as $news)
        {
            if(strlen($news['title']) == 0) continue;

            if(strlen($news['body']) == 0) continue;

            $this->module->parse('news_block', 'view', array(
                'title' => ucfirst($news['title']),
                'body'  => ucfirst($news['body']),
	        'created' => date('d:m:Y', strtotime($news['created']))
            ));
        }


    }
}
?>
