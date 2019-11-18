<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Orders extends Admin_Controller {
	
		function Orders () {
			parent::Admin_Controller();
        	parent::access('struct_tours');
		}

		function table () {
			$tid = $this->uri->segment (5);
			$OrdName = $this->orders_model->getName ($tid);
			$this->display->_content(anchor (site_url (array ('admin', 'struct_tours', 'struct_tours')), 'Виды туров'));
        	$this->display->_content('<h2>'._icon('chart_curve_add'). $OrdName . '</h2>');
			
        	$Orders = $this->orders_model->getOrds ($tid);
        
		    if(count($Orders) == 0) return FALSE;
		    
		    foreach($Orders as $order)
		    {
		        $data['orders'][] = array(
		            'id'        =>	$order['id'],
		            'client'    =>	$order['name'],
		            'actions'   =>	anchor('admin/struct_tours/orders/order/'.$order['id'], _icon('chart_curve_delete'), 'title="Просмотр заказа"').
		            				anchor('admin/struct_tours/orders/remove/'.$order['id'], _icon('chart_curve_delete'), 'title="Удалить"')
		        );
		    }
		    

			
		    $this->_show('orders.php', $data);
		}

		function _show($view = '', $data = array(), $return = FALSE)
		{
		    return $this->module->parse('struct_tours', $view, $data, $return);
		}

		function remove () {
			$id = (int) $this->uri->segment (5);
			$link =$this->orders_model->getLink ($id);
			$this->orders_model->delete (array ('id' => $id));
			redirect ($link);
		}

		function order () {
			$id = $this->uri->segment (5);
			$Orders = $this->orders_model->get (array ('id' =>$id));
			$data = array (
				'name'		=>	$Orders[0]['name'],
				'email'		=>	$Orders[0]['email'],
				'phone'		=>	$Orders[0]['phone'],
				'catName'	=>	$this->orders_model->getCatName ($id),
				'nums'		=>	$Orders[0]['nums'],
				'sdate'		=>	$Orders[0]['sday'] . ' ' . $this->_getMoons ($Orders[0]['smoon']),
				'edate'		=>	$Orders[0]['eday'] . ' ' . $this->_getMoons ($Orders[0]['emoon']),
				'needs'		=>	$Orders[0]['needs'],
				'more'		=>	$Orders[0]['more'],
				'link'		=>	anchor ($this->orders_model->getLink ($id),'Вернуться на страницу заказов')
			);
			
			$this->_show('ordinfo.php', $data);
		}

		function _getMoons ($item) {
			$moons = array (1 =>
				'Января',
				'февраля',
				'Марта',
				'Апреля',
				'Мая',
				'Июня',
				'Июля',
				'Августа',
				'Сентября',
				'Октября',
				'Ноября',
				'Декабря'
			);
			return $moons[$item];
		}
	}

?>
