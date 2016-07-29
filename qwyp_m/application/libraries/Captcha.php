<?php
/*
 * 图片验证码
 * @author john 20150705
 * 
 * */
class captcha
{
	/**
	 * 背景图片所在目录
	 * @var string  $folder
	 */
	protected $folder     = "font/";

	/**
	 * 图片的文件类型
	 *
	 * @var string  $img_type
	 */
	protected $img_type   = 'png';

	/*------------------------------------------------------ */
	//-- 存在session中的名称
	/*------------------------------------------------------ */
	protected $session_key = '';

	/**
	 * 背景图片以及背景颜色
	 *
	 * 0 => 背景图片的文件名
	 * 1 => Red, 2 => Green, 3 => Blue
	 * @var array   $themes
	 */
	protected $themes_jpg = array(
			1 => array('captcha_bg1.jpg', 255, 255, 255),
			2 => array('captcha_bg2.jpg', 0, 0, 0),
			3 => array('captcha_bg3.jpg', 0, 0, 0),
			4 => array('captcha_bg4.jpg', 255, 255, 255),
			5 => array('captcha_bg5.jpg', 255, 255, 255),
	);

	protected $themes_gif = array(
			1 => array('captcha_bg1.gif', 255, 255, 255),
			2 => array('captcha_bg2.gif', 0, 0, 0),
			3 => array('captcha_bg3.gif', 0, 0, 0),
			4 => array('captcha_bg4.gif', 255, 255, 255),
			5 => array('captcha_bg5.gif', 255, 255, 255),
	);

	/**
	 * 图片的宽度
	 * @var integer $width
	 */
	public $width      = 90;

	/**
	 * 图片的高度
	 * @var integer $height
	 */
	public $height     = 30;


	/**
	 * 构造函数
	 * @access  public
	 * @param   string  $folder     背景图片所在目录
	 * @param   integer $width      图片宽度
	 * @param   integer $height     图片高度
	 * @return  bool
	 */
	function __construct($width=80, $height=30, $session_key='captcha', $folder='')
	{
		if (empty($folder))
		{
			$this->folder = FCPATH.$this->folder;
		}
		$this->width    	= $width;
		$this->height   	= $height;
		$this->session_key  = $session_key;

		/* 检查是否支持 GD */
		if (PHP_VERSION >= '4.3')
		{
			return (function_exists('imagecreatetruecolor') || function_exists('imagecreate'));
		}
		else
		{
			return (((imagetypes() & IMG_GIF) > 0) || ((imagetypes() & IMG_JPG)) > 0 );
		}
	}


	/**
	 * 检查给出的验证码是否和session中的一致
	 * @access  public
	 * @param   string  $word   验证码
	 * @return  bool
	 */
	function check($word)
	{
		$recorded = isset($_SESSION[$this->session_key]) ? base64_decode($_SESSION[$this->session_key]) : '';
		$given    = $this->encrypts_word(strtoupper($word));
		return (preg_match("/$given/", $recorded));
	}

	/**
	 * 生成图片并输出到浏览器
	 * @access  public
	 * @param   string  $word   验证码
	 * @return  mix
	 */
	function generate($word = false)
	{
		if (!$word)
		{
			$word = $this->generate_word();
		}

		/* 记录验证码到session */
		$this->record_word($word);

		/* 验证码长度 */
		$letters = strlen($word);

		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);

		if (function_exists('imagecreatefromjpeg') && ((imagetypes() & IMG_JPG) > 0))
		{
			$theme  = $this->themes_jpg[mt_rand(1, count($this->themes_jpg))];
		}
		else
		{
			$theme  = $this->themes_gif[mt_rand(1, count($this->themes_gif))];
		}

		/* if (file_exists($this->folder . $theme[0]))
		{
			return false;
		}
		else
		{ */
			$width=$this->width;
			$height=$this->height;
			$code=$word;
			$im = imagecreatetruecolor($width, $height);
			// 定义要用到的颜色
			$back_color = imagecolorallocate($im, 255, 255, 255);
			$boer_color = imagecolorallocate($im, 255, 255, 255);
			$text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
			// 画背景
			imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
			// 画边框
			imagerectangle($im, 0, 0, $width - 1, $height - 1, $boer_color);
			// 画干扰线
			for ($i = 0; $i < 10; $i++) {
				$font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
				imagearc($im, mt_rand(-$width, $width), mt_rand(-$height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
			}
			// 画干扰点
			for ($i = 0; $i < 80; $i++) {
				$font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
				imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
			}
                    
			// 画验证码
			if($height>=30 && $height<35) {
				@imagefttext($im, 22, 0, 5, 25, $text_color, $this->folder.'SNICN___.TTF', $code);
			}
			elseif($height>=35 && $height<40) {
				@imagefttext($im, 24, 0, 10, 30, $text_color, $this->folder.'SNICN___.TTF', $code);
			}
			elseif($height>=40 && $height<45) {
				@imagefttext($im, 28, 0, 10, 34, $text_color, $this->folder.'SNICN___.TTF', $code);
			}
			elseif($height>=45 && $height<=50) {
				@imagefttext($im, 32, 0, 10, 40, $text_color, $this->folder.'SNICN___.TTF', $code);
			}
			 
			header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
			
			// HTTP/1.1
			header('Cache-Control: private, no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0, max-age=0', false);

			// HTTP/1.0
			header('Pragma: no-cache');
			header("Content-type: image/png;charset=utf-8");
			imagepng($im);
			imagedestroy($im);

			return true;
		//}
	}

	/*------------------------------------------------------ */
	//-- PRIVATE METHODs
	/*------------------------------------------------------ */

	/**
	 * 对需要记录的串进行加密
	 *
	 * @access  private
	 * @param   string  $word   原始字符串
	 * @return  string
	 */
	function encrypts_word($word)
	{
		return substr(md5($word), 1, 10);
	}

	/**
	 * 将验证码保存到session
	 *
	 * @access  private
	 * @param   string  $word   原始字符串
	 * @return  void
	 */
	function record_word($word)
	{
		$_SESSION[$this->session_key] = base64_encode($this->encrypts_word($word));
	}

	/**
	 * 生成随机的验证码
	 *
	 * @access  private
	 * @param   integer $length     验证码长度
	 * @return  string
	 */
	function generate_word($length = 4)
	{
		// $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		//纯字母
		$chars = '123456789ABEFHKMNPQRSTUVWXY';

		for ($i = 0, $count = strlen($chars); $i < $count; $i++)
		{
			$arr[$i] = $chars[$i];
		}

		mt_srand((double) microtime() * 1000000);
		shuffle($arr);

		return substr(implode('', $arr), 5, $length);
	}
}