<?php
class Calendar_model extends Model
{
    var $moduleName = 'calendar';
    
    function Calendar_model()
    {
        parent::Model();
    }
    
    function calendar_block()
    {
        $data=array();
        if ($this->input->post('year')) $year=$this->input->post('year');  else $year=date('Y');
        if ($this->input->post('month')) $month=$this->input->post('month'); else $month=date('m');
        if ($this->input->post('day'))
        {
          $day=$this->input->post('day');
          $year=$this->input->post('y'); 
          $month=$this->input->post('m');
        }
        else $day=0;
    
     $prefs = array (
           'start_day'      => 'monday',
           'month_type'  => 'long',
           'day_type'      => 'abr',
           'template' => ' 
           {table_open}<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" id="kalend">{/table_open} 
           {heading_row_start}<tr>{/heading_row_start} 
           {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell} 
           {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell} 
           {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell} 
           {heading_row_end}</tr>{/heading_row_end} 
           {week_row_start}<tr>{/week_row_start} 
           {week_day_cell}<th>{week_day}</th>{/week_day_cell} 
           {week_row_end}</tr>{/week_row_end} 
           {cal_row_start}<tr>{/cal_row_start} 
           {cal_cell_start}<td>{/cal_cell_start} 
           {cal_cell_content}<a href="#calendar" onclick="document.calendar.day.value=\'{content}\';document.calendar.submit()">{day}</a>{/cal_cell_content}
           {cal_cell_content_today}<div class="highlight"><a href="#calendar" onclick="document.calendar.day.value=\'{content}\';document.calendar.submit()">{day}</a></div>{/cal_cell_content_today}
           {cal_cell_no_content}{day}{/cal_cell_no_content} 
           {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today} 
           {cal_cell_blank}&nbsp;{/cal_cell_blank} 
           {cal_cell_end}</td>{/cal_cell_end} 
           {cal_row_end}</tr>{/cal_row_end} 
           {table_close}</table>{/table_close}'
     );
    
    $days=array();


    $news=$this->get_news_date($year, $month);

    if ($news)
    {
        foreach($news as $_news)
        {

            $c_day=date('d', strtotime($_news['created']));
            $c_month=date('m', strtotime($_news['created']));
        // убираем ведущий ноль для индекса массива
            $index = (($c_day < 10) ?  substr($c_day, 1) : $c_day);
            $month = (($c_month < 10) ?  substr($c_month, 1) : $c_month);
            if($c_month == $month)  $days[$index] = $c_day;
        }
        
    }
        $this->lang->load('calendar', 'russian');
        $this->load->library('calendar', $prefs);
        $data['template'] = $this->calendar->generate($year, $month, $days);


        $a="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

        $this->lang->load('calendar', 'russian');
        $this->load->library('calendar', $prefs);
         return $this->module->parse($this->moduleName, 'block.php', array(
            'month' =>  form_dropdown('month', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date($month)),
            'year' =>  form_dropdown('year', array('2010'=>2010, '2011'=>2011, '2012'=>2012), date($year)),
            'day' => form_hidden('day', $day),
            'calendar' =>  $this->calendar->generate($year, $month, $days),
            'submit' => form_submit('frmSubmit', 'OK', 'class="search_submit radius"'),
            'm' => form_hidden('m', $month),
            'y' => form_hidden('y', $year),
            'form_openC' => form_open($this->moduleName.'/'.$this->moduleName, array('name'=>'calendar')),
            'form_closeC' => form_close(),
            'form_openM' => form_open($a),
            'form_closeM' => form_close(),
            
        ), TRUE);
        /* return $this->module->parse($this->moduleName, 'block.php', array(
            'month' =>  form_dropdown('month', array('01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'), date($month), 'onchange=showUser(this.value)'),
            'year' =>  form_input('year', date('Y'), 'style="width:40px;"'),
            'day' => form_hidden('day', ''),
            'calendar' => $data['template'], // $this->calendar->generate($year, $month, $days),
            'submit' => form_submit('frmSubmit', 'OK', 'class="search_submit radius"'),
            'text' => form_input('txt', ''),
            'form_open' => form_open('', array('name'=>'calendar')),
            'form_close' => form_close()
        ), TRUE);*/
//    }

    }
  function get_news_date($year=NULL, $month=NULL)
  {
      $d = strtotime("$year-$month");
      $where=date('Y-m', $d);
      if (count($where)==0) return false;
      return $this->db->from('th_news')->like('created', $where)->get()->result_array();
  }
  function get_news($year=NULL, $month=NULL, $day=NULL)
  {
      if ($day!=0)
      {
      $d = strtotime("$year-$month-$day"); 
      $where=date('Y-m-d', $d);
      } else {
      $d = strtotime("$year-$month"); 
      $where=date('Y-m', $d);
      }
      if (count($where)==0) return false;
      return $this->db->like('created',$where)->get('th_news')->result_array();
  }

}
?>
