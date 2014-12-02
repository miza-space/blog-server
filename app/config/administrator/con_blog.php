<?php

return array(

	'title' => 'Blog Admin',

	'single' => 'Blog',

	'model' => 'Blog',

	'form_width' => 500,
	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'title',
		// 'photos',
		'location',
		'created_at',
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'id',
		'title',
		'content'
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'title' => array(
			'type' => 'text',
		),
		'content' => array(
			'type' => 'textarea',
		),
		'location_label' => array(
			'type' => 'text',
		),
		'location_lon' => array(
			'type' => 'text',
		),
		'location_lat' => array(
			'type' => 'text',
		),
	),

);