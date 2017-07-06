<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends BaseController {
    public function xuanzuo(){
		$model=M('Student');
		$data=$model->select();

		//echo $model->getlastSql();
		//dump($data);exit;
		$this->display();
 
    }
    public function add(){
        $mmodel=D('Member');
        $mlmodel=M('MemberLevel');
        $this->levellist=$mlmodel->select();
        if(IS_POST){
            //dump($_POST);exit;
            if($data=$mmodel->create()){
                //dump(I('post.'));exit;
                if($data['logo']){
                    $path_dir=pathinfo($data['logo']);
                    $file_dir=$path_dir['dirname'];
                    //$file_name=$path_dir['basename'];
                    $file_ext=$path_dir['extension'];
                    //dump($path_dir);//die;
                    $file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
                    $newname=str_replace("image","truefile",$file_dir);
                    if(!file_exists(".".$newname)){
                        mkdir(".".$newname,0777,true);
                    }
                    //echo $newname;die;
                    $newfile=".".$newname."/".$file_name;
                    copy(".".$data['logo'],$newfile);
                    //echo $newname;die;
                    //$data['logo']=$newname."/".$file_name;
                    $data['logo']=$newname."/".$file_name;
                    if($data['width'] && $data['height']){
                        $image = new \Think\Image();
                        $image->open('.'.$data['logo']);
                        $savename = 'thumb_'.$file_name;
                        $image->thumb($data['width'],$data['height'],6)->save('.'.$newname."/".$savename);
                        $data['thumb_logo']=$newname."/".$savename;
                    }/*else {
                        $image = new \Think\Image();
                        $image->open('.' . $data['logo']);
                        $savename = 'thumb_' . $file_name;
                        // echo $newname;
                        //echo $savename;die;
                        $image->thumb(200, 120, 6)->save('.' . $newname . "/" . $savename);
                        $data['thumb_logo'] = $newname . "/" . $savename;
                        $data['logo'] = $newname . "/" . $file_name;
                    }*/
                    //echo $newfile;die;
                }
                $result=$mmodel->add($data);
                if($result){
                    $this->success('添加成功',U('Member/add'),1);
                    exit;
                }else{
                    $this->error($mmodel->getError());
                }
            }else{
                $this->error($mmodel->getError());
            }
        }
        $this->display();
    }
    public function a(){
        $this->display();
    }
    public function b(){
        $this->display();
    }
    public function lis(){
        //这是会员的等级
        $mlmodel=M('MemberLevel');
        $this->dj=$mlmodel->select();
        $mmodel=D('Member');
       /*dlist=$mmodel->alias('a')->field('a.*,b.level,b.zk')
           ->join('left join beidou_member_level b on a.level_id=b.id')
           ->select();
       $this->dlist=$dlist;*/
        $list=$mmodel->search(5);
        foreach($list['data'] as $k=>$v){
            $v['zk']=(1-$v['level']*0.07)*10;
            $list['data'][$k]=$v;
        }
        $this->dlist=$list['data'];
        $this->page=$list['page'];
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $model=D('Member');
        $vo=$model->where(array('id'=>$id))->find();
        $this->vo=$vo;
        if(IS_POST){
            //dump(I('post.'));exit;
            if($data=$model->create()){
                //dump($data);die;
                //dump(I('post.'));exit;
                if($data['logo']){
                    if($data['logo']!=$vo['logo']){
                        $path_dir=pathinfo($data['logo']);
                        //dump($path_dir);die;
                        $file_dir=$path_dir['dirname'];
                        //$file_name=$path_dir['basename'];
                        $file_ext=$path_dir['extension'];
                        //dump($path_dir);//die;
                        $file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
                        $newname=str_replace("image","truefile",$file_dir);
                        //echo $newname;die;
                        if(!file_exists(".".$newname)){
                            mkdir(".".$newname,0777,true);
                        }
                        $newfile=".".$newname."/".$file_name;
                        copy(".".$data['logo'],$newfile);
                        unlink(".".$vo['logo']);
                        $data['logo']=$newname."/".$file_name;
                        if($data['width'] && $data['height']){
                            $image = new \Think\Image();
                            $image->open('.'.$data['logo']);
                            $savename = 'thumb_'.$file_name;
                            $image->thumb($data['width'],$data['height'],6)->save('.'.$newname."/".$savename);
                            $data['thumb_logo']=$newname."/".$savename;
                            unlink(".".$vo['thumb_logo']);
                        }else{
                            unlink(".".$vo['thumb_logo']);
                        }
                    }else{
                        if($data['width'] && $data['height']){
                            $path_dir=pathinfo($data['logo']);
                            //dump($path_dir);die;
                            $file_dir=$path_dir['dirname'];
                            $file_name=$path_dir['basename'];
                            //$file_ext=$path_dir['extension'];
                            $image = new \Think\Image();
                            $image->open(".".$vo['logo']);
                            $savename = $file_dir."/".'thumb_'.$file_name;
                            $image->thumb($data['width'],$data['height'],6)->save('.'.$savename);
                            $data['thumb_logo']=$savename;
                            //$data['logo']=$newname."/".$file_name;
                        }else{
                            unlink(".".$vo['thumb_logo']);
                        }
                    }
                }
                $result=$model->where(array('id'=>$id))->save($data);
                if($result!==false){
                    $this->success('修改成功',U('Member/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                $this->error($model->getError());
            }
        }
        $this->display();
    }
	public function djcz(){
		$id=I('get.id');
		$model=M('Member');
		$model->where(array('id'=>$id))->save(array('total_price'=>0));
		$this->success('重置成功',U('Member/cleardj',array('id'=>$id)),1);
	}
    public function ajaxcz(){

    }
    public function ajax_remove(){
        $url=I('post.lj');
        $id=I('post.id');
        $model=M("Member");
        if($id){
            $list=$model->find($id);
            if($url==$list['logo']){
                $model->where(array('id'=>$id))->setField('logo','');
                $model->where(array('id'=>$id))->setField('thumb_logo','');
            }
        }
        $lj=pathinfo($url);
        $thumb_url=$lj['dirname']."/thumb_".$lj['basename'];
        unlink(".".$thumb_url);
        unlink(".".$url);die;
    }
    public function uploadfile()
    {

        require_once './Public/kindeditor/php/JSON.php';
        //echo $_GET['width'];die;

        $php_path = dirname(__FILE__) . '/';
        $php_path=$_SERVER['DOCUMENT_ROOT'];
        //$php_path=str_replace("\\","/",str_replace("Application\Admin\Home","",dirname(__FILE__)));
        //$php_url = dirname($_SERVER['PHP_SELF']) . '/';

        //文件保存目录路径

        $save_path = $php_path . '/Public/Uploads/kindeditor/';
        //echo $php_path;
        //echo $php_url;
        //文件保存目录URL
        $save_url =  '/Public/Uploads/kindeditor/';
        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //最大文件大小
        $max_size = 1000000;

        $save_path = realpath($save_path) . '/';

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch ($_FILES['imgFile']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            alert($error);
        }

        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                alert("请选择文件。");
            }
            //检查目录
            if (@is_dir($save_path) === false) {
                alert("上传目录不存在。");
            }
            //检查目录写权限
            if (@is_writable($save_path) === false) {
                alert("上传目录没有写权限。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $max_size) {
                alert("上传文件大小超过限制。");
            }
            //检查目录名
            $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
            if (empty($ext_arr[$dir_name])) {
                alert("目录名不正确。");
            }
            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }
            //创建文件夹
            if ($dir_name !== '') {
                $save_path .= $dir_name . "/";
                $save_url .= $dir_name . "/";
                if (!file_exists($save_path)) {
                    mkdir($save_path);
                }
            }
            $ymd = date("Ymd");
            $save_path .= $ymd . "/";
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            //移动文件
            $file_path = $save_path . $new_file_name;
            if (move_uploaded_file($tmp_name, $file_path) === false) {
                alert("上传文件失败。");
            }
            @chmod($file_path, 0644);
            $file_url = $save_url . $new_file_name;
            //echo $file_url;die;

            header('Content-type: text/html; charset=UTF-8');
            $json = new \Services_JSON();
            echo $json->encode(array('error' => 0, 'url' => $file_url));
            exit;
        }
    }
    public function multiuploadfile()
    {

        require_once './Public/kindeditor/php/JSON.php';
        //echo $_GET['width'];die;

        $php_path = dirname(__FILE__) . '/';
        $php_path=$_SERVER['DOCUMENT_ROOT'];
        //$php_path=str_replace("\\","/",str_replace("Application\Admin\Home","",dirname(__FILE__)));
        //$php_url = dirname($_SERVER['PHP_SELF']) . '/';

        //文件保存目录路径

        $save_path = $php_path . '/Public/Uploads/kindeditor/';
        //echo $php_path;
        //echo $php_url;
        //文件保存目录URL
        $save_url =  '/Public/Uploads/kindeditor/';
        $_GET['dir']='multiupload';
        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'multiupload' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //最大文件大小
        $max_size = 1000000;

        $save_path = realpath($save_path) . '/';

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch ($_FILES['imgFile']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            alert($error);
        }

        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                alert("请选择文件。");
            }
            //检查目录
            if (@is_dir($save_path) === false) {
                alert("上传目录不存在。");
            }
            //检查目录写权限
            if (@is_writable($save_path) === false) {
                alert("上传目录没有写权限。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $max_size) {
                alert("上传文件大小超过限制。");
            }
            //echo $_GET['dir'];die;
            //检查目录名
            $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
            if (empty($ext_arr[$dir_name])) {
                alert("目录名不正确。");
            }
            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }
            //创建文件夹
            if ($dir_name !== '') {
                $save_path .= $dir_name . "/";
                $save_url .= $dir_name . "/";
                if (!file_exists($save_path)) {
                    mkdir($save_path);
                }
            }
            $ymd = date("Ymd");
            $save_path .= $ymd . "/";
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            //移动文件
            $file_path = $save_path . $new_file_name;
            if (move_uploaded_file($tmp_name, $file_path) === false) {
                alert("上传文件失败。");
            }
            @chmod($file_path, 0644);
            $file_url = $save_url . $new_file_name;
            //echo $file_url;die;

            header('Content-type: text/html; charset=UTF-8');
            $json = new \Services_JSON();
            echo $json->encode(array('error' => 0, 'url' => $file_url));
            exit;
        }
    }
}
?>