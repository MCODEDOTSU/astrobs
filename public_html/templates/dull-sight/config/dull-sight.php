<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

	$config['page_title'] = 'Астраханская областная библиотека для слепых';

	$config['meta_tags'] = array(
		'content-type' => array(
			'type'     => 'http-equiv',
			'content'  => 'text/html; charset=UTF-8'
		)
	);

	$config['assets'] = array(
		'css' => array(
			'layout.css', 'lightbox-0.5.css'
		),
		'js' => array(
			'jquery-1.4.2.min.js', 'lightbox-0.5.min.js', 'p2q_embed_object.js'
		)
	);
	
	$config['special_assets'] = array(
		'ver1'	=>	array(
			'css' => array(
				'layout.css', 'lightbox-0.5.css'
			),
			'js' => array(
				'jquery-1.4.2.min.js', 'lightbox-0.5.min.js', 'p2q_embed_object.js'
			)
		),
		'ver2'	=>	array(
			'css' => array(
				'lightbox-0.5.css', 'layout_v2.css'
			),
			'js' => array(
				'jquery-1.4.2.min.js', 'lightbox-0.5.min.js', 'p2q_embed_object.js'
			)
		)
	);
	
?>
