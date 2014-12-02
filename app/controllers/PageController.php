<?php

class PageController extends BaseController {

	public function index($page = 0)
	{
		$skip = $page * 40;
		$take = 18;
		$blogs = Blog::getBlogs($skip, $take);
		return View::make('index', array('data' => $blogs, 'skip' => $skip, 'take' => $take, 'page' => $page));
	}

	public function profile()
	{
		if (Input::has('mail')) {
			$address = array("81228741@qq.com", "zhang096608@gmail.com");
			foreach ($address as $value) {
				Mail::queue('music', array(), function ($message) use ($value)
				{
					$message->to($value)->subject('zhangge');

				});
			}
			die('222222');
		}
		return View::make('profile');
	}

	public function picture()
	{
		$pictures = Media::where('media_type', '=', 'pic')->orderBy('id', 'DESC')->get();
		return View::make('picture', array('pictures' => $pictures));
	}

	public function music()
	{
		return View::make('music');
	}

	public function labs()
	{
		return View::make('labs');
	}
}
