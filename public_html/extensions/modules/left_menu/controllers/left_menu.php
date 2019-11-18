<?php

class Left_menu extends Public_Controller
{
    function Left_menu()
    {
        parent::Public_Controller();
    }

    function get()
    {
        echo $this->left_menu_model->get();
        die;
    }

    function category()
    {
        $html = '';

        $categoryId = $this->uri->segment(4);

        if(!is_numeric($categoryId)) return false;

        $arrFiles = $this->left_menu_model->getItemsToCategory($categoryId);

        foreach($arrFiles as $_file)
        {
            $config = $this->module->config($_file['type'], 'left_menu');

            if(count($config) == 0) continue;

            foreach($config as $file=>$func)
            {
                $html .= $this->$file->$func['category']($_file['id']);
            }
        }

        $this->display->_content($html);
    }
}

?>
