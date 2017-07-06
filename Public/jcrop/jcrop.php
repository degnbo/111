<?php
//require_once(dirname(__FILE__).'/../../inc/config.inc.php');

/*
**************************
(C)2010-2015 www.sunyixin.com
update: 2013-4-24 14:48:27
person: Xiao Xin
**************************
*/

if(isset($_GET)){
	extract($_GET);
}
//引入水印文件
//require_once(PHPMYWIND_DATA.'/watermark/watermark.class.php');

$url=$_SERVER['DOCUMENT_ROOT'];
//初始化参数
$serverurl=$_SERVER['SERVER_PROTOCOL'];
$imgurl    = isset($_GET['imgurl']) ? $imgurl : '';
$srcfile   = "http://zyqs.qmchina.net".$imgurl;

$newwidth  = isset($iw) ? $iw : '';
$newheight = isset($ih) ? $ih : '';
$x1        = isset($x1) ? $x1 : '';
$y1        = isset($y1) ? $y1 : '';
$x2        = isset($x2) ? $x2 : '';
$y2        = isset($y2) ? $y2 : '';
$wm        = isset($wm) ? $wm : '';

$fileinfo=pathinfo($srcfile);
if(!file_exists($url."/Public/upload/")){
	mkdir($url."/Public/upload/",0777,true);
}
$thumbfile=$url."/"."Public/upload/".$fileinfo['basename'];
$thumburl="/Public/upload/".$fileinfo['basename'];
//var_dump($fileinfo);die;
//获取图片信息
$srcinfo = @getimagesize($srcfile);
//var_dump($srcinfo);die;
$width   = $srcinfo[0];
$height  = $srcinfo[1];


//检测图片扩展名
if($srcinfo[2] != 1 &&
   $srcinfo[2] != 2 &&
   $srcinfo[2] != 3 &&
   $srcinfo[2] != 6)
{
	echo '不符合的图片格式！';
	exit();
}


//创建真彩图像
$newimg = @imagecreatetruecolor($newwidth, $newheight);


//获取图像文件
switch($srcinfo[2])
{
	case 1:
		$imgfrom = imagecreatefromgif($srcfile);
		break;
	case 2:
		$imgfrom = imagecreatefromjpeg($srcfile);
		break;
	case 3:
		$imgfrom = imagecreatefrompng($srcfile);
		break;
	case 6:
		$imgfrom = imagecreatefromwbmp($srcfile);
		break;
	default:
		echo '对不起，裁剪图片类型不支持请选择其他类型图片！';
		exit();
}

//var_dump($imgfrom);die;
//创建图像文件
@imagecopyresampled($newimg, $imgfrom, 0, 0, $x1, $y1, $width, $height, $width, $height);

//var_dump($srcinfo[2]);die;
//生成图像文件
//var_dump($newimg);die;

switch($srcinfo[2])
{
	case 1:
		imagegif($newimg, $thumbfile);
		break;
	case 2:
		imagejpeg($newimg,$thumbfile,100);
		break;
	case 3:
		imagepng($newimg, $thumbfile);
		break;
	case 6:
		imagewbmp($newimg, $thumbfile);
		break;
	default:
		echo '对不起，裁剪图片类型不支持请选择其他类型图片！';
		exit();
}
@imagedestroy($newimg);

//水印设置
/*if($cfg_markswitch=='Y' and $wm=='true')
{
	WaterMark($srcfile, PHPMYWIND_ROOT.'/'.$cfg_markpicurl, $cfg_markwhere, $cfg_marktext, '黑体', $cfg_marksize, $cfg_markcolor, $cfg_marktype);
}*/


//返回状态
echo json_encode(array('message'=>$thumburl,'status'=>true));
?>
