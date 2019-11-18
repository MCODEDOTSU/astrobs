<?php
	class Folders extends Admin_Controller {

		function Folders () {
			parent::Admin_Controller ();
		}

		function index () {

		}

		function edit () {
			$id = $this->uri->segment (5);
			$parentId = $this->folders_model->getParentId ($id);
			if ($parentId == 257 || $parentId == 259) {
				$this->_image ();
			} else {
				$this->_typeFold ();
			}
		}

		// Управление раззделом
		function _typeFold () {
			$id = $this->uri->segment (5);
			$folder = $this->folders_model->get (array ('id' => $id));
			$types = $this->folders_model->getFolderTypes ();
			//print_r ($folder);
			$data = array (
				'form_open'			=>	form_open (site_url (array ('admin', 'folders', 'folders', 'save_type'))),
				'form_close'		=>	form_close (),
				'title'				=>	$folder[0]['title'],
				'form_submit'		=>	form_submit ('submit', 'Сохранить'),
				'form_select_fold'	=>	form_dropdown ('type_folder', $types, $folder[0]['type_id']),
				'folder_id'			=>	form_hidden ('folder_id', $id)
			);

			$this->module->parse ('folders', 'folders_type_form.php', $data);

		}

		// Добавление картинок к турам
		function _image () {
			$id = $this->uri->segment (5);
			$els = $this->folders_model->get (array ('id' => $id));

			$img1 = ($els[0]['img1'] != 'none') ? '<img src="' . site_url (array ('uploads', 'folders', 'img1_' . $els[0]['id'] . '.' . $els[0]['img1'])) . '"><br>' . anchor ('#', 'Удалить картинку', array ('onclick' => 'return false;', 'class' => 'clicker', 'id' => 'aimg1')) : form_upload ('img1');
			$img2 = ($els[0]['img2'] != 'none') ? '<img src="' . site_url (array ('uploads', 'folders', 'img2_' . $els[0]['id'] . '.' . $els[0]['img2'])) . '"><br>' . anchor ('#', 'Удалить картинку', array ('onclick' => 'return false;', 'class' => 'clicker', 'id' => 'aimg2')) : form_upload ('img2');
			$img3 = ($els[0]['img3'] != 'none') ? '<img src="' . site_url (array ('uploads', 'folders', 'img3_' . $els[0]['id'] . '.' . $els[0]['img3'])) . '"><br>' . anchor ('#', 'Удалить картинку', array ('onclick' => 'return false;', 'class' => 'clicker', 'id' => 'aimg3')) : form_upload ('img3');
			$data = array (
				'title'			=>	$els[0]['title'],
				'form_open'		=>	form_open_multipart (site_url (array ('admin', 'folders', 'folders', 'save'))),
				'img1'			=>	'<div id="img1">' . $img1 . '</div>',
				'img2'			=>	'<div id="img2">' . $img2 . '</div>',
				'img3'			=>	'<div id="img3">' . $img3 . '</div>',
				'form_button'	=>	form_submit ('submit', 'Сохранить'),
				'form_delete'	=>	'<input type="hidden" name="form_delete" id="form_delete" value="[]" />',
				'form_id'		=>	form_hidden ('id', $id),
				'form_close'	=>	form_close (),
				'form_ftext'	=>	form_ckeditor ('ftext', $els[0]['desc_tour'])
			);
			$this->module->parse ('folders', 'folders_form.php', $data);
		}

		// Сохранение всяких настроек для разделов
		function save_type () {
			if (!$this->input->post ('submit')) redirect (site_url (array ('admin'))); 

			$id = $this->input->post ('folder_id');
			$type = $this->input->post ('type_folder');

			$this->folders_model->update (array ('type_id' => $type), array ('id' => $id));
		
			redirect (site_url (array ('admin', 'place', 'place')));
		}

		// Сохранение картинок для туров
		function save () {
			$id = $this->input->post ('id');

			$arrImgs = json_decode ($this->input->post ('form_delete'));
			$setImg = array ();
			$fold = $this->folders_model->get (array ('id' => $id));
			foreach ($arrImgs AS $v) {
				$setImg[$v] = 'none';
				unlink ($this->input->server ('DOCUMENT_ROOT') . '/uploads/folders/' . $v . '_' . $id . '.' . $fold[0][$v]);
			}

			$this->folders_model->update ($setImg, array ('id' => $id));
			
			
			$img = array ();
			foreach ($_FILES AS $k => $v) {
				$img[$k] = $this->folders_model->getImg ($v, $id, $k . '_');
			}

			$set = array ();
			
			foreach ($img AS $k => $v) {
				if ($v != 'none') {
					$set[$k] = $v;
				}
			}
			$set['desc_tour'] = $this->input->post ('ftext');
			$this->folders_model->update ($set, array ('id' => $id));
			
			//redirect (site_url (array ('admin', 'folders', 'folders', 'edit', $id)));
			redirect (site_url (array ('admin', 'place', 'place')));
		}
	}
?>
