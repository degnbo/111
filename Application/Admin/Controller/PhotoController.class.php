<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class PhotoController extends Controller {
    function upload() {
        //echo 1;die;
        import('ORG.Net.UploadFile');
        $upload = new \UploadFile();// 实例化上传类
        $upload->maxSize  = 1024*1024*2 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $savepath='./Public/shangchuan/'.date('Ymd').'/';
        if (!file_exists($savepath)){
            mkdir($savepath);
        }
        $upload->savePath =  $savepath;// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        //echo json_encode(array('name'=>'fsf','sex'=>'fsf'));die;
        //file_put_contents("d:/a.txt",print_r($info,true));
        print_r($this->J(__ROOT__.'/'.$info[0]['savepath'].'/'.$info[0]['savename']));die;
    }
    function J($str){
        return str_replace('./', '', str_replace('//', '/', $str));
    }

    function del() {
        //$src=str_replace(__ROOT__.'/', '', str_replace('//', '/', $_GET['src']));
        //file_put_contents("d:/a.txt",$src);
		//echo $src;die;
		$src=$_GET['src'];
        if(file_exists(".".$src)){
            unlink(".".$src);
        }
        print_r($_GET['src']);
        exit();
    }
	public function delete(){
        $id=I('get.id');
        $model=M('Photo');
        $logo=$model->where(array('id'=>$id))->find();
        //unlink('./Public/Uploads/'.$logo['logo']);
        //unlink('./Public/Uploads/'.$logo['thumb_logo']);
        $url=$model->where(array('id'=>$id))->getField('url');
        $urls=explode('|',$url);
		//dump($urls);die;
        foreach($urls as $k=>$v){
			if($v){
				//echo __ROOT__.$v;die;
				unlink(".".$v);
			}            
        }
        $model->where(array(
            'id'=>$id,
        ))->delete();
        $this->success('删除成功',U('Photo/lis'),1);
    }
    public function lis(){
		$bmodel=M('Photo');
		$list=$bmodel->select();
		foreach($list as $k=>$v){
			if($v['url']){
				$urls=explode('|',$v['url']);
			}else{
				$urls[]=$v['url'];
			}
            $v['banner']=$urls;
            $list[$k]=$v;			
		}
		//dump($list);die;
		$this->list=$list[0];
        $this->display();
        //http://www.yemafinancial.com<?php echo jq("__SELF__")   /hd/xmt.htlml
    }
	public function zxxq(){
		$id=I('get.id');
		$bmodel=M('Photo');
		$list=$bmodel->find($id);
		if($list['url']){
				$urls=explode('|',$list['url']);
		}else{
			$urls[]=$list['url'];
		}
		$list['banner']=$urls;
		$this->list=$list;
        $this->display();
    }
	public function add(){
        $this->display();
        //http://www.yemafinancial.com<?php echo jq("__SELF__")   /hd/xmt.htlml
    }
    public function sc()
    {
        $model = M('Photo');
        $list=$model->find();
        $data=I('post.');
        //dump($data);die;
        if($list){
            if($data['url']) {
                $data['url'] = $list['url'] ."|". $data['url'];
            }else{
                $data['url']= $list['url'];
            }
            $result = $model->where(array('id'=>$list['id']))->save($data);
            if ($result!==false) {
                $this->success('添加成功', U('Photo/lis'), 1);
            } else {
                $this->error('添加失败');
            }
        }else{
            $result = $model->add($data);
            if ($result) {
                $this->success('添加成功', U('Photo/lis'), 1);
            } else {
                $this->error('添加失败');
            }
        }
    }
    public function thumb($file,$filename,$width=200,$height=200,$type=1){
        $image = new \Think\Image();
        $image->open($file);
        $savepath = str_replace($filename, '', $file); // ./Public/Uploads/2015-01-01/
        $savename = 'thumb_'.$filename;
        $image->thumb($width,$height,$type)->save($savepath.$savename);
        $url=str_replace('./Public/Uploads/','',$savepath.$savename);
        return $url;
    }
	public function ajax_del(){
		$tp=I('post.tp');
		$id=I('post.id');
		$model=M('Photo');
		$url=$model->where(array('id'=>$id))->find();
        $urls=explode('|',$url['url']);
        foreach($urls as $k=>$v){
			if($tp==$v){
				unset($urls[$k]);
				unlink(".".$tp);
				//unlink(__ROOT__.$tp);
			}         
        }
		$lj=implode('|',$urls);
		$url=$model->where(array('id'=>$id))->setField('url',$lj);
        echo 1;die;		
	}
	public function ajax_edit(){
        //dump($_FILES);
        $tp=I('post.tp');
        $id=I('get.id');
        //echo $id;
        //echo $tp;die;
		if($_FILES['myfile2']['size']>C("UPLOADS_SIZE")){
                $this->error('上传图片不能大于2M');
        }
        $model=M('Photo');
        $url=$model->where(array('id'=>$id))->find();
        //echo $tp;die;
        $info=$this->upload2(2,"shangchuan/");
        $urls=explode('|',$url['url']);
        foreach($urls as $k=>$v){
            if($tp==$v){
                $v="/Public/".$info['myfile2']['savepath'].$info['myfile2']['savename'];
                unlink(".".$tp);
            }
            $urls[$k]=$v;
        }
        $lj=implode('|',$urls);
        $result=$model->where(array('id'=>$id))->setField('url',$lj);
        if($result!==false){
            $this->success('图片修改成功',U('Photo/edit',array('id'=>$id),1));
            exit;
        }else{
            $this->error($model->getError());
        }
    }
	public function add_pic(){
		$id=I('get.id');
		//echo $id;die;
		if($_FILES['myfile1']['size']>C("UPLOADS_SIZE")){
                $this->error('上传图片不能大于2M');
        }
		$model=M('Photo');
		$url=$model->where(array('id'=>$id))->find();       
		$info=$this->upload2(2,"shangchuan/");
		//dump($info);die;
		if($url['url']){
			$urls=explode('|',$url['url']);
			$urls[]="/Public/".$info['myfile1']['savepath'].$info['myfile1']['savename'];
			$lj=implode('|',$urls);
		}else{
			$urls="/Public/".$info['myfile1']['savepath'].$info['myfile1']['savename'];
			$lj=$urls;
		}					
		$result=$model->where(array('id'=>$id))->save(array('url'=>$lj));
		if($result!==false){
			$this->success('上传成功',U('Photo/edit',array('id'=>$id),1));
			exit;
        }else{
			$this->error($model->getError());
        }        		
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
        }else {
            return false;
        }
    }
	public function edit(){
		$model=M('Photo');
		$list=$model->find();
        if($list){
            $id=$list['id'];
        }else{
            $id=0;
        }
		//echo C('UPLOADS_SIZE');
		if(IS_POST){
			$data=I('post.');
			$data['url_name']=str_replace('，',',',$data['url_name']);
			if($model->where(array('id'=>$id))->save($data)!==false){
                $this->success('修改成功',U('Photo/lis'),1);
                exit;
            }else{
                $this->error($model->getError());
            }
		}		
		$vo=$model->where(array('id'=>$id))->find();
        $url=$vo['url'];
        if($url){
			$urls=explode('|',$url);
		}else{
			$urls=$url;
		} 
        $this->vo=$vo;		
        $this->urls=$urls;
		$this->display();
	}

}

?>