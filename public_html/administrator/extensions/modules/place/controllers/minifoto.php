<?php if (!defined('BASEPATH')) exit('No direct script access allowed');        
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MiniFoto extends Admin_Controller
{
    function index()
    {
	$this->db->select('full_path, file_name');
	$this->db->from('photo');
	$Photo = $this->db->get()->result_array();

	foreach ($Photo as $_photo)
	{
	$this->photo_model->image_resize_mini($_photo['full_path'], $_photo['file_name']);
	
	}
    }
}
?>
