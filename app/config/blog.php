<?php
return array(
	'media' => array(
		'farm' => 1
	),

	'page_cache' => array(
		'key' => 'blog_page_conf_cache',
		'expire' => 1,
		'bg_size_map' => array('other' => '1280', '1024' => '960', '640' => '640') // max screen with mapping bg size
	),

	'assets' => array(
		'_common' => array(
			'css' => array('/css/reset.css', '/css/common.css'),
			'js' => array('/js/jquery.min.js', '/js/jquery.cookie.js', '/js/common.js')
		),
		'index' => array(
			'css' => array('/css/blueimp-gallery.css', '/css/timeline.css'),
			'js' => array('/js/blueimp-gallery.min.js', '/js/timeline.js')
		),
		'profile' => array(
			'js' => array('/js/profile.js'),
			'css' => array('/css/profile.css')
		),
		'picture' => array(
			'js' => array('/js/blueimp-gallery.min.js', '/js/jquery.blueimp-gallery.js', '/js/picture.js'),
			'css' => array('/css/blueimp-gallery.css', '/css/picture.css')
		)
	)
);