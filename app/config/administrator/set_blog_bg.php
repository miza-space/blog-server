<?php
$farm = rand(1, 10);
$farm = 1;
$upload_path = public_path() . "/uploads/{$farm}/";
return array(

	'title' => 'Blog Background',

	'single' => 'Background',

	'model' => 'BlogBg',

	'form_width' => 500,
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'status',
		'farm',
		'src',
		'image_show',
		'created_at',
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'status' => array(
			'type' => 'bool'
		)
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'status' => array(
			'type' => 'bool'
		),
		'farm' => array(
			'value' => $farm,
			'editable' => false
		),
		'src' => array(
			'type' => 'image',
			'location' => $upload_path,
			'naming' => 'random',
			'length' => 20,
			'size_limit' => 4,
			'sizes' => array(
				// cut with width=320, the height will auto scall
				array(60, 0, 'crop', $upload_path . 'w-60-', 100),
				array(640, 0, 'auto', $upload_path . 'w-640-', 100),
				array(960, 0, 'auto', $upload_path . 'w-960-', 100),
				array(1280, 0, 'auto', $upload_path . 'w-1280-', 100),
				array(1600, 0, 'auto', $upload_path . 'w-1600-', 100),
			)
		),
	),

);