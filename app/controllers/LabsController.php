<?php

class LabsController extends BaseController {

	public function weixin()
	{
		$weixin = new LabsWeixin();
		echo $weixin->receiveMsg();
	}

	public function fetchMedia($source)
	{
		$fetch = new LabsFetchMedia();
		echo $fetch->fetchMedia($source);
	}
}
