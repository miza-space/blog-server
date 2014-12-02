<?php

class BlogBg extends Eloquent {

	protected $table = 'mz_blog_bg';

	public static $rules = array
	(
	);

	public function getImageShowAttribute()
	{
		return '<img src="/uploads/'.$this->farm.'/w-60-'.$this->src.'" />';
	}
}