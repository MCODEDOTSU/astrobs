<?php
class workingCalendar_model extends Model
{
    var $TABLES;
    var $mounth = array(
        '01' => 'Январь',
        '02' => 'Февраль',
        '03' => 'Март',
        '04' => 'Апрель',
        '05' => 'Май',
        '06' => 'Июнь',
        '07' => 'Июль',
        '08' => 'Август',
        '09' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь'
    );
    var $days_of_week = array(
        '1' => 'Пнд.',
        '2' => 'Вт.',
        '3' => 'Ср.',
        '4' => 'Чтв.',
        '5' => 'Птн.',
        '6' => 'Сбт.',
        '7' => 'Вск.'
    );
    
    var $year = array();
    
    function workingCalendar_model()
    {
        parent::Model();
        $this->TABLES = $this->module->config('municipal', 'TABLES');
        
        $this->year = array(
            date('Y') => date('Y'), 
            date('Y')+1 => date('Y')+1, 
            date('Y')+2 => date('Y')+2
        );
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        return $this->db->insert($this->TABLES['Calendar'], $extrafields);
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;
        
        return $this->db->delete($this->TABLES['Calendar'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->TABLES['Calendar'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->TABLES['Calendar'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLES['Calendar'])->result_array();
    }
    
    function get_calendar($month, $year)
    {
        if(!is_numeric($month)) return FALSE;    
        if(!is_numeric($year)) return FALSE;
        
        $html = '<ul class="mod_doctor_year">';
        
        $countDays = $this->daysInMonth($month, $year);
        $startDayInWeek = date('w', mktime(0, 0, 0, $month, 1, $year));

        if($startDayInWeek == 0){$startDayInWeek = 1;} 

        $html .= '<li>'.$this->getMonthAsHTML($startDayInWeek, $countDays, $month, $year).'</li>';

        $html .= '</ul>';
        
        return $html;    
    }
    
    function getMonthAsHTML($startDayInWeek = null, $countDays = null, $month = null, $year = null)
    {
        if(!is_numeric($startDayInWeek)) return FALSE;
        if(!is_numeric($countDays)) return FALSE;
        if(!is_numeric($month)) return FALSE;
        if(!is_numeric($year)) return FALSE;
        
        $html = '<table cellspacing="0" cellpadding="0" border="0" class="mod_doctor_month">';
        $html .= '<thead>';
        $html .= '<tr><th colspan="7">'.$this->mounth[$month].', '.$year.'г.</th></tr>';
        
        $html .= '<tr>';
        foreach($this->days_of_week as $k=>$v){
            $html .= '<th>'.$v.'</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $day = 1;
        for($i=1;$i<=5;$i++){
            $html .= '<tr>';
            for($j=1;$j<=7;$j++){
                $html .= '<td';

                if($j > 5) $html .= ' class="mod_doctor_calendar_weekend" ';

                $html .= '>';

                if($i == 1){
                    if($j < $startDayInWeek){
                        $html .= '&nbsp;</td>';
                        continue;
                    }
                }

                if($day > $countDays){
                    $html .= '&nbsp;</td>';
                    continue;
                }
                
                /*if((
                      mktime(0, 0, 0, $month, $day, $year) <
                      mktime(0, 0, 0, date('m', time()), date('d', time()), date('Y', time()))
                    ) || (
                      mktime(0, 0, 0, $month, $day, $year) >
                      mktime(0, 0, 0, date('m', time() + 31556926), date('d', time() + 31556926), date('Y', time() + 31556926))
                    ))
                {
                    $html .= '&nbsp;</td>';
                    $day++;
                    continue;
                }*/
                
                $this->db->where(array(
                    'stamp' => mktime(0,0,0,$month,$day,$year)
                ))->from($this->TABLES['Calendar']);

                $html .= $day;
                $html .= form_checkbox('date['.$year.']['.$month.']['.$day.']', 1, $this->db->count_all_results());
                $html .= '</td>';
                $day++;
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }
    
    function daysInMonth($month = 0, $year = '')
    {
        $days_in_month    = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        $d = array("Январь" => 31, "Февраль" => 28, "Март" => 31, "Апрель" => 30, "Май" => 31, "Июнь" => 30, "Июль" => 31, "Август" => 31, "Сентябрь" => 30, "Октябрь" => 31, "Ноябрь" => 30, "Декабрь" => 31);

        if(!is_numeric($year) || strlen($year) != 4) $year = date('Y');

        if($month == 2 || $month == 'Feb'){
            if($this->leapYear($year)) return 29;
        }

        if(is_numeric($month)){
            if($month < 1 || $month > 12) return 0;
            else return $days_in_month[$month - 1];
        } else {
            if(in_array($month, array_keys($d))) return $d[$month];
            else return 0;
        }
    }
}
?>
