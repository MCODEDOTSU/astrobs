<?php

class Home extends Admin_Controller {

	function Home()
	{
		parent::Admin_Controller();
	}
	
	function index()
	{
		redirect ('/admin/place/place/');
        $this->display->_content('<h2>Главная страница панели администратора</h2>');
        $this->load->helper('file');
        $this->display->_content('<h3 align="center">Журнал событий</h3>');  
    /*    $this->display->_content('
            '.form_open('admin').'
            <ul class="form"><li>
            <label>Дата c:</label>'.
           form_input('day1', date('d'), 'style="width:20px;"').

            form_dropdown('month1', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date('m')).
            form_input('year1', date('Y'), 'style="width:40px;"').'

            <label>Дата по:</label>'.
            form_input('day2', date('d'), 'style="width:20p\\x;"').
            form_dropdown('month2', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date('m')).
            form_input('year2', date('Y'), 'style="width:40px;"').'

            '.form_submit('frmSubmit', 'Показать').'</li></ul>
            '.form_close().'
        ');*/
        $this->display->_content('
            '.form_open('admin').'
            <ul class="form"><li>
            <label>Дата c:</label>'.
           form_input('day1', date('d'), 'style="width:20px;"').

            form_dropdown('month1', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date('m')).
            form_input('year1', date('Y'), 'style="width:40px;"').'<br/>

            <label>Дата по:</label>'.
            form_input('day2', date('d'), 'style="width:20p\\x;"').
            form_dropdown('month2', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date('m')).
            form_input('year2', date('Y'), 'style="width:40px;"').'

            '.form_submit('frmSubmit', 'Показать').'</li></ul>
            '.form_close().'
        ');

        if($this->input->post('day1') && $this->input->post('month1') && $this->input->post('year1') && $this->input->post('day2') && $this->input->post('month2') && $this->input->post('year2')){
            
            $day=$this->input->post('day');
            $day1=$this->input->post('day1');
            $day2=$this->input->post('day2');
            
            $month=$this->input->post('month');
            $month1=$this->input->post('month1');
            $month2=$this->input->post('month2');
            
            $year=$this->input->post('year');
            $year1=$this->input->post('year1');
            $year2=$this->input->post('year2');
            
            
            $date1=strtotime($year1.'-'.$month1.'-'.$day1);
            $date2=strtotime($year2.'-'.$month2.'-'.$day2);
                
            if (is_numeric($day1) && is_numeric($day2) && is_numeric($year1) && is_numeric($year2))
            {
            if (checkdate($month1, $day1, $year1) && checkdate($month2, $day2, $year2) && $date1<=$date2)
            {            
            $this->display->_content('<p>Логи c <b>"'.date("Y-m-d", $date1).'"</b> по <b>"'.date("Y-m-d", $date2).'"</b></p>');          
            $this->display->_content('<hr />');               
            $num=0;
            for ($date=$date1; $date<=$date2;$date+=86400)
            {
                    $date0=date("Y-m-d", $date);
                    if(file_exists('./system/logs/log-'.$date0.'.php')){
                    
                    $this->display->_content('<p>Логи за <b>"'.$date0.'"</b></p>');          
            
                    $this->display->_content('<div class="admin_main_log">'.read_file('./system/logs/log-'.$date0.'.php').'</div>');
                    $num++;
                    } else{
                //    $this->display->_content('<p>Логи за <b>"'.$date0.'"</b> отсутствуют</p>'); 
                }
            }
            if ($num==0) $this->display->_content('<p>Логи с <b>'.date("Y-m-d", $date1).'</b> по <b>'.date("Y-m-d", $date2).'</b> отсутствуют</p>');
            }
            else 
            {
                  $this->display->_content('<p>Не правильно выбрана дата</p>');
            }
            } else $this->display->_content('<p>Не правильно выбрана дата</p>');
        }       
        
        
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
