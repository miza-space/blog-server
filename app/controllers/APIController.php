<?php

class APIController extends BaseController {

	public function blogs($skip = 0, $take = 18)
	{
		$blogs = Blog::getBlogs($skip, $take);

		return Response::json(array(
			'skip' => $skip,
			'take' => $take,
			'is_all' => count($blogs) < $take ? true : false,
			'view' => View::make('layouts.blog', array('data' => $blogs)) . ''
		));
	}
}
