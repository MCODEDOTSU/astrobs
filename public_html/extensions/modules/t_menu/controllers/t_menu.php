<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class T_menu extends Public_Controller
{
    function T_menu()
    {
        parent::Public_Controller();
        //echo 'dfjghs';
    }

    function category()
    {
        $html = '';

        $categoryId = $this->uri->segment(4);

        if(!is_numeric($categoryId)) return false;

        $arrFiles = $this->t_menu_model->getFilesToCategory($categoryId);
		$html .= '<div class="content_desc">' . $this->breadcrumbs->catget ($categoryId) . '';
        foreach($arrFiles as $_file)
        {
            $config = $this->module->config($_file['type'], 'left_menu');

            if(count($config) == 0) continue;
            //print_r ($_file);
			
            foreach($config as $file=>$func)
            {
            	//print_r ($file);
                $html .= $this->$file->$func['category']($_file['id']);
                //echo $file . ' => ' . $func['category'] . '<br>'; 
            }
        }
        $html .= '</div>';
        //print_R ($this->breadcrumbs->catget ($categoryId));
//print_r ($html);
        $this->display->_content('<div id="content_category">'.$html.'</div>');
    }
}
?>
