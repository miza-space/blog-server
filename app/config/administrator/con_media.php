<?php

$media_conf 	= Config::get('blog.media');
$farm 			= $media_conf['farm'];
$upload_path 	= public_path() . "/uploads/{$farm}/";

return array(

	'title' => 'Media Admin',

	'single' => 'Media',

	'model' => 'Media',

	'form_width' => 500,

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'blog_id',
		'media_type',
		'media_show',
		'desc',
		'created_at',
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'media_type' => array(
			'type' => 'enum',
			'options' => array('pic', 'audio', 'video')
		),
		'blog' => array(
			'type' => 'relationship',
			'name_field' => 'title',
			// 'value' => -1,
		),
		// 'farm' => array(
		// 	'type' => 'enum',
		// 	'options' => array('1','2','3','4','5','6','7','8','9')
		// ),
		'desc'
	// 	'column' => array(
	// 		'type' => 'relationship',
	// 		'name_field' => 'name',
	// 	),
	// 	'author' => array(
	// 		'type' => 'relationship',
	// 		'name_field' => 'nick',
	// 	),
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'media_type' => array(
			'type' => 'enum',
			'options' => array('pic', 'audio', 'video')
		),
		'blog' => array(
			'type' => 'relationship',
			'name_field' => 'title',
		),
		'src' => array(
			'type' => 'image',
			'location' => $upload_path,
			'naming' => 'random',
			'length' => 20,
			'size_limit' => 8,
			'sizes' => array(
				// cut with width=320, the height will auto scall
				array(60, 0, 'crop', $upload_path . 'w-60-', 100),
				array(640, 0, 'auto', $upload_path . 'w-640-', 100),
				array(960, 0, 'auto', $upload_path . 'w-960-', 100),
				array(120, 120, 'crop', $upload_path . 's-120-', 100),
				array(640, 640, 'crop', $upload_path . 's-640-', 100),
			)
		),
		'farm' => array(
			'value' => $farm,
			'editable' => false
		),
		'desc' => array(
			'type' => 'text',
		),
		// 'column' => array(
		// 	'type' => 'relationship',
		// 	'name_field' => 'name',
		// ),
		// 'author' => array(
		// 	'type' => 'relationship',
		// 	'name_field' => 'nick',
		// ),
	),

);