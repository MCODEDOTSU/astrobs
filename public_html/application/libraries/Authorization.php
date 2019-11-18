<?php
class Authorization
{
    var $CI;
    var $autologin_timeout = 300;
    var $id_offset = 3;

    var $user;
    var $group;
    
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
            $user = $this->CI->db
                             ->where(array('id'=>$this->getSessionId()))
                             ->get('auser')
                             ->result_array();

            $this->user = $user[0];

            $group = $this->CI->db
                             ->where(array('id'=>$user[0]['group_id']))
                             ->get('augroup')
                             ->result_array();
            $this->group = $group[0];
            return TRUE;
        }

        if($this->CI->input->post('login_email') && $this->CI->input->post('login_pwd'))
        {    
            $email = $this->CI->input->post('login_email');
            $pwd = $this->CI->input->post('login_pwd');
            $autologin = true;

            $user_id = $this->login($email, md5($pwd), $autologin);
            if( $user_id['id'] !== FALSE)
            {
                $this->setSessionId($user_id['id'], $autologin);
                log_message('INFO', 'Пользователь '.$user_id['name'].' вошел в систему.');
                redirect('admin');
            }
            else 
            {
                redirect('admin');
            }
        }

        $this->CI->display->_content($this->form_login());
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
                <div class="cms_title">Вход в панель администратора</div>
			<label><span>Логин:</span>'.form_input('login_email').'</label>
			<label><span>Пароль:</span>'.form_password('login_pwd').'</label>
			'
			.form_submit('login_enter', 'Войти', 'class="intoadmin"').form_close().'
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

    function login($email, $pass, $autologin=FALSE)
    {
        $this->CI->db->where('email', $email);
        $this->CI->db->where('password', $pass);
        $success = $this->CI->db->count_all_results('th_auser');
        //print_r ($success);
        if($success)
        {
            $this->CI->db->where('email', $email);
            $this->CI->db->where('password', $pass);
            $user_info = $this->CI->db->get('th_auser')->result_array();
            $user_data = $user_info[0];
            //print_r ($user_data);
            if(!$user_data['activate_code'])
            {
                $data = array('last_visit' => date("Y-m-d H:i:s"));
                $this->CI->db->update('auser', $data, array('id' => $user_data['id']));
            //    return $user_data['id'];
                return $user_data;
            }
        }
        return FALSE;
    }

    function access($module)
    {
        if(strlen($module) == 0){
            $this->CI->display->_content('Ошибка !!! У Вас нет прав к данному разделу.');
            $this->CI->display->_display();
            die;
        }

        if(!in_array($module, $this->CI->config->is_loaded_modules)) {
            $this->CI->display->_content('Ошибка !!! У Вас нет прав к данному разделу.');
            $this->CI->display->_display();
            die;
        }

        if($this->user['group_id'] == 2) return TRUE; //admin
        
        $this->CI->db->from('th_access')->where(array(
            'module' => $module,
            'group_id' => $this->user['group_id']
        ));

        if($this->CI->db->count_all_results() == 1){
            return TRUE;
        } else {
            $this->CI->display->_content('Ошибка !!! У Вас нет прав к данному разделу.');
            $this->CI->display->_display();
            die;
        }
    }
    
    function getUserName()
    {
        $userId=$this->getSessionId();
        $this->CI->db->where('id', $userId);
        $user_info=$this->CI->db->get('th_auser')->result_array();
        $user_data=$user_info[0];
        return $user_data['name'];
    }
}
?>
