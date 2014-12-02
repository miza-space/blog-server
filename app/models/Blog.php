<?php

class Blog extends Eloquent {

	protected $table = 'mz_blogs';

	public static $rules = array
	(
	);

	public function medias()
	{
		return $this->hasMany('Media', 'blog_id');
	}

	public function locations()
	{
		if ($this->location_label)
		{
			return array(
				'lon' => $this->location_lon,
				'lat' => $this->location_lat,
				'label' => $this->location_label,
			);
		}
		return null;
	}

	public function getLocationAttribute()
	{
		return $this->location_label;
	}

	public static function getBlogs($skip = 0, $take = 18)
	{
		$skip = $skip < 0 ? 0 : $skip;
		$take = $take < 1 ? 1 : $take;
		$latest_blog_num = Config::get('blog.latest_blogs_num');
		$blogs = self::orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
		$return_data = array();

		foreach ($blogs as $blog)
		{
			$medias = $blog->medias;
			$medias_data = array();

			foreach ($medias as $media)
			{
				$media_type = $media->media_type;
				if (!$media_type) continue;

				if (!isset($medias_data[$media_type]))
				{
					$medias_data[$media_type] = array();
				}

				$medias_data[$media_type][] = array(
					'path' 	=> $media->media_path,
					'src' 	=> $media->image_size('w-640')
				);
			}

			$data = array(
				'id' 			=> $blog->id,
				'title' 		=> $blog->title,
				'content' 		=> $blog->content,
				'media'			=> $medias_data,
				'location' 		=> $blog->locations(),
				'created_at' 	=> $blog->created_at->toFormattedDateString()
			);

			$return_data[] = $data;
		}

		return $return_data;
	}
}