<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    public function index(){
        $this->display();
    }
    public function ajax_remove(){
        $url=I('post.lj');
        $id=I('post.id');
        $model=M("Article");
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
    public function ajax_remove_sj(){
        $id=I('post.id');
        $model=M('Article');
        $list=$model->find($id);
        unlink(".".$list['thumb_logo']);
        unlink(".".$list['logo']);
        $result=$model->delete($id);
        if($result!==false){
            echo json_encode(array(
                'message'=>'删除成功',
                'result'=>true));die;
        }else{
            echo json_encode(array(
                'message'=>'删除失败',
                'result'=>false));die;
        }
    }
    public function lis(){
        $model=M('Article');
        $p=I('get.p');
        $p1=I('get.p1');
        $id=I('get.id');
        if($id){
            //echo 1;die;
            $vo=$model->find($id);
            $this->vo=$vo;
            if(IS_POST){
                if($_FILES['myfile']['size']>=1024*1024*2){
                    $this->error('上传图片不能大于2M');
                }
                $data=I('post.');
                if($data['uptime']){
                    $data['uptime']=strtotime($data['uptime']);
                }else{
                    $data['uptime']=time();
                }
                if($data['logo']){
                    if($data['logo']!=$vo['logo']){
                        //echo 1;die;
                        $oldlogo=$data['logo'];
                        $path_dir=pathinfo($data['logo']);
                        //dump($path_dir);die;
                        $file_dir=$path_dir['dirname'];
                        //$file_name=$path_dir['basename'];
                        $file_ext=$path_dir['extension'];
                        //dump($path_dir);//die;
                        //dump($path_dir);die;
                        //echo $file_dir;//die;
                        $file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
                        //echo $file_name;die;
                        $newname=str_replace("image","truefile",$file_dir);
                        //echo $newname;die;
                        if(!file_exists(".".$newname)){
                            mkdir(".".$newname,0777,true);
                        }
                        $newfile=".".$newname."/".$file_name;
                        copy(".".$data['logo'],$newfile);
                        //echo $newname;die;
                        unlink(".".$vo['logo']);
                        $data['logo']=$newname."/".$file_name;
                        if($data['width'] && $data['height']){
                            $image = new \Think\Image();
                            $image->open($newfile);
                            $savename = 'thumb_'.$file_name;
                            $image->thumb($data['width'],$data['height'],6)->save('.'.$newname."/".$savename);
                            $data['thumb_logo']=$newname."/".$savename;
                            unlink(".".$vo['thumb_logo']);
                            //$data['logo']=$newname."/".$file_name;
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
                if($model->where(array('id'=>$id))->save($data)!==false){
                    $this->success('修改成功',U('Article/lis',array('id'=>$id,'p'=>$p,'p1'=>$p1)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }
        }else{
            if(IS_POST){
                if($_FILES['myfile']['size']>=1024*1024*2){
                    $this->error('上传图片不能大于2M');
                }
                //dump($_POST);exit;{
                $data=I('post.');
                $data['addtime']=time();
                if($data['uptime']){
                    $data['uptime']=strtotime($data['uptime']);
                }else{
                    $data['uptime']=time();
                }
                if($data['logo']){
                    $oldlogo=$data['logo'];
                    $path_dir=pathinfo($data['logo']);
                    $file_dir=$path_dir['dirname'];
                    //$file_name=$path_dir['basename'];
                    $file_ext=$path_dir['extension'];
                    $newname=str_replace("image","truefile",$file_dir);
                    if(!file_exists(".".$newname)){
                        mkdir(".".$newname,0777,true);
                    }
                    //echo $newname;die;
                    $file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
                    $newfile=".".$newname."/".$file_name;
                    copy(".".$data['logo'],$newfile);
                    $data['logo']=$newname."/".$file_name;
                    if($data['width'] && $data['height']){
                        $image = new \Think\Image();
                        $image->open('.'.$oldlogo);
                        $savename = 'thumb_'.$file_name;
                        $image->thumb($data['width'],$data['height'],6)->save('.'.$newname."/".$savename);
                        $data['thumb_logo']=$newname."/".$savename;
                    }
                }
                if($lastid=$model->add($data)){
                    $model->where(array('id'=>$lastid))->setField('sort_num',$lastid);
                    $this->success('添加成功',U('Article/lis',array()),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }
        }
        $xqlist=$this->search1();

        //foreach($tlist as )
        //dump($xqlist[1]);die;
        $this->zs=count($xqlist);
        $this->data=$xqlist;
        //dump($xqlist);die;
        $tlist=M('Type')->select();
        $this->tlist=$tlist;
        $clist=getTree("Ctype");
        $this->clist=$clist;

        $this->display();
    }
    public function search1($model='Article',$pageSize=15,$tid=''){
        /************************************* 翻页 ****************************************/
        $model=M($model);
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        //dump($tlist);die;
        foreach($tlist as $k=>$v){
            $where['type_id']=$v['id'];
            $count = $model->where($where)->count();
            //echo $this->getLastSql();
            //echo $count;exit;
            if(!$count){
                $data['page']='无查询数据!';
                //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
            }else {
                if($k==0){
                    //$_GET['type_id']=$v['id'];
                    //$_GET['p1']=1;
                    import("Admin.Page.Page");
                    $page = new \Page($count, $pageSize);
                    $page->rollPage = 3;
                    //dump($page);die;
                    $page->setConfig('theme', '%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END%');
                    // 配置翻页的样式
                    $page->setConfig('prev', '上一页');
                    $page->setConfig('next', '下一页');
                    $data['page'] = $page->show();
                    $data['data'] = $model->where($where)->order("sort_num desc")->limit($page->firstRow.','.$page->listRows)->select();
                    $v['xq']=$data;
                    //echo $model->getLastSql();die;
                    //dump($data);die;
                }else{
                    //$_GET['type_id']=$v['id'];
                    //$_GET['p']=1;
                    import("Admin.Page1.Page1");
                    $page1 = new \Page1($count, $pageSize);
                    $page1->rollPage = 3;
                    //dump($page1);die;
                    $page1->setConfig('theme', '%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END%');
                    // 配置翻页的样式
                    $page1->setConfig('prev', '上一页');
                    $page1->setConfig('next', '下一页');
                    $data['page'] = $page1->show();
                    $data['data'] = $model->where($where)->order("sort_num desc")->limit($page1->firstRow.','.$page1->listRows)->select();
                    $v['xq']=$data;
                }
            }
            $tlist[$k]=$v;
                //echo $model->getLastSql();exit;
        }
        return $tlist;
    }
    //article内容添加
    public function article(){
        $model=M('Article');
        $cid=I('get.cid');
        if(IS_POST){
			if($_FILES['myfile']['size']>=1024*1024*2){
                $this->error('上传图片不能大于2M');
            }
            //dump($_POST);exit;{
            $data=I('post.');
            $data['addtime']=time();
            if($data['uptime']){
                $data['uptime']=strtotime($data['uptime']);
            }else{
                $data['uptime']=time();
            }
            $info=$this->upload();
            if($info){
                $yt=$info['myfile']['savepath'].$info['myfile']['savename'];
                if($data['width'] && $data['height']){
                    $tp=$this->thumb('./Public/Uploads/'.$yt,$info['myfile']['savename'],$data['width'],$data['height']);
                    $data['logo']=$yt;
                    $data['thumb_logo']=$tp;
                }else{
                    $tp=$this->thumb('./Public/Uploads/'.$yt,$info['myfile']['savename']);
                    $data['logo']=$yt;
                    $data['thumb_logo']=$tp;
                }
            }
            if($lastid=$model->add($data)){
                $model->where(array('id'=>$lastid))->setField('sort_num',$lastid);
                $this->success('添加成功',U('Article/article',array('cid'=>$cid)),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }

        //$this->dlist=$model->getTree();
        //dump($this->dlist);
        //<input type="text" id="sort{$dlist.id}" value="{$dlist.sort}" onChange="return updateGoods('sort','{$dlist.id}');" name="sort" class="goods_input_text" />
        $this->display();

    }
	//清除缓存
	public function update(){
		$id=I('get.id');
		$cid=I('get.cid');
		$p=I('get.p',1);
		//$url="./Application/Html/Home/Article/wzy_".$id."_".$cid;
		//echo $url;die;
		unlink("./Application/Html/Home/Article/wzy_".$id.".shtml");
		$this->success('更新成功',U('Article/lis',array('cid'=>$cid,'p'=>$p)),1);
	}
    public function upload($size=2,$lanmu='article/'){
        $config=array(
            'maxSize'    =>    $size*1024*1024,
            'savePath'   =>$lanmu,
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile']['error']==0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        }else {
            return false;
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
    public function all(){
        $model=M('Article');
        $cid=I('get.cid');
        $cmodel=M('Category');
        $tmodel=M('Type');
        $va=$cmodel->where('id='.$cid)->find();
		$this->va=$va;
		$data=$this->search();
        foreach($data['data'] as $k=>$v){
            $v['tname']=$tmodel->where(array('id'=>$v['type_id']))->getField('tname');
            $data['data'][$k]=$v;
        }
        //dump($data['data']);die;
		$this->plist=$data['page'];
        $this->dlist=$data['data'];
        $clist=$tmodel->select();
        $this->clist=$clist;
        $this->display();
    }
    public function edit(){
        $p=I('get.p');
        $id=I('get.id');
        $cid=I('get.cid');
        //echo $cid;die;
        $model=M('Article');
        if(IS_POST){
			if($_FILES['myfile']['size']>=1024*1024*2){
                $this->error('上传图片不能大于2M');
            }
            $data=I('post.');
            if($data['uptime']){
                $data['uptime']=strtotime($data['uptime']);
            }else{
                $data['uptime']=time();
            }
            $info=$this->upload();
            //dump($info);die;
            if($info){
                $yt=$info['myfile']['savepath'].$info['myfile']['savename'];
                if($data['width'] && $data['height']){
                    $tp=$this->thumb('./Public/Uploads/'.$yt,$info['myfile']['savename'],$data['width'],$data['height']);
                    $data['logo']=$yt;
                    $data['thumb_logo']=$tp;
                }else{
                    $tp=$this->thumb('./Public/Uploads/'.$yt,$info['myfile']['savename']);
                    $data['logo']=$yt;
                    $data['thumb_logo']=$tp;
                    //echo $tp;die;
                }
                $logo=$model->where(array('id'=>$id))->find();
                unlink('./Public/Uploads/'.$logo['logo']);
                unlink('./Public/Uploads/'.$logo['thumb_logo']);
            }else{
                $logo=$model->where(array('id'=>$id))->find();
                //dump($logo);die;
                if($data['width'] && $data['height']){
                    $image = new \Think\Image();
                    $image->open("./Public/Uploads/".$logo['logo']);
                    $image->thumb($data['width'],$data['height'],6)->save("./Public/Uploads/".$logo['thumb_logo']);
                }
            }
            if($model->where(array('id'=>$id))->save($data)!==false){
                $this->success('修改成功',U('Article/lis',array('cid'=>$cid,'p'=>$p)),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        //dump($this->dlist);
        //省份
        $this->vo=$model->where(array('id'=>$id))->find();
        $this->display();
    }

    public function delete(){
        $id=I('get.id');
        $cid=I('get.cid');
        $model=M('Article');
        $model->where(array(
            'id'=>$id,
        ))->delete();
        $logo=$model->where(array('id'=>$id))->find();
        unlink('./Public/Uploads/'.$logo['logo']);
        unlink('./Public/Uploads/'.$logo['thumb_logo']);
        $this->success('删除成功',U('Article/lis',array('cid'=>$cid)),1);
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $type=I('post.type');
        $val=I('post.xs');
        file_put_contents('d:/a.txt',$type);
        $model=M('Article');
        if($type==1){
            if($val==1){
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_show'=>'0'));
                exit;
            }else{
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_show'=>'1'));
                exit;
            }
        }else{
            if($val==1){
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_rec'=>'0'));
                exit;
            }else{
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_rec'=>'1'));
                exit;
            }
        }
    }
    public function ajax_up(){
        $id=I('post.mid');
        $sort=I('post.val');
        $tid=I('post.tid');
        //echo json_encode($sort);die;
        $model=M('Article');
        $data=array();
        if($tid){
            $where['type_id']=$tid;
        }
        $where['sort_num']=array('gt',$sort);
        $sj=$model->where($where)->order('sort_num asc')->find();
        //echo json_encode($sj);die;
        if($sj){
            $model->where(array('id'=>$id))->save(array('sort_num'=>$sj['sort_num']));
            $model->where(array('id'=>$sj['id']))->save(array('sort_num'=>$sort));
            //file_put_contents("d:/b.txt",$model->getLastSql());
            $now=$model->find($id);
            $updata=$model->find($sj['id']);
            $data['now']=$now;
            $data['updata']=$updata;
            echo json_encode($data);die;
        }else{
            echo json_encode($data);
        }
    }
    public function ajax_down(){
        $id=I('post.mid');
        $sort=I('post.val');
        $tid=I('post.tid');
        //echo json_encode($sort);die;
        $model=M('Article');
        $data=array();
        if($tid){
            $where['type_id']=$tid;
        }
        $where['sort_num']=array('lt',$sort);
        $sj=$model->where($where)->order('sort_num desc')->find();
        //echo json_encode($sj);die;
        if($sj){
            $model->where(array('id'=>$id))->save(array('sort_num'=>$sj['sort_num']));
            $model->where(array('id'=>$sj['id']))->save(array('sort_num'=>$sort));
            $now=$model->find($id);
            $updata=$model->find($sj['id']);
            $data['now']=$now;
            $data['updata']=$updata;
            echo json_encode($data);die;
        }else{
            echo json_encode($data);
        }
    }


}