<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Top_menu_model extends Model
{
    function Top_menu_model()
    {
        parent::Model();
        //echo 'TOP_model MENU';
    }
    
    function public_block()
    {
        
        return '
            <div id="vertical_menu_ul">
              <ul>
                <li><a href="'.base_url().'">Главная</a></li>
                <li><a href="'.base_url().'news_block/news_block">Новости</a></li>
                <li><a href="#">О центре</a></li>
                <li><a href="#">Вакансии</a></li>
                <li><a href="#">Контакты</a></li>
                <li><a href="'.base_url().'qas/qas">Вопрос-ответ</a></li>
              </ul>
            </div>
        ';
    }
}   
?>
