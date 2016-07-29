<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//图片处理公共函数 
	
	/**
	 * 生成正方形压缩图
	 * @param string $sourcePath 源图片的路径
	 * @param string $saveFile  保存图片路径
	 * @param int $w_h  指定宽高
	 * @param int $rate  压缩比率
	 */
	function resize_square_img($sourcePath, $saveFile, $w_h, $rate=90) {
		ini_set('memory_limit', '100M');
		set_time_limit(0);
		$imgSize = getimagesize($sourcePath);
		$imgW = $imgSize[0];
		$imgH = $imgSize[1];
		$WH = min($imgW, $imgH);
		if($WH < $w_h) {
			$w_h = $WH;
		}
		switch($imgSize['mime']){
			case "image/gif" : $resourceImg = imagecreatefromgif($sourcePath);
			break;
			case "image/jpeg" : $resourceImg = imagecreatefromjpeg($sourcePath);
			break;
			case "image/png" : $resourceImg = imagecreatefrompng($sourcePath);
			break;
		}
		$tcimg = ImageCreateTrueColor($w_h, $w_h);
		$back = imagecolorallocate($tcimg, 255, 255, 255);
		imagefilledrectangle($tcimg, 0, 0, $w_h, $w_h, $back);
		imagecopyresampled($tcimg, $resourceImg, 0, 0, 0, 0, $w_h, $w_h, $WH, $WH);
	
		//header("Content-Type:image/jpeg");    //所有压缩图片最终保存成jpg格式
		$result = imagejpeg($tcimg, $saveFile, $rate);
		imagedestroy($tcimg);
		return $result;
	}
	
	/**
	 * 生成固定宽和高的压缩图
	 * @param string $sourcePath 源图片的路径
	 * @param string $saveFile  保存图片路径
	 * @param int $fixed_w  固定的宽
	 * @param int $fixed_h  固定的高
	 * @param int $rate  压缩比率
	 */
	function resize_fixed_img($sourcePath, $saveFile, $fixed_w, $fixed_h, $rate=90)
	{
		ini_set('memory_limit', '100M');
		set_time_limit(0);
		$imgSize = getimagesize($sourcePath);
		$imgW = $imgSize[0];
		$imgH = $imgSize[1];
		
		$rate_w = $imgW/$fixed_w;
		$rate_h = $imgH/$fixed_h;
		if($rate_w <= $rate_h) { //以宽为标准压缩
			$imgH = $rate_w*$fixed_h;
			$imgW = $rate_w*$fixed_w;
		}
		else{ //以高为标准压缩
			$imgH = $rate_h*$fixed_h;
			$imgW = $rate_h*$fixed_w;
		}
		switch($imgSize['mime']){
			case "image/gif" : $resourceImg = imagecreatefromgif($sourcePath);
			break;
			case "image/jpeg" : $resourceImg = imagecreatefromjpeg($sourcePath);
			break;
			case "image/png" : $resourceImg = imagecreatefrompng($sourcePath);
			break;
		}
		$tcimg = imagecreatetruecolor($fixed_w, $fixed_h);
		$back = imagecolorallocate($tcimg, 255, 255, 255);
		imagefilledrectangle($tcimg, 0, 0, $fixed_w, $fixed_h, $back);
		imagecopyresampled($tcimg, $resourceImg, 0, 0, 0, 0, $fixed_w, $fixed_h, $imgW, $imgH);
		
		//header("Content-Type:image/jpeg");    //所有压缩图片最终保存成jpg格式
		$result = imagejpeg($tcimg, $saveFile, $rate);
		imagedestroy($tcimg);
		return $result;
	}
	
	/**
	 * 生成合适大小的压缩图
	 * @param string $sourcePath 源图片的路径
	 * @param string $saveFile  保存图片路径
	 * @param int $maxWidth  保存最大宽
	 * @param int $maxHeight  保存最大高
	 * @param int $rate  压缩比率
	 */
	function resize_fit_img($sourcePath, $saveFile, $maxWidth, $maxHeight, $rate=90)
	{
		ini_set('memory_limit', '100M');
		set_time_limit(0);
		$imgSize = getimagesize($sourcePath);
		$imgW = $imgSize[0];
		$imgH = $imgSize[1];
		if ( !($imgW > $maxWidth || $imgH > $maxHeight) )
			return false;
		//图片最终宽高
		if ($imgW > $maxWidth) {
			$desWidth = $maxWidth;
			$desHeight = ($maxWidth/$imgW) * $imgH;
			if ( $desHeight > $maxHeight) {
				$desWidth = $maxHeight/$desHeight * $desWidth;
				$desHeight = $maxHeight;
			}
		} elseif ($imgH > $maxHeight) {
			$desHeight = $maxHeight;
			$desWidth = $maxHeight/$imgH * $imgW;
			if ( $desWidth > $maxWidth) {
				$desHeight = $maxWidth/$desWidth * $desHeight;
				$desWidth = $maxWidth;
			}
		} else {
			$desWidth = $maxWidth;
			$desHeight = $maxHeight;
		}
		switch($imgSize['mime']){
			case "image/gif" : $resourceImg = imagecreatefromgif($sourcePath);
			break;
			case "image/jpeg" : $resourceImg = imagecreatefromjpeg($sourcePath);
			break;
			case "image/png" : $resourceImg = imagecreatefrompng($sourcePath);
			break;
		}
		$tcimg = imagecreatetruecolor($desWidth, $desHeight);
		$back = imagecolorallocate($tcimg, 255, 255, 255);
		imagefilledrectangle($tcimg, 0, 0, $desWidth, $desHeight, $back);
		imagecopyresampled($tcimg, $resourceImg, 0, 0, 0, 0, $desWidth, $desHeight, $imgW, $imgH);
		/*switch($imgSize['mime']){
		 case "image/gif" : header("Content-Type:image/gif"); return @imagegif($tcimg, $saveFile);
		break;
		case "image/jpeg" : header("Content-Type:image/jpeg"); return @imagejpeg($tcimg, $saveFile, $rate);
		break;
		case "image/png" : header("Content-Type:image/png"); return @imagepng($tcimg, $saveFile, $rate);
		break;
		}*/
		//header("Content-Type:image/jpeg");    //所有压缩图片最终保存成jpg格式
		$result = imagejpeg($tcimg, $saveFile, $rate);
		imagedestroy($tcimg);
		return $result;
	}
	
	
	
	
	
	
    