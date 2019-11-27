<?php
function wpWaterMarkPosition($point, $imgWidth, $imgHeight, $textWidth, $textLength, $lineHeight, $margin) {
	$pointLeft = $margin;
	$pointTop = $margin;
	switch ($point) {
		case 1:
			$pointLeft = $margin;
			$pointTop = $margin;
			break;
		case 2:
			$pointLeft = floor(($imgWidth - $textWidth) / 2);
			$pointTop = $margin;
			break;
		case 3:
			$pointLeft = $imgWidth - $textWidth - $margin;
			$pointTop = $margin;
			break;
		case 4:
			$pointLeft = $margin;
			$pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			break;
		case 5:
			$pointLeft = floor(($imgWidth - $textWidth) / 2);
			$pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			break;
		case 6:
			$pointLeft = $imgWidth - $textWidth - $margin;
			$pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			break;
		case 7:
			$pointLeft = $margin;
			$pointTop = $imgHeight - $textLength * $lineHeight - $margin;
			break;
		case 8:
			$pointLeft = floor(($imgWidth - $textWidth) / 2);
			$pointTop = $imgHeight - $textLength * $lineHeight - $margin;
			break;
		case 9:
			$pointLeft = $imgWidth - $textWidth - $margin;
			$pointTop = $imgHeight - $textLength * $lineHeight - $margin;
			break;
	}
	return array('pointLeft' => $pointLeft, 'pointTop' => $pointTop);
}
function wpWaterMarkSetAngle ( $angle, $point, $position, $textWidth, $imgHeight ) {
	if ($angle < 90) {
		$diffTop = ceil(sin(deg2rad($angle)) * $textWidth);

		if (in_array($point, array(1, 2, 3))) {
			$position['pointTop'] += $diffTop;
		} elseif (in_array($point, array(4, 5, 6))) {
			if ($textWidth > ceil($imgHeight / 2)) {
				$position['pointTop'] += ceil(($textWidth - $imgHeight / 2) / 2);
			}
		}
	} elseif ($angle > 270) {
		$diffTop = ceil(sin(deg2rad(360 - $angle)) * $textWidth);

		if (in_array($point, array(7, 8, 9))) {
			$position['pointTop'] -= $diffTop;
		} elseif (in_array($point, array(4, 5, 6))) {
			if ($textWidth > ceil($imgHeight / 2)) {
				$position['pointTop'] = ceil(($imgHeight - $diffTop) / 2);
			}
		}
	}
	return $position;
}
function wpwatermark_hex2rgb($hexColor) {
	$color = str_replace('#', '', $hexColor);
	if (strlen($color) > 3) {
		$rgb = array(
			'r' => hexdec(substr($color, 0, 2)),
			'g' => hexdec(substr($color, 2, 2)),
			'b' => hexdec(substr($color, 4, 2))
		);
	} else {
		$color = $hexColor;
		$r = substr($color, 0, 1) . substr($color, 0, 1);
		$g = substr($color, 1, 1) . substr($color, 1, 1);
		$b = substr($color, 2, 1) . substr($color, 2, 1);
		$rgb = array(
			'r' => hexdec($r),
			'g' => hexdec($g),
			'b' => hexdec($b)
		);
	}
	return $rgb['r'].','.$rgb['g'].','.$rgb['b'];
}
function wpWaterMarkCreateWordsWatermark($imgurl, $newimgurl, $text, $margin='30', $fontSize = '14', $color = '#790000', $point = '1', $font = 'simhei.ttf', $angle = '0', $watermark_margin = '80' )
{
	$margin = intval($margin);
	$angle = intval($angle);
	$watermark_margin = intval($watermark_margin);
	$imageCreateFunArr = array(
		'image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif'
	);
	$imageOutputFunArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');
	$imgsize = getimagesize($imgurl);
	if (empty($imgsize)) { return false; }
	$imgWidth = $imgsize[0];
	$imgHeight = $imgsize[1];
	$imgMime = $imgsize['mime'];
	if (!isset($imageCreateFunArr[$imgMime])) { return false; }
	if (!isset($imageOutputFunArr[$imgMime])) { return false; }
	$imageCreateFun = $imageCreateFunArr[$imgMime];
	$imageOutputFun = $imageOutputFunArr[$imgMime];
	$im = $imageCreateFun($imgurl);
	$color = explode(',', wpwatermark_hex2rgb($color));
	$text_color = imagecolorallocate($im, intval($color[0]), intval($color[1]), intval($color[2]));
	$point = intval($point) >= 0 && intval($point) <= 10 ? intval($point) : 1;
	$fontSize = intval($fontSize) > 0 ? intval($fontSize) : 14;
	$angle = ($angle >= 0 && $angle < 90 || $angle > 270 && $angle < 360) ? $angle : 0;
	$fontUrl = plugin_dir_path( __FILE__ ) . 'fonts/' . ($font ? $font : 'alibaba.otf');
	$text = explode('|', $text);
	$textLength = count($text) - 1;
	$maxtext = 0;
	foreach ($text as $val) {
		$maxtext = strlen($val) > strlen($maxtext) ? $val : $maxtext;
	}
	$textSize = imagettfbbox($fontSize, 0, $fontUrl, $maxtext);
	$textWidth = $textSize[2] - $textSize[1];
	$textHeight = $textSize[1] - $textSize[7];
	$lineHeight = $textHeight + 3;
	if ($textWidth + 40 > $imgWidth || $lineHeight * $textLength + 40 > $imgHeight) { return false; }
	if ($point == 10) {
		$position = array('pointLeft' => $margin, 'pointTop' => $margin);
		if ($angle != 0) {
			$position = wpWaterMarkSetAngle($angle, $point, $position, $textWidth, $imgHeight);
		}
		$x_length = $imgWidth - $margin;
		$y_length = $imgHeight - $margin;
		for  ($x = $position['pointLeft']; $x < $x_length; $x++ ) {
			for ($y = $position['pointTop']; $y < $y_length; $y++) {
				foreach ($text as $key => $val) {
					imagettftext($im, $fontSize, $angle, $x, $y + $key * $lineHeight, $text_color, $fontUrl, $val);
				}
				$y += ($lineHeight * count($text) + $watermark_margin);
			}
			$x += ($textWidth + $watermark_margin);
		}
	} else {
		if ( $point == 0 ) {
			$point = mt_rand(1, 9);
		}
		$position = wpWaterMarkPosition($point, $imgWidth, $imgHeight, $textWidth, $textLength, $lineHeight, $margin);
		if ($angle != 0) {
			$position = wpWaterMarkSetAngle($angle, $point, $position, $textWidth, $imgHeight);
		}
		foreach ($text as $key => $val) {
			imagettftext($im, $fontSize, $angle, $position['pointLeft'], $position['pointTop'] + $key * $lineHeight, $text_color, $fontUrl, $val);
		}
	}
	if ( $imgMime == 'image/jpeg' ) {
		$imageOutputFun($im, $newimgurl, 100);
	} else {
		$imageOutputFun($im, $newimgurl);
	}
	imagedestroy($im);
	return $newimgurl;
}
function wpWaterMarkImageWatermarkPosition( $point, $imgWidth, $imgHeight, $stampWidth, $stampHeight, $margin )
{
	$pointLeft = $margin;
	$pointTop = $margin;
	if ( $point == 0 ) {
		$point = mt_rand(1, 9);
	}
	switch ( $point ) {
		case 1:
			$pointLeft = $margin;
			$pointTop = $margin;
			break;
		case 2:
			$pointLeft = floor(($imgWidth - $stampWidth) / 2);
			$pointTop = $margin;
			break;
		case 3:
			$pointLeft = $imgWidth - $stampWidth - $margin;
			$pointTop = $margin;
			break;
		case 4:
			$pointLeft = $margin;
			$pointTop = floor(($imgHeight - $stampHeight) / 2);
			break;
		case 5:
			$pointLeft = floor(($imgWidth - $stampWidth) / 2);
			$pointTop = floor(($imgHeight - $stampHeight) / 2);
			break;
		case 6:
			$pointLeft = $imgWidth - $stampWidth - $margin;
			$pointTop = floor(($imgHeight - $stampHeight) / 2);
			break;
		case 7:
			$pointLeft = $margin;
			$pointTop = $imgHeight - $stampHeight - $margin;
			break;
		case 8:
			$pointLeft = floor(($imgWidth - $stampWidth) / 2);
			$pointTop = $imgHeight - $stampHeight - $margin;
			break;
		case 9:
			$pointLeft = $imgWidth - $stampWidth - $margin;
			$pointTop = $imgHeight - $stampHeight - $margin;
			break;
	}
	return array('pointLeft' => $pointLeft, 'pointTop' => $pointTop);
}
function wpWaterMarkCreateImageWatermark( $img_url, $stamp_url, $newimgurl, $point, $pct='100', $margin='30', $watermark_margin = '80' )
{
	$pct = intval($pct);
	$margin = intval($margin);
	$watermark_margin = intval($watermark_margin);
	$imageCreateFunArr = array(
		'image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif'
	);
	$imageOutputFunArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');
	$im_size = getimagesize($img_url);
	$stamp_size = getimagesize($stamp_url);
	if ( empty($im_size) or empty($stamp_size) ) {return false;}
	$imWidth = $im_size[0];
	$imHeight = $im_size[1];
	$imMime = $im_size['mime'];
	$stampWidth = $stamp_size[0];
	$stampHeight = $stamp_size[1];
	$stampMime = $stamp_size['mime'];
	if ( $imWidth < $stampWidth or $imHeight < $stampHeight ) {return false;}
	if ( !isset($imageCreateFunArr[$imMime]) or !isset($imageCreateFunArr[$stampMime]) ) {return false;}
	if ( !isset($imageOutputFunArr[$imMime]) ) {return false;}
	$imCreateFun = $imageCreateFunArr[$imMime];
	$imOutputFun = $imageOutputFunArr[$imMime];
	$stampCreateFun = $imageCreateFunArr[$stampMime];
	$im = $imCreateFun($img_url);
	$stamp = $stampCreateFun($stamp_url);
	$tcStamp = imagecreatetruecolor($stampWidth, $stampHeight);
	$point = intval($point) >= 0 && intval($point) <= 10 ? intval($point) : 1;
	if ( $point == 10  ) {
		$x_length = $imWidth - $margin;
		$y_length = $imHeight - $margin;
		for  ($x = $margin; $x < $x_length; $x++ ) {
			for ($y = $margin; $y < $y_length; $y++) {
				imagecopy($tcStamp, $im, 0, 0, $x, $y, $stampWidth, $stampHeight);
				imagecopy($tcStamp, $stamp, 0, 0, 0, 0, $stampWidth, $stampHeight);
				imagecopymerge($im, $tcStamp, $x, $y, 0, 0, $stampWidth, $stampHeight, $pct);
				$y += ($stampHeight + $watermark_margin);
			}
			$x += ($stampWidth + $watermark_margin);
		}
	} else {
		$position = wpWaterMarkImageWatermarkPosition($point, $imWidth, $imHeight, $stampWidth, $stampHeight, $margin);
		imagecopy($tcStamp, $im, 0, 0, $position['pointLeft'], $position['pointTop'], $stampWidth, $stampHeight);
		imagecopy($tcStamp, $stamp, 0, 0, 0, 0, $stampWidth, $stampHeight);
		imagecopymerge($im, $tcStamp, $position['pointLeft'], $position['pointTop'], 0, 0, $stampWidth, $stampHeight, $pct);
	}
	if ( $imMime == 'image/jpeg' ) { $imOutputFun($im, $newimgurl, 100); } else { $imOutputFun($im, $newimgurl); }
	imagedestroy($im);
	imagedestroy($stamp);
	imagedestroy($tcStamp);
	return $newimgurl;
}
