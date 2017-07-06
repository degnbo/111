<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CbannerController extends BaseController{
    public function index()
    {
        $this->display();
    }
    public function add(){
        $cmodel=D('Category');
        //所属nav列表
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        //所属师资列表
        $slist=$cmodel->getChildren(9);
        array_unshift($slist,9);
        $sdata=array();
        foreach($slist as $k=>$v){
            $cname=$cmodel->field('id,cate_name')->where(array('id'=>$v))->find();
            $sdata[]=$cname;
        }
        $this->sdata=$sdata;
        $bmodel=D('Banner');
        if(IS_POST){
            $data=I('post.');
            //dump($data);exit;
            $ar=array();
            $pic=$data['pic'];
            $smpic=$data['sm_pic'];
            $midpic=$data['mid_pic'];
            $savePath='./Public/Uploads/';
            $date=I('session.admin_date');
            $todayDir = 'banner/'.$date;
            //echo $savePath.$todayDir;exit;
            if(!file_exists($savePath.$todayDir)){
                mkdir($savePath.$todayDir, 0777, TRUE);
            }
            //copy时必须目录存在
            foreach($pic as $k=>$v){
                $newPic = str_replace('temp', 'banner', $v);
                $midnewPic = str_replace('temp', 'banner', $midpic[$k]);
                $smnewPic = str_replace('temp', 'banner', $smpic[$k]);
				$midnewPic1 = str_replace('temp', 'banner', $midpic1[$k]);
                $smnewPic1 = str_replace('temp', 'banner', $smpic1[$k]);
                // 把图片从临时目录移到到商品目录
                //echo $savePath . $v;exit;
                copy($savePath.$v, $savePath.$newPic);
                copy($savePath.$midpic[$k], $savePath.$midnewPic);
                copy($savePath.$smpic[$k], $savePath.$smnewPic);
                copy($savePath.$midpic1[$k], $savePath.$midnewPic1);
                copy($savePath.$smpic1[$k], $savePath.$smnewPic1);
                $data['pic']=$newPic ;
                $data['sm_pic']= $smnewPic;
                $data['mid_pic']=$midnewPic;
				$data['sj_sm_pic']= $smnewPic1;
                $data['sj_mid_pic']=$midnewPic1;
                $ar[]=$data;
            }
            deldir($savePath.'temp');
            //dump($ar);exit;
            //if($bmodel->create($_POST)){
                if($bmodel->addAll($ar)){
                    $this->success('添加成功',U('Banner/add'),1);
                    exit;
                }else{
                    $this->error($bmodel->getError());
                }
           /* }else{
                $this->error($bmodel->getError());
            }*/
        }
        $this->display();
    }
    public function ajax_upload_pic()
    {
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'temp/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        session('admin_date',date('Ymd'));
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['fileField']['error']==0){
            $ret=$upload->upload();
            if($ret) {
                $log=$ret['pic']['savepath'].$ret['pic']['savename'];
                $smlog=$ret['pic']['savepath'].'sm_'.$ret['pic']['savename'];
                $midlog=$ret['pic']['savepath'].'mid_'.$ret['pic']['savename'];
                $sj_smlog=$ret['pic']['savepath'].'sm_sj'.$ret['pic']['savename'];
                $sj_midlog=$ret['pic']['savepath'].'mid_sj'.$ret['pic']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(717,284,6)->save('./Public/Uploads/'.$midlog);
                $image->thumb(710,281,6)->save('./Public/Uploads/'.$sj_midlog);
                $image->thumb(670,430,6)->save('./Public/Uploads/'.$sj_smlog);
                $image->thumb(260,168,6)->save('./Public/Uploads/'.$smlog);
            }
            //file_put_contents('d:/t.txt',$log);exit;
            /********************* 上传图片成功之后，输出js到iframe窗口中执行 ***********************/
            // 构造一个图片的LI标签，并添加一个删除的按钮
            $img = "<li>";
            sleep(1);
            $img .= "<input onclick=\'this.parentNode.parentNode.removeChild(this.parentNode);\' type=\'button\' value=\'删除\' /><br />"; // 放个删除图片的按钮
            $img .= "<img src=\'/Public/Uploads/{$smlog}\' width=\'120px\' height=\'60px\'/>";  // 图片显示在页面中
            $img .= "<input name=\'pic[]\' type=\'hidden\' value=\'{$log}\' />";       // 原图路径放到页面中
            $img .= "<input name=\'mid_pic[]\' type=\'hidden\' value=\'{$midlog}\' />";     // 中图路径放到页面中
            $img .= "<input name=\'sm_pic[]\' type=\'hidden\' value=\'{$smlog}\' />";    // 小图路径放到页面中
            $img .= "<input name=\'sj_mid_pic[]\' type=\'hidden\' value=\'{$sj_midlog}\' />";     // 中图路径放到页面中
            $img .= "<input name=\'sj_sm_pic[]\' type=\'hidden\' value=\'{$sj_smlog}\' />";
            $img .= "</li>";
            // 把图片显示在页面中上
            $js = "<script>";
            $js .= "parent.document.getElementById('pic_container').innerHTML+='$img';";   // 图片显示在页面上
            $js .= "parent.document.getElementById('loading').innerHTML='';";            // 把加载的loading图片去掉
            //$js .= "parent.document.getElementById('upload_form').reset();";            // 重置图片表单
            $js .= "</script>";
            echo $js;
        }
    }
    public function lis(){
        $model=M('Goods');
        $list=$model->field('id,goods_name')->select();
        $this->list=$list;
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $bmodel=M('Banner');
        $list=$bmodel->where(array('id'=>$id))->find();
        $this->list=$list;
        $cmodel=D('Category');
        //所属nav列表
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        //所属师资列表
        $slist=$cmodel->getChildren(9);
        array_unshift($slist,9);
        $sdata=array();
        foreach($slist as $k=>$v){
            $cname=$cmodel->field('id,cate_name')->where(array('id'=>$v))->find();
            $sdata[]=$cname;
        }
        $this->sdata=$sdata;
        if(IS_POST) {
            //echo $id;
            $cid=I('post.cate_id');
            if ($bmodel->create()) {
                //dump(I('post.'));exit;
                $result = $bmodel->where(array('cate_id' => $cid))->save();
                if ($result !== false) {
                    $this->success('修改成功', U('Banner/edit', array('id' => $id)), 1);
                    exit;
                } else {
                    $this->error($bmodel->getError());
                }
            } else {
                $this->error($bmodel->getError());
            }
        }
        $this->display();
    }
    public function edittu(){
        $model=D('Banner');
        $id=I('get.id');
        $list=$model->where(array('id'=>$id))->find();
        $cate_id=$list['cate_id'];
        //echo $cate_id;
        if(IS_POST){
            $data=I('post.');
            if($data){
                //dump($data);exit;
                $oldtupian=$model->where(array('cate_id'=>$cate_id))->select();
                foreach($oldtupian as $k1=>$v1){
                    unlink('./Public/Uploads/'.$v1['pic']);
                    unlink('./Public/Uploads/'.$v1['mid_pic']);
                    unlink('./Public/Uploads/'.$v1['sm_pic']);
                    unlink('./Public/Uploads/'.$v1['sj_mid_pic']);
                    unlink('./Public/Uploads/'.$v1['sj_sm_pic']);
                }
                $model->where(array('cate_id'=>$cate_id))->delete();
                $ar=array();
                $pic=$data['pic'];
                $smpic=$data['sm_pic'];
                $midpic=$data['mid_pic'];
                $smpic1=$data['sj_sm_pic'];
                $midpic1=$data['sj_mid_pic'];
                $savePath='./Public/Uploads/';
                $date=I('session.admin_date');
                $todayDir = 'banner/'.$date;
                //echo $savePath.$todayDir;exit;
                if(!file_exists($savePath.$todayDir)){
                    mkdir($savePath.$todayDir, 0777, TRUE);
                }
                //copy时必须目录存在
                foreach($pic as $k=>$v){
                    $newPic = str_replace('temp', 'banner', $v);
                    $midnewPic = str_replace('temp', 'banner', $midpic[$k]);
                    $smnewPic = str_replace('temp', 'banner', $smpic[$k]);
                    $midnewPic1 = str_replace('temp', 'banner', $midpic1[$k]);
                    $smnewPic1 = str_replace('temp', 'banner', $smpic1[$k]);
                    // 把图片从临时目录移到到商品目录
                    //echo $savePath . $v;exit;
                    copy($savePath.$v, $savePath.$newPic);
                    copy($savePath.$midpic[$k], $savePath.$midnewPic);
                    copy($savePath.$smpic[$k], $savePath.$smnewPic);
                    copy($savePath.$midpic1[$k], $savePath.$midnewPic1);
                    copy($savePath.$smpic1[$k], $savePath.$smnewPic1);
                    $data['pic']=$newPic ;
                    $data['sm_pic']= $smnewPic;
                    $data['mid_pic']=$midnewPic;
                    $data['sj_sm_pic']= $smnewPic1;
                    $data['sj_mid_pic']=$midnewPic1;
                    $data['cate_id']=$list['cate_id'];
                    $data['cid']=$list['cid'];
                    $data['banner_name']=$list['banner_name'];
                    $data['banner_des']=$list['banner_des'];
                    $ar[]=$data;
                }
                deldir($savePath.'temp');
            }
            if($model->addAll($ar)){
                $this->success('修改成功',U('Banner/lis'),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        $this->display();
    }
    public function xiangqing(){
        $id=I('get.id');
        $model=M('Banner');
        $cate_id=$model->where(array('id'=>$id))->getField('cate_id');
        echo $cate_id;
        $list=$model->where(array('cate_id'=>$cate_id))->select();
        $this->list=$list;
        //dump($list);
        $this->display();


    }

}