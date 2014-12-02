<?php

class LabsWeixin
{
	const MZ_WEIXIN_TOKEN 		= 'mz_weixin';
	const MZ_WEIXIN_OWNER 		= 'ohAy0jrch88p4PmOwfUkPl93nGyc';
	const MZ_TMP_POST_NAME		= 'mz_tmp_post_name';

	const MZ_WEIXIN_TABLE_MSG	= 'mz_labs_weixin_msg';
	const MZ_WEIXIN_TABLE_POST 	= 'mz_labs_weixin_post';

	const IDENTIFY_START        = "/:pig";
	const IDENTIFY_PRIVATE      = "/:hug";
	const IDENTIFY_END          = "/:ok";
	const IDENTIFY_CANCEL       = "/:bome";
	const IDENTIFY_HELP         = "/:?";
	const IDENTIFY_SHOW_WEB     = "/:8-)";
	const IDENTIFY_TITLE        = "/:basketb";

	function __construct()
	{
	}

	public function receiveMsg()
	{
		//get post data, May be due to the different environments
		if (!isset($GLOBALS["HTTP_RAW_POST_DATA"]))
		{
			return '...';
		}

		$post_str = $GLOBALS["HTTP_RAW_POST_DATA"];

		try
		{
			$this->msg 	= simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
			$curr_msg 	= $this->getMsgInfo($this->msg->MsgId);

			if ($curr_msg)
			{
				error_log("weixin: msg id: {$this->msg->MsgId} exist");

				// if complete handling
				if ($curr_msg->status == 1)
				{
					error_log("weixin: try to sent again...");
					return $this->responseMsg($curr_msg->response_msg . $this->getHelpInfo() . "\n---");
				}

				// wait for next call
				sleep(5);
				return;
			}

			// log mag
			$this->logWeixinMsg($post_str);

			// get exist post data
			$this->exist_post = $this->getExistTmpContent();

			// check if is owner
			if ($this->msg->FromUserName != self::MZ_WEIXIN_OWNER) {
				return $this->responseMsg("我是zhangge，\n留言已收到,欢迎上\n<a href=\"http://zhangge.me/everything.php\">zhangge.me</a>\n查看最新动态");
			}

			$response_text = "...";
			switch ($this->msg->MsgType)
			{
				case 'text':
					$response_text = $this->analysisText();
					break;
				case 'image':
					$response_text = $this->uploadImg();
					break;
				case 'location':
					$response_text = $this->uploadLocation();
					break;
				case 'voice':
				case 'link':
				case 'video':
				default:
					$response_text = "暂不支持该格式";
					break;
			}

			// update msg handling status
			$this->updateWeiMsgStatus();
			$this->logResponseMsg($response_text);
			return $this->responseMsg($response_text . $this->getHelpInfo());

		}
		catch (Exception $e)
		{
			return $this->responseMsg("...");
		}
	}

	private function responseMsg ($content)
	{
		$text_tpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";

		return sprintf($text_tpl, $this->msg->FromUserName, $this->msg->ToUserName, time(), 'text', $content);
	}

	private function analysisText()
	{
		$text = strtolower(urldecode(trim($this->msg->Content)));
		$msg  = '';

		switch ($text) {
			case self::IDENTIFY_START:
				$msg = $this->initNewPostContent();
				break;
			case self::IDENTIFY_PRIVATE:
				$msg = $this->initNewPostContent(true);
				break;
			case self::IDENTIFY_END:
				$msg = $this->submitNewContent();
				break;
			case self::IDENTIFY_CANCEL:
				$msg = $this->removeTmpContent();
				break;
			case self::IDENTIFY_HELP:
				$msg = "帮助快捷键：" . $this->getAdvancedHelp();
				break;
			case self::IDENTIFY_SHOW_WEB:
				$msg = "点击<a href=\"http://www.zhangge.me\">zhangge.me</a>";
				break;
			default:
				$msg = $this->addTextContent($text);
				break;
		}

		return $msg;
	}

	private function uploadImg ()
	{
		if (!$this->exist_post) {
			return "还未初始化...";
		}

		// save the link
		$this->exist_post->picture[] = (string) $this->msg->PicUrl;
		$this->updatePost(array('picture' => json_encode($this->exist_post->picture)));

		return "图片已接收,请继续";

		// $pic_url 	= $this->msg->PicUrl;
		// $file_name 	= rand() . '-' . time() . '.jpg';
		// $file_path  = public_path() . '/uploads' . self::MZ_MEDIA_PATH;

		// if (copy($pic_url, $file_path . $file_name)) {
		// 	$this->exist_post->picture[] = $file_name;
		// 	$this->updatePost(array('picture' => json_encode($this->exist_post->picture)));

		// 	return "上传成功,请继续";
		// } else {
		// 	return "上传失败,请重新上传";
		// }
	}

	private function uploadLocation ()
	{
		if (!$this->exist_post) {
			return "还未初始化...";
		}

		$location = array(
			"X"     => (string) $this->msg->Location_X,
			"Y"     => (string) $this->msg->Location_Y,
			"Scale" => (string) $this->msg->Scale,
			"Label" => (string) $this->msg->Label
		);

		$this->updatePost(array('location' => json_encode($location)));

		return "位置上传成功..." . $this->msg->Label;
	}

	private function getMsgInfo ($msg_id)
	{
		$msg_info = DB::table(self::MZ_WEIXIN_TABLE_MSG)->where('msg_id', '=', $msg_id)->first();

		return $msg_info;
	}

	private function initNewPostContent ($private = false)
	{
		if ($this->exist_post)
		{
			return "已经初始化，\n请继续写内容.";
		}

		DB::table(self::MZ_WEIXIN_TABLE_POST)->insert(array(
			'create_time' => time(),
			'from_user_name' => $this->msg->FromUserName,
			'permission_level' => $private ? 1 : 0
		));

		return "初始化成功，请开写.";
	}

	private function submitNewContent ()
	{
		if (!$this->exist_post)
		{
			return "还未初始化...";
		}

		if (!$this->exist_post->title && !$this->exist_post->content && !$this->exist_post->picture)
		{
			return "还木有内容，\n写点文字,传点东东";
		}

		$location 				= json_decode((string) $this->exist_post->location, true);
		$location 				= $location ? $location : array('X' => '', 'Y' => '', 'Label' => '');
		$blog 					= new Blog;
		$blog->location_label 	= $location['Label'];
		$blog->location_lon 	= $location['X'];
		$blog->location_lat 	= $location['Y'];

		if (!$blog->title)
		{
			$blog->title 		= $this->exist_post->content;
			$blog->content 		= '';
		}
		else
		{
			$blog->title 		= $this->exist_post->title;
			$blog->content 		= $this->exist_post->content;
		}
		$blog->save();

		foreach ($this->exist_post->picture as $picture)
		{
			$media 				= new Media();
			$media->blog_id 	= $blog->id;
			$media->media_type 	= 'pic';
			$media->from 		= 'weixin';
			$media->farm 		= -1;
			$media->src 		= $picture;
			$media->save();
		}

		$this->removeTmpContent();
		return "已经提交至网站";
	}

	private function removeTmpContent ()
	{
		DB::table(self::MZ_WEIXIN_TABLE_POST)
				->where('from_user_name', '=', $this->msg->FromUserName)
				->delete();

		return "已经取消所有内容";
	}

	private function addTextContent ($text)
	{
		if (!$this->exist_post)
		{
			return "请先初始化...";
		}

		// check if is post a title or content
		$regex 	= "(\\" . self::IDENTIFY_TITLE . ")";
		$update = array();

		if (preg_match("/^" . $regex . "[\s\S]+" . $regex . "$/i", $text))
		{
			$update['title'] = preg_replace("/^" . $regex . "|" . $regex . "$/i", "", $text);
		}
		else
		{
			$update['content'] = $this->exist_post->content ? $this->exist_post->content . "\n$text" : $text;
		}

		$this->updatePost($update);

		return "DONE, 请继续...";
	}

	private function logWeixinMsg ($fullText)
	{
		DB::table(self::MZ_WEIXIN_TABLE_MSG)->insert(array(
			'from_user_name' => $this->msg->FromUserName,
			'msg_id'         => $this->msg->MsgId,
			'create_time'    => $this->msg->CreateTime,
			'msg_type'       => $this->msg->MsgType,
			'full_request'   => $fullText
		));
	}

	private function logResponseMsg ($msg)
	{
		DB::table(self::MZ_WEIXIN_TABLE_MSG)
				->where('msg_id', '=', $this->msg->MsgId)
				->update(array('response_msg' => $msg));
	}

	private function updateWeiMsgStatus ($status = 1)
	{
		DB::table(self::MZ_WEIXIN_TABLE_MSG)
				->where('msg_id', '=', $this->msg->MsgId)
				->update(array('status' => $status));
	}

	private function getHelpInfo ()
	{
		$helpInfo  = "\n";
		$helpInfo .= "[" . self::IDENTIFY_START . "]初始化公共内容\n";
		$helpInfo .= "[" . self::IDENTIFY_PRIVATE . "]初始化只我可见内容\n";
		$helpInfo .= "[" . self::IDENTIFY_END . "]提交内容\n";
		$helpInfo .= "[" . self::IDENTIFY_TITLE . "][标题内容][" . self::IDENTIFY_TITLE . "]标题\n";
		$helpInfo .= "[" . self::IDENTIFY_CANCEL . "]取消所有消息\n";
		$helpInfo .= "[" . self::IDENTIFY_HELP . "]帮助\n";

		return $helpInfo;
	}

	private function getAdvancedHelp ()
	{
		$helpInfo  = "\n";
		$helpInfo .= "[" . self::IDENTIFY_SHOW_WEB . "]显示网站\n";

		return $helpInfo;
	}

	private function getExistTmpContent ()
	{
		// check if exist data in tmp table
		$exist_post = DB::table(self::MZ_WEIXIN_TABLE_POST)
						->where('from_user_name', '=', $this->msg->FromUserName)
						->first();

		if ($exist_post)
		{
			$picture = json_decode($exist_post->picture, true);
			$exist_post->picture = $picture ? $picture : array();
		}

		return $exist_post;
	}

	private function updatePost($update_data)
	{
		DB::table(self::MZ_WEIXIN_TABLE_POST)
				->where('id', '=', $this->exist_post->id)
				->update($update_data);
	}

	public static function valid()
	{
		$echo_str 	= Input::get('echostr');
		$signature 	= Input::get('signature');
		$timestamp 	= Input::get('timestamp');
		$nonce 		= Input::get('nonce');
		$tmp_arr 	= array(self::MZ_WEIXIN_TOKEN, $timestamp, $nonce);
		sort($tmp_arr);
		$tmp_str 	= implode($tmp_arr);
		$tmp_str 	= sha1( $tmp_str);

		if($tmp_str == $signature){
			echo $echo_str;
		}

		exit();
	}
}
?>