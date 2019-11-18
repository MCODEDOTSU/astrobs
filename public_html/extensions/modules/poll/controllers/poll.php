<?php
	class Poll extends Public_Controller
	{
		function Poll()
		{
		    parent::Public_Controller();
		}
		
		function get()
		{
		    // Получаем в массиве все опросы
		    $Polls = $this->poll_model->get();
		    
		    if(count($Polls) == 0) return FALSE;
		    
		    foreach($Polls as $poll)
		    {
		        // Если пользователь уже голосовал то показываем результат текущего опроса
		        if(isset($_COOKIE['poll'.$poll['id']])) 
		        {
		            $this->poll_finish($poll['id']);
		            continue;
		        }
		        
		        $data = array(
		            'id'        => $poll['id'],
		            'title'     => $poll['title'],
		            'desc'      => $poll['desc'],
		            'created'   => date('d.m.Y', strtotime($poll['created'])),
		            'submit'    => anchor('#', 'Голосовать','onclick="pollSend(this); return false;" class="vote"'),
		            'poll_all'  => anchor('poll/poll/view','Архив опросов', 'class="polls_archive"')
		        );
		        
		        
		        //==================================
		            $Answers = $this->poll_answer_model->extra(array('poll_id' => $poll['id']));
		            
		            if(count($Answers) == 0) continue;
		            
		            $count = 0;
		            
		            foreach($Answers as $answer)
		            {
		                $data['answers'][] = array(
		                    'answer' => '<label>' . form_radio('answer', $answer['id'], false, 'poll="'.$poll['id'].'"') . $answer['text'] . '</label>'
		                );
		                $count += $answer['count'];
		            }
		        //==================================
		        
		        $data['count_all'] = 'Итого проголосовало: '.$count;
		        
		        echo $this->module->parse('poll', 'block.php', $data, TRUE);
		    }
		    
		    die;
		}
		
		function send()
		{
		    $poll_value = $this->input->post('value');
		    $poll_id = $this->input->post('poll_id');
		    
		    if(!is_numeric($poll_value)) die;
		    if(!is_numeric($poll_id)) die;

		    setcookie('poll'.$poll_id, $poll_value, time()+500000);

		    $Answer = $this->poll_answer_model->extra(array('id'=>$poll_value));
		    $count = $Answer[0]['count'];
		    $count++;
		    
		    $this->poll_answer_model->update(array('count'=> $count), array('id'=>$poll_value));
		    
		    $this->poll_finish($poll_id);
		    die;        
		}
		
		function poll_finish($pollId)
		{
		    // Получаем данные по конкретному опросу
		    $Poll = $this->poll_model->extra(array('id' => $pollId));
		    
		    foreach($Poll as $p)
		    {
			
		if ($p['archive'] == 0) {
			
		        $data = array(
		            'id'        => $p['id'],
		            'title'     => $p['title'],
		            'desc'      => $p['desc'],
		            'created'   => date( 'd.m.Y', strtotime($p['created'])),
		            'submit'    => '',
		            'poll_all'  => anchor('poll/poll/view','Архив опросов', 'class="polls_archive"')
		        );
		        
		        //==================================
		            $Answers = $this->poll_answer_model->extra(array('poll_id' => $pollId));
		            
		            if(count($Answers) == 0) continue;
		            
		            $count = 0;
		            
		            foreach($Answers as $answer)
		            {
		                $data['answers'][] = array(
		                    'answer' => '<label>' . $answer['text'].' - <div class="votes">' . $answer['count'] . '</div></label>'
		                );
		                $count += $answer['count'];
		            }
		        //==================================
		        $data['count_all'] = 'Итого проголосовало: '.$count;
		        echo $this->module->parse('poll', 'block.php', $data, TRUE);     
			
				}
			
		    }
		}
		
		function view()
		{
		    $Polls = $this->poll_model->get();
		    
		    if(count($Polls) == 0) return FALSE;
		    
		    foreach($Polls as $poll)
		    {
		        
				if ($poll['archive'] == 1) {
			
					$data = array(
						'title'     => $poll['title'],
						'desc'      => $poll['desc'],
						'created'   => date('d.m.Y', strtotime($poll['created']))
					);
				
				
					//==================================
						$Answers = $this->poll_answer_model->extra(array('poll_id' => $poll['id']));
					
						if(count($Answers) == 0) continue;
					
						$count = 0;
					
						foreach($Answers as $answer)
						{
							$data['answers'][] = array(
								'text' => $answer['text'],
								'count' => $answer['count']
							);
							$count += $answer['count'];
						}
					//==================================
				
					$data['count_all'] = $count;
		        
					$this->display->_content($this->module->parse('poll', 'view.php', $data, TRUE));
				}
			
		    }
		}
		
	}
?>
