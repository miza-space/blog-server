<?php
/**
* fetching media outside
*/
class LabsFetchMedia
{
	const RETRIES = 3;
	function __construct()
	{
		$this->retries = 0;
	}

	public function fetchMedia($source)
	{
		$media = Media::where('from', '=', $source)
			->where('farm', '=', -1)
			->orderBy('id', 'asc')
			->first();

		if (!$media)
		{
			return 'done';
		}

		$media_conf = Config::get('blog.media');
		$file_dir 	= public_path() . '/uploads/' . $media_conf['farm'];
		$file_name	= rand() . '-' . time() . '.jpg';
		$file_path	= $file_dir . '/' . $file_name;
		if (copy($media->src, $file_path))
		{
			$thumb = new EasyPhpThumbnail();
			// width: 60
			$thumb->Thumbprefix = $file_dir . '/w-60-';
			$thumb->Thumbsize = 60;
			$thumb->Createthumb($file_path, 'file');

			// width: 640
			$thumb->Thumbprefix = $file_dir . '/w-640-';
			$thumb->Thumbsize = 640;
			$thumb->Createthumb($file_path, 'file');

			// width: 960
			$thumb->Thumbprefix = $file_dir . '/w-960-';
			$thumb->Thumbsize = 960;
			$thumb->Createthumb($file_path, 'file');

			// Square: 120
			$thumb->Thumbprefix = $file_dir . '/s-120-';
			$thumb->Thumbsize = 120;
			$thumb->Cropimage = array(3,0,0,0,0,0);
			$thumb->Square = true;
			$thumb->Createthumb($file_path, 'file');

			// Square: 640
			$thumb->Thumbprefix = $file_dir . '/s-640-';
			$thumb->Thumbsize = 640;
			$thumb->Cropimage = array(3,0,0,0,0,0);
			$thumb->Square = true;
			$thumb->Createthumb($file_path, 'file');

			Media::where('id', '=', $media->id)->update(array('farm' => $media_conf['farm'], 'src' => $file_name));

			return 'ok';
		}
		else
		{
			if ($this->retries > self::RETRIES)
			{
				return 'error';
			}
			$this->retries += 1;
			$this->fetchMedia();
		}
	}
}
?>