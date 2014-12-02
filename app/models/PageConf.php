<?php

class PageConf
{
	
	public static function makePageAssets()
	{
		$roue 	= Route::currentRouteName();
		$assets = Config::get('blog.assets', array());
		$common = $assets['_common'];
		$page 	= isset($assets[$roue]) ? $assets[$roue] : array();
		$return = array('js' => '', 'css' => '');

		if (isset($common['js'])) {
			foreach ($common['js'] as $js) {
				$return['js'] .= '<script type="text/javascript" src="' . $js . '"></script>';
			}
		}
		if (isset($common['css'])) {
			foreach ($common['css'] as $css) {
				$return['css'] .= '<link rel="stylesheet" href="' . $css . '" />';
			}
		}
		if (isset($page['js'])) {
			foreach ($page['js'] as $js) {
				$return['js'] .= '<script type="text/javascript" src="' . $js . '"></script>';
			}
		}
		if (isset($page['css'])) {
			foreach ($page['css'] as $css) {
				$return['css'] .= '<link rel="stylesheet" href="' . $css . '" />';
			}
		}

		return $return;
	}

	public static function makeCustomerPageStyle()
	{
		$cache_conf = Config::get('blog.page_cache');

		// if debug, forget cache
		if (Config::get('app.debug'))
		{
			Cache::forget($cache_conf['key']);
		}

		$styles = Cache::remember($cache_conf['key'], $cache_conf['expire'], function () use ($cache_conf)
		{
			$page_conf 	= array();
			$blog_bgs 	= BlogBg::where('status', 1)->get();

			foreach ($blog_bgs as $bg) {
				$base_path 	= '/uploads/' . $bg->farm;
				$style		= '';

				foreach ($cache_conf['bg_size_map'] as $screen_size => $bg_size)
				{
					if ($screen_size == 'other')
					{
						$style .= '.page-bg, .sidebar {background-image: url(' . $base_path . '/w-1280-' . $bg->src . ');}';
						continue;
					}

					$imge_path 	= $base_path . '/w-' . $bg_size . '-' . $bg->src;
					$style 		.= '@media screen and (max-width: '.$screen_size.'px) {.page-bg, sidebar {background-image: url('.$imge_path.');}}';
				}
				$page_conf[] = $style;
			}
			return $page_conf;
		});

		$rand_style = rand(0, count($styles) - 1);
		return $styles[$rand_style];
	}
}
?>
