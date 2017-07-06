<?php
class Image{
	private $types;	//使用的功能编号，1为图片缩放功能  2为图片裁剪功能   3,为图片加图片水印功能
	private $imgtype;//图片的格式
	private $image;	//图片资源
	private $width;//图片宽度
	private $height;//图片高度
	private $value1;//根据所传type值的不同，$value1分别代表不同的值
	private $value2;//根据所传type值的不同，$value2分别代表不同的值
	private $endaddress;//输出后的地址+文件名


	function __construct($imageaddress, $types, $value1="", $value2="", $endaddress){
		$this->types=$types;
		$this->image=$this->imagesources($imageaddress);
		$this->width=$this->imagesizex();
		$this->height=$this->imagesizey();
		$this->value1=$value1;
		$this->value2=$value2;
		$this->endaddress=$endaddress;
	}

	function outimage(){	//根据传入type值的不同，输出不同的功能
		switch($this->types){
			case 1:
				$this->scaling();
				break;
			case 2:
				$this->clipping();
				break;
			case 3:
				$this->imagewater();
				break;
			default:
				return false;

		}
	}

	private function imagewater(){	//加图片水印功能
		//用函数获取水印文件的长和宽
		$imagearrs=$this->getimagearr($this->value1);
		//调用函数计算出水印加载的位置
		$positionarr=$this->position($this->value2, $imagearrs[0], $imagearrs[1]);
		//加水印
		imagecopy($this->image, $this->imagesources($this->value1), $positionarr[0], $positionarr[1], 0, 0, $imagearrs[0], $imagearrs[1]);
		//调用输出方法保存
		$this->output($this->image);
	}

 function clipping(){	//图片裁剪功能
		//将传进来的值分别赋给变量
		list($src_x, $src_y)=explode(",", $this->value1);
		list($dst_w, $dst_h)=explode(",", $this->value2);
		if($this->width < $src_x+$dst_w || $this->height < $src_y+$dst_h){	//这个判断就是限制不能截取到图片外面去
			return false;
		}
		//创建新的画布资源
		$newimg=imagecreatetruecolor($dst_w, $dst_h);
		//进行裁剪
		imagecopyresampled($newimg, $this->image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $dst_w, $dst_h);
		//调用输出方法保存
		$this->output($newimg);
	}

	private function scaling(){	//图片缩放功能
		//获取等比缩放的宽和高
		$this-> proimagesize();
		//根据参数进行缩放,并调用输出函数保存处理后的文件
		$this->output($this->imagescaling());
	}

	private function imagesources($imgad){	//获取图片类型并打开图像资源
		$imagearray=$this->getimagearr($imgad);
		switch($imagearray[2]){
			case 1://gif
				$this->imgtype=1;
				$img=imagecreatefromgif($imgad);
				break;
			case 2://jpeg
				$this->imgtype=2;
				$img=imagecreatefromjpeg($imgad);
				break;
			case 3://png
				$this->imgtype=3;
				$img=imagecreatefrompng($imgad);
				break;
			default:
				return false;
		}
		return $img;
	}

	private function imagesizex(){	//获得图片宽度
		return imagesx($this->image);
	}

	private function imagesizey(){	//获取图片高度
		return imagesy($this->image);
	}

	private function proimagesize(){	//计算等比缩放的图片的宽和高
		if($this->value1 && ($this->width < $this->height)) {	//等比缩放算法
			$this->value1=round(($this->value2/ $this->height)*$this->width);
		}else{
			$this->value2=round(($this->value1/ $this->width) * $this->height);
		}
	}

	private function imagescaling(){//图像缩放功能，返回处理后的图像资源
		$newimg=imagecreatetruecolor($this->value1, $this->value2);

		$tran=imagecolortransparent($this->image);//处理透明算法
		if($tran >= 0 && $tran < imagecolorstotal($this->image)){
			$tranarr=imagecolorsforindex($this->image, $tran);
			$newcolor=imagecolorallocate($newimg, $tranarr['red'], $tranarr['green'], $tranarr['blue']);
			imagefill($newimg, 0, 0, $newcolor);
			imagecolortransparent($newimg, $newcolor);
		}
		 
		imagecopyresampled($newimg, $this->image, 0, 0, 0, 0, $this->value1, $this->value2, $this->width, $this->height);
		return $newimg;
	}

	private function output($image){//输出图像
		switch($this->imgtype){
			case 1:
				imagegif($image, $this->endaddress);
				break;
			case 2:
				imagejpeg($image, $this->endaddress);
				break;
			case 3:
				imagepng($image, $this->endaddress);
				break;
			default:
				return false;
		}
	}

	private function getimagearr($imagesou){//返回图像属性数组方法
		return getimagesize($imagesou);
	}

	private function position($num, $width, $height){//根据传入的数字返回一个位置的坐标,$width和$height分别代表插入图像的宽和高
		switch($num){
			case 1:
				$positionarr[0]=0;
				$positionarr[1]=0;
				break;
			case 2:
				$positionarr[0]=($this->width-$width)/2;
				$positionarr[1]=0;
				break;
			case 3:
				$positionarr[0]=$this->width-$width;
				$positionarr[1]=0;
				break;
			case 4:
				$positionarr[0]=0;
				$positionarr[1]=($this->height-$height)/2;
				break;
			case 5:
				$positionarr[0]=($this->width-$width)/2;
				$positionarr[1]=($this->height-$height)/2;
				break;
			case 6:
				$positionarr[0]=$this->width-$width;
				$positionarr[1]=($this->height-$height)/2;
				break;
			case 7:
				$positionarr[0]=0;
				$positionarr[1]=$this->height-$height;
				break;
			case 8:
				$positionarr[0]=($this->width-$width)/2;
				$positionarr[1]=$this->height-$height;
				break;
			case 9:
				$positionarr[0]=$this->width-$width;
				$positionarr[1]=$this->height-$height;
				break;
			case 0:
				$positionarr[0]=rand(0, $this->width-$width);
				$positionarr[1]=rand(0, $this->height-$height);
				break;
		}
		return $positionarr;
	}
	
	function getImageName(){
		return $this->endaddress;
	}

	function __destruct(){
		imagedestroy($this->image);
	}


}