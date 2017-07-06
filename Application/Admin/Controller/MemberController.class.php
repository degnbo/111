<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends BaseController {
    public function wo(){
        $url='http://er.51cptj.com/Public/Uploads/1.jpg';
        //dump(phpinfo());die;
        $info = $this->downloadImageFromWeiXin($url);
        $time = time();
        $pathname = "./Public/aa/" . date('Ymd');
        //$pathname1 = "/Public/aa/" . date('Ymd');
        if (!file_exists($pathname)) {
            mkdir($pathname, 0777, true);
        }
        $filename = $time . substr(uniqid(), 0, 5) . ".png";
        $local_file = $pathname . "/" . $filename;
        $file_dir = fopen($local_file, "w");
        if (false !== $file_dir) {
            if (false !== fwrite($file_dir, $info['body'])) {
                fclose($file_dir);
            }
        }
        //dump($list);die;

    }
    public function delete(){
        $id=I('get.id');
        $model=M('Member');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        //$result=$model->delete($id);
        $list=$model->find($id);
        //dump($list);die;
        $result=$model->where(array('id'=>$id))->save(array('is_show'=>0));
        if(false!==$result){
            if($list['type']==2){
                $this->success('删除成功',U('Member/lis1'),1);
                exit;
            }else{
                $this->success('删除成功',U('Member/lis'),1);
                exit;
            }
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }
    public function downloadImageFromWeiXin($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        //print_r($package);die;
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        return array_merge(array('body' => $package), array('header' => $httpinfo));
    }
    public function ajax_del(){
        $tp=I('post.tp');
        $id=I('post.id');
        $model=M('Member');
        $url=$model->where(array('id'=>$id))->find();
        $urls=explode('|',$url['pic_list']);
        foreach($urls as $k=>$v){
            if($tp==$v){
                unset($urls[$k]);
                unlink(".".$tp);
            }
        }
        $lj=implode('|',$urls);
        $url=$model->where(array('id'=>$id))->setField('pic_list',$lj);
        echo 1;die;
    }
    public function upload2($size=2,$lanmu='shangchuan/'){
        $config=array(
            'maxSize'    =>    $size*1024*1024,
            'savePath'   =>$lanmu,
            'rootPath'=>'./Public/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile1']['error']==0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        }else{
            return false;
        }
    }
    public function upload1($size=2,$lanmu='shangchuan/'){
        $config=array(
            'maxSize'    =>    $size*1024*1024,
            'savePath'   =>$lanmu,
            'rootPath'=>'./Public/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile2']['error']==0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        }else{
            return false;
        }
    }
    public function ajax_edit(){
        //dump($_FILES);
        $tp=I('post.tp');
        $id=I('post.id');
        //C("UPLOADS_SIZE",2);
        if($_FILES['myfile2']['size']>2*1024*1024){
            $this->error('上传图片不能大于2M');
        }
        $model=M('Member');
        $url=$model->where(array('id'=>$id))->find();
        //echo $tp;die;
        $info=$this->upload1(2,"shangchuan/");
        $path='/Public/'.$info['myfile2']['savepath'].$info['myfile2']['savename'];
        $image = new \Think\Image();
        $image->open('.'.$path);
        $image->thumb(200, 200, 3)->save('.'.$path);
        $urls=explode('|',$url['pic_list']);
        foreach($urls as $k=>$v){
            if($tp==$v){
                $v=$path;
                unlink(".".$tp);
            }
            $urls[$k]=$v;
        }
        $lj=implode('|',$urls);
        $result=$model->where(array('id'=>$id))->setField('pic_list',$lj);
        if($result!==false){
            $this->success('图片修改成功',U('Member/edit',array('id'=>$id),1));
            exit;
        }else{
            $this->error('修改失败！');
        }
    }
    public function add_pic(){
        $id=I('get.id');
        //echo $id;die;
        $p=I('get.p');
        $cid=I('get.cid');
        $model=M('Member');
        $url=$model->where(array('id'=>$id))->find();
        $info=$this->upload2(2,"shangchuan/");
        $path='/Public/'.$info['myfile1']['savepath'].$info['myfile1']['savename'];
        $image = new \Think\Image();
        $image->open('.'.$path);
        $image->thumb(200, 200, 3)->save('.'.$path);
        //dump($info);die;
        if($url['pic_list']){
            $urls=explode('|',$url['pic_list']);
            $urls[]=$path;
            $lj=implode('|',$urls);
        }else{
            $urls=$path;
            $lj=$urls;
        }
        $result=$model->where(array('id'=>$id))->save(array('pic_list'=>$lj));
        if($result!==false){
            $this->success('上传成功',U('Member/edit',array('id'=>$id,'p'=>$p,'cid'=>$cid)),1);
            exit;
        }else{
            $this->error('上传失败！');
        }
    }
    public function search($pageSize=20,$type=2){
        /************************************* 翻页 ****************************************/
        $mmodel=M('Member');
        $where['is_show']=array('eq',1);
        $goodsName = I('get.keyword');
        if($goodsName!=''){
            $data['email'] = array('like', "%$goodsName%");
            $data['name'] = array('like', "%$goodsName%");
            $data['phone'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        if($type==2){
            $where['type']=array('eq', '2');
        }else{
            $where['type']=array('eq', '1');
        }
        $orderby = 'id';  // 默认排序的字段
        $orderway = 'desc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc')
            $orderway = 'asc';
        $count = $mmodel->where($where)->count();
        //echo $mmodel->getLastSql();die;
        //echo $count;exit;
        if(!$count){
            $data['page']='无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        }else{
            $page = new \Think\Page($count, $pageSize);
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            //join('left join beidou_member_level b on a.level_id=b.id')->
            /************************************** 取数据 ******************************************/
            $data['data'] = $mmodel->alias('a')->field('a.*')->

            where($where)->order("$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }
    public function checkPhone(){
        $phone=$_POST['param'];
        $model=M('Member');
        $data=$model->where('phone='.$phone)->find();
        //$data='18233305970';
        if($data){
            echo '你输入的手机号已经被绑定!';
        }else{
            echo 'y';
        }
    }
    public function add(){
        $mmodel=M('Member');
        if(IS_POST){
            $data=I('post.');
            $data['addtime']=time();
            $data['openid']=time().uniqid();
            //dump($data);exit;
            if($data['logo']){
                $newname=basename($data['logo']);
                $foreverlj=basename("../".dirname($data['logo']));
                //var_dump($foreverlj);
                $newfile="/Public/tempfile/forver/".$foreverlj;
                if(!file_exists(".".$newfile)){
                    mkdir(".".$newfile,0777,true);
                }
                copy(".".$data['logo'],".".$newfile."/".$newname);
                unlink(".".$data['logo']);
                $data['logo']=$newfile."/".$newname;
            }
            //deldir("./Public/tempfile/temp");
            //deldir("./Public/upload/");
            $result=$mmodel->add($data);
            if($result){
                $this->success('添加成功',U('Member/edit', array('id' => $result)),1);
                exit;
            }else{
                $this->error($mmodel->getError());
            }
        }
        $this->display();
    }
    public function lis(){
        $mmodel=M('Member');
       /*dlist=$mmodel->alias('a')->field('a.*,b.level,b.zk')
           ->join('left join beidou_member_level b on a.level_id=b.id')
           ->select();
       $this->dlist=$dlist;*/
        $list=$this->search(8,1);
        $this->dlist=$list['data'];
        $this->page=$list['page'];
        $this->display();
    }
    public function lis1(){
        $mmodel=M('Member');
        /*dlist=$mmodel->alias('a')->field('a.*,b.level,b.zk')
            ->join('left join beidou_member_level b on a.level_id=b.id')
            ->select();
        $this->dlist=$dlist;*/
        $list=$this->search(8,2);
        //dump($list);die;
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
        $p=I('get.p');
        $model=M('Member');
        $vo=$model->where(array('id'=>$id))->find();
        $url=$vo['pic_list'];
        if($url){
            $urls=explode('|',$url);
        }else{
            $urls=$url;
        }
        $this->urls=$urls;
        $this->vo=$vo;
        if(IS_POST) {
            $data=I('post.');
            $data['openid']=time().uniqid();
            //dump($data);exit;
            //dump($data);die;
            if ($data['logo']) {
                if ($vo['logo'] != $data['logo']) {
                    $newname = basename($data['logo']);
                    $foreverlj = basename("../" . dirname($data['logo']));
                    //echo $data['logo'];
                    //var_dump($foreverlj);die;
                    $newfile = "/Public/tempfile/forver/" . $foreverlj;
                    if (!file_exists("." . $newfile)) {
                        mkdir("." . $newfile, 0777, true);
                    }
                    copy("." . $data['logo'], "." . $newfile . "/" . $newname);
                    @unlink("." . $vo['logo']);
                    @unlink("." . $data['logo']);
                    $data['logo'] = $newfile . "/" . $newname;
                }
            }
            //echo 2;die;
            //deldir("./Public/tempfile/temp");
            //deldir("./Public/upload/");
            $result = $model->where(array('id' => $id))->save($data);
            if ($result !== false) {
                $this->success('修改成功', U('Member/edit', array('id' => $id,'p'=>$p)), 1);
                exit;
            } else {
                $this->error($model->getError());
            }
        }
        $this->display();
    }

    public function edit1(){
        $id=I('get.id');
        $p=I("get.p");
        $model=M('Member');
        $vo=$model->where(array('id'=>$id))->find();
        $url=$vo['pic_list'];
        if($url){
            $urls=explode('|',$url);
        }else{
            $urls=$url;
        }
        $this->urls=$urls;
        $this->vo=$vo;
        if(IS_POST) {
            $data=I('post.');
            //dump($data);die;
            if ($data['logo']) {
                if ($vo['logo'] != $data['logo']) {
                    $newname = basename($data['logo']);
                    $foreverlj = basename("../" . dirname($data['logo']));
                    //echo $data['logo'];
                    //var_dump($foreverlj);die;
                    $newfile = "/Public/tempfile/forver/" . $foreverlj;
                    if (!file_exists("." . $newfile)) {
                        mkdir("." . $newfile, 0777, true);
                    }
                    copy("." . $data['logo'], "." . $newfile . "/" . $newname);
                    @unlink("." . $vo['logo']);
                    @unlink("." . $data['logo']);
                    $data['logo'] = $newfile . "/" . $newname;
                }
            }
            //echo 2;die;
            //deldir("./Public/tempfile/temp");
            //deldir("./Public/upload/");
            $result = $model->where(array('id' => $id))->save($data);
            if ($result !== false) {
                $this->success('修改成功', U('Member/edit1', array('id' => $id,'p'=>$p)), 1);
                exit;
            } else {
                $this->error($model->getError());
            }
        }
        $this->display();
    }

    public function ajax_uploads(){
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'temp/',
            'rootPath'=>'./Public/tempfile/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        //session('admin_date',date('Ymd'));
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile']['error']==0){
            $ret=$upload->upload();
            if($ret) {
                $log="/Public/tempfile/".$ret['myfile']['savepath'].$ret['myfile']['savename'];
            }else{
                $log='';
            }
        }
        //$url.="window.parent.document.getElementById('logo').value='';";
        $url="<script>window.parent.document.getElementById('logo').value='$log';";
        //$url.="window.parent.document.getElementById('target').src='$log';";
       /* $url.="window.parent.document.getElementById('preview').src='$log';";
        $url.="window.parent.document.getElementById('preview2').src='$log';";
        $url.="window.parent.document.getElementsByTagName('img')[1].src='$log';";
        $url.="window.parent.document.getElementsByTagName('img')[2].src='$log';";
        $url.="window.parent.document.getElementsByTagName('img')[3].src='$log';";*/
        $url.="</script>";
        echo  $url;die;
        //echo json_encode(array('message'=>'boge','status'=>true));

    }
    public function caijian(){
        import("Admin.Image.Image");
        $images = new \Images();
        $image=I('post.tp');
        $image=".".$image;
        //$image = "./Public/caijian/0000.jpg";
        //echo $image;die;
        $res = $images->thumb($image,false,1);
        if($res == false){
            echo json_encode(array('big'=>$res));
        }elseif(is_array($res)){
            echo json_encode($res);
        }elseif(is_string($res)){
            echo json_encode(array('big'=>$res));
        }
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
