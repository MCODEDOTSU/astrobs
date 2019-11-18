<?php
class workingCalendar extends Admin_Controller
{
    function workingCalendar()
    {
        parent::Admin_Controller();
        parent::access('municipal');
    }
    
    function index()
    {
        $change_month = $this->input->post('change_month');
        $change_year = $this->input->post('change_year');
        
        if(!is_numeric($change_month)) $change_month = date('m');
        if(!is_numeric($change_year)) $change_year = date('Y');
        
        $data = array(
            'controls' => array(
                '0' => array(
                    'form_open' => form_open('admin/municipal/workingCalendar'), 
                    'form_close' => form_close(),
                    'select_date' => form_dropdown('change_month', $this->workingCalendar_model->mounth).form_dropdown('change_year', $this->workingCalendar_model->year),
                    'submit' => form_submit('frmSubmitChange', 'Изменить') 
                )
            ),
            'alotment_cal' => array(
                '0' => array(
                    'form_open' => form_open('admin/municipal/workingCalendar/set'), 
                    'form_close' => form_close(),
                    'calendar' => $this->workingCalendar_model->get_calendar($change_month, $change_year),
                    'year' => form_hidden('c_year', $change_year),
                    'month' => form_hidden('c_month', $change_month),
                    'submit' => form_submit('frmSubmitSave', 'Сохрвнить')
                )
            )
        );
        
        $this->module->parse('municipal', 'workingCalendar/index.php', $data);
            
    }
    
    function set()
    {
        $date = $this->input->post('date'); if(!isset($date)) return false;
        $c_year = $this->input->post('c_year'); if(!isset($c_year)) return false;
        $c_month = $this->input->post('c_month'); if(!isset($c_month)) return false;
        
        $this->workingCalendar_model->delete(array(
            'stamp >' => mktime(0,0,0, $c_month, 1, $c_year),
            'stamp <' => mktime(0,0,0, $c_month, $this->workingCalendar_model->daysInMonth($c_month, $c_year), $c_year)
        ));
        
        if(count($date) > 0) {
            foreach($date as $yName => $yValue){
                foreach($yValue as $mName => $mValue){
                    foreach($mValue as $dName => $dValue){
                        $this->workingCalendar_model->create(array(
                            'stamp' => mktime(0,0,0,$mName,$dName,$yName)
                        ));
                    }
                }
            }
        }
        
        redirect('admin/municipal/workingCalendar');        
    }
    
    
}
?>