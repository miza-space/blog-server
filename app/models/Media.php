<?php

class Media extends Eloquent {

	protected $table = 'mz_media';

	public static $rules = array
	(
	);

	public function blog()
	{
		return $this->belongsTo('Blog');
	}

	public function image_size($size)
	{
		if ($this->farm < 0)
		{
			return $this->src;
		}

		return '/uploads/' . $this->farm . '/' . $size . '-' . $this->src;
	}

	public function getMediaShowAttribute()
	{
		return '<img style="width:60px;" src="/uploads/' . $this->farm . '/w-60-' . $this->src . '" />';
	}
}