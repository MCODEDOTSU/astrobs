<?php
	class Library extends Public_Controller {

		function Library() {
		    parent::Public_Controller();
		}
		
		function index() {
		
			$Library = $this->library_model->get();

			$Category = $this->db->get('th_library_category')->result_array();
		 
	        foreach ($Library as $lib) {
        
				foreach ($Category as $category) {
					if ($lib['category'] == $category['id']) $categ = $category['title'];
				}

		        $data['library'][] = array(
		            'name'      => $lib['name'],
		            'author'	=> $lib['author'],
		            'year'		=> $lib['year'],
		            'category'  => $categ,
		            'size'		=> $lib['size'],
		            'download'       => anchor(base_url().'uploads/library/'.$lib['document'], 'Скачать', 'title="Скачать"')
		        );
        	}

        	$this->module->parse('library', 'library.php', $data);
        
		}
		
	}
?>
