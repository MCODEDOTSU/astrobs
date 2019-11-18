<?php
class Authorization
{
    var $CI;
    var $autologin_timeout = 300;
    var $id_offset = 3;
    
    function Authorization()
    {
        $this->CI = &get_instance();
        
        if($this->CI->uri->uri_string == '/admin/logout') 
        {
            $this->logout();
        }   
    }
    
    function auth()
    {
        if($this->isLogged()) 
        {
            return TRUE;
        }
        
        
        if($this->CI->input->post('login_email') && $this->CI->input->post('login_pwd'))
        {    
            $email = $this->CI->input->post('login_email');
            $pwd = $this->CI->input->post('login_pwd');
            $autologin = $this->CI->input->post('login_save');
            
            $user_id = $this->login($email, md5($pwd), $autologin);
            $this->setSessionId($user_id, $autologin); 
            
            if( $user_id !== FALSE)
            {
                redirect('admin');
            }
            else 
            {
                //$this->data['message'] = $this->lang->line('error_login');
                //$this->_show_view('blank.php');
                return FALSE;
            }
        }
                
        if($this->getSessionId()) 
        {
            return TRUE;
        }
        
        $this->CI->display->_content($this->form_login());

        //$this->CI->output->set_output($this->form_login());
        $this->CI->output->_display();
        die;
    }
    
    function form_login()
    {
        $html = '
            <script>
                $("body").attr("id", "login");
            </script>
            <div id="cms_login">
            '.form_open('admin').'
                <h2>Вход в панель администратора</h2>
                <ul>
                    <li><label for="login_email">Email:</label> '.form_input('login_email').'</li>
                    <li><label for="login_pwd">Пароль:</label> '.form_password('login_pwd').'</li>
                    <li>'.form_checkbox('login_save', 1, false).'<label class="login_check" for="login_save">Запомнить?</label> </li>
                    <li>'.form_submit('login_enter', 'Войти').'</li>
                </ul>    
            '.form_close().'
            </div>
        ';
        
        return $html;
        
        
    }
    
    /*
     * function block for set/get session userID
     */
    function setSessionId($id, $storable = FALSE)
    {
        if( $id < 1 ) 
        {
            $this->CI->session->unset_userdata('id');
            $this->CI->session->unset_userdata('flash');
        }
        else
        {
            $this->CI->session->set_userdata('flash', !$storable);
            $offset = (int) $this->id_offset;
            
             if($storable)
             { 
                $this->CI->session->set_userdata('id', $id * $offset);
             }
             else 
             {
                 $this->CI->session->set_flashdata('id', $id * $offset);
                 $this->CI->session->set_flashdata('expire', time() + $this->autologin_timeout);
             }
        }                              
    }
    function getSessionId()
    { 
        $this->keepSession();
        
        
        if($this->CI->session->userdata('flash'))
        {
            if( !$this->CI->session->flashdata('id') )
                return 0;        
        }
        
        $offset = (int) $this->id_offset;
        
        if(!$this->CI->session->userdata('flash'))
        {
            $real_id = ($this->CI->session->userdata('id') / $offset);
        }
        else
        {
            $real_id = ($this->CI->session->flashdata('id') / $offset);
        }
        
        if(ceil($real_id) != floor($real_id)) 
        {
            $this->logout();
            return 0;
        }
        else return $real_id;      
    }
    
    // Продлевает сессию если время не истекло
    function keepSession()
    {
        if( ( $this->CI->session->userdata('flash') ) && ( $this->CI->session->flashdata('expire') >= time() ) ) 
        {
            $this->CI->session->keep_flashdata('id');
            $this->CI->session->set_flashdata('expire', time() + $this->autologin_timeout);
        }           
    }

    /*
     * Return TRUE if user is logged on
     */
    function isLogged() 
    { 
        return (bool) $this->getSessionId();    
    }
    
    function logout()
    {
        $this->setSessionId(-1, FALSE);
        redirect('admin');
    }
    
    function login($name, $pass, $autologin=FALSE)
    {
        $this->CI->db->where('name', $name);
        $this->CI->db->where('password', $pass);
        $success = $this->CI->db->count_all_results('th_auser');
        if($success)
        {     
            $this->CI->db->where('name', $name);
            $this->CI->db->where('password', $pass);
            $user_info = $this->CI->db->get('th_auser')->result_array();
            $user_data = $user_info[0];
            if(!$user_data['activate_code'])
            {
                $data = array('last_visit' => date("Y-m-d H:i:s"));
                $this->CI->db->update('auser', $data, array('id' => $user_data['id']));
                return $user_data['id'];
            }
        }
        return FALSE;     
    }
    
}
?>
