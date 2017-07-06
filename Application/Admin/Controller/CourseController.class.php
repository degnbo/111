<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CourseController extends BaseController {
    public function index(){
        $this->display();
    }
    public function lis(){
        $list=$this->search(10,2);
        //dump($list);die;
        foreach($list['data'] as $k=>$v){
            $v['join_num']=$this->get_total_count($v['id']);
            $list['data'][$k]=$v;
        }
        $this->assign(array(
            'dlist'=>$list['data'],
            'plist'=>$list['page']
        ));
        $cmodel=M("Category");
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        $this->display();
    }
    public function xq(){
        $id=I('get.id');
        //已参与人数
        $omodel=M("Order");
        $list=$omodel->alias('a')->field('b.name,a.pay_status')->
        where(array('a.active_id'=>$id))
            ->join('left join __MEMBER__ b on a.member_id=b.id')->
        select();
        $amodel=M("Active");
        $zs=$amodel->field('number,acode')->find($id);
        $omodel=M("Order");
        $anumber=$omodel->where(array('active_id'=>$id,'pay_status'=>array('in','1,2,3,5,6')))->count();
        $this->sl=$anumber;
        //echo $omodel->getLastSql();
        $this->zs=$zs;
        //dump($zs);die;
        $this->list=$list;
        //

        $this->display();
    }
    public function lis1(){
        $list=$this->search(10,1);
        //dump($list);die;
        foreach($list['data'] as $k=>$v){
            $v['join_num']=$this->get_total_count($v['id']);
            $list['data'][$k]=$v;
        }
        $this->assign(array(
            'dlist'=>$list['data'],
            'plist'=>$list['page']
        ));
        $cmodel=M("Category");
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        $this->display();
    }
    public function get_total_count($id){
        $model=M('Order');
            $list=$model->where(array(
            'pay_status'=>array('in','1,2,3,5,6'),
            'active_id'=>$id
        ))->count();
        return $list;
    }
    public function get_list(){
        $id=I('post.id');
        if($id){
            $cmodel=M('Ctype');
            $data=$cmodel->where(array('pid'=>$id))->select();
            $flist['message']='成功';
            $flist['result']=true;
            $flist['xq']=$data;
            echo json_encode($flist);die;
        }else{
            echo json_encode(array(
                'message'=>'失败',
                'result'=>false));die;
        }
    }
    public function ewm()
    {
        $aid = I('get.aid');
        $lj = "https://" . $_SERVER['HTTP_HOST'];
        //$lj="http://192.168.0.72";
        import('Home.Ewm.phpqrcode', APP_PATH, ".php");
        //$lj."/index.php/Home/Index/del_sign/aid/"
        $url = $aid;
        //$value = $_GET['url'];//二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        \QRcode::png($url,false,$errorCorrectionLevel, $matrixPointSize);
    }
    public function add(){
        //一级分类
        $cmodel=M('Category');
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        //strip_tags
        $model=M('Active');
        if(IS_POST){
            $data=I('post.');
            //dump($_FILES);die;
            if($data['start_time']){
                $data['start_time']=strtotime($data['start_time']);
            }else{
                $data['start_time']=time();
            }
            $data['addtime']=time();
            $data['acode']=1;
            $logo=$this->savepic();
            //dump($logo);die;
            if($logo){
                $data['pic']=$logo['logo'];
                $data['thumb_pic']=$logo['thumb_logo'];
            }
            //dump($data);die;
            if($lastid=$model->add($data)){
                $model->where(array('id'=>$lastid))->setField('sort_num',$lastid);
                $this->success('添加成功',U('Course/lis'),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        //教练列表
        $jlist=M('Member')->where(array('type'=>'2'))->select();
        $this->jlist=$jlist;
        //$this->dlist=$model->getTree();
        //dump($this->dlist);
        $this->display();

    }
    public  function savepic(){
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'active/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $log=false;
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile']['error']==0){
            $info=$upload->upload();
            if($info) {
                //dump($info);exit;
                $logo=$info['myfile']['savepath'].$info['myfile']['savename'];
                $smlogo=$info['myfile']['savepath'].'thumb_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$logo);
                $image->thumb(160,160,3)->save('./Public/Uploads/'.$smlogo);
                $tp['logo']=$logo;
                $tp['thumb_logo']=$smlogo;
                $log=$tp;
            }else{
                return $log;
            }
        }
        return $log;
    }
    public function search($pageSize=20,$type=1){
        $cmodel=M('Active');
        $where['a.is_show']=array('eq','1');
        $goodsName = bian(I('get.keyword'));
        if($goodsName){
            $data['a.tname'] = array('like', "%$goodsName%");
            $data['a.price'] = array('like', "%$goodsName%");
            $data['b.name'] = array('like', "%$goodsName%");
            $data['c.cate_name'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        if($type==1){
            $where['b.type']=array('eq','1');
        }else{
            $where['b.type']=array('eq','2');
        }
        $cid=I('get.type');
        if($cid){
            $where['a.type_id']=array('eq',$cid);
        }
        $count = $cmodel->alias('a')->field('a.*,b.name te_name,c.cate_name')->
        join("left join __MEMBER__ b on a.member_id=b.id")->
        join("left join __CATEGORY__ c on c.id=a.type_id")->
        where($where)->count();
        //echo $cmodel->getLastSql();die;
        //echo $count;exit;
        if(!$count){
            $data['page']='无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        }else{
            $page = new \Think\Page($count, $pageSize);
            $page->rollPage=6;
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $cmodel->alias('a')->alias('a')->field('a.*,b.name te_name,c.cate_name')->
            join("left join __MEMBER__ b on a.member_id=b.id")->
            join("left join __CATEGORY__ c on c.id=a.type_id")->
            where($where)->limit($page->firstRow.','.$page->listRows)->select();
            //dump($data);die;
            ///echo $this->getLastSql();exit;
        }
        return $data;

        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }
    public function edit(){
        //一级分类
        $id=I('get.id');
        $p=I('get.p');
        $cmodel=M('Category');
        $model=M('Active');
        $vo=$model->find($id);
        //dump(pathinfo($vo['pic']));die;
        $this->vo=$vo;
        $clist=$cmodel->where(array('pid'=>0))->select();
        $this->clist=$clist;
        //strip_tags
        if(IS_POST){
            //dump($_POST);exit;
            $data=I('post.');
            if($data['start_time']){
                $data['start_time']=strtotime($data['start_time']);
            }else{
                $data['start_time']=time();
            }
            $logo=$this->savepic();
            if($logo){
                $data['pic']=$logo['logo'];
                $data['thum_pic']=$logo['thumb_logo'];
                unlink("./Public/Uploads/".$vo['thumb_pic']);
            }else{
                if($vo['pic']){
                    $basepath=pathinfo($vo['pic']);
                    $tlogo=$basepath['dirname'].'/thumb_'.$basepath['basename'];
                    if(!$vo['thumb_pic']){
                        $image=new \Think\Image();
                        $image->open('./Public/Uploads/'.$vo['pic']);
                        $image->thumb(160,160,3)->save('./Public/Uploads/'.$tlogo);
                        $data['thumb_pic']=$tlogo;
                    }else{
                        if($tlogo!=$vo['thumb_pic']){
                            $image=new \Think\Image();
                            $image->open('./Public/Uploads/'.$vo['pic']);
                            $image->thumb(160,160,3)->save('./Public/Uploads/'.$tlogo);
                            @unlink("./Public/Uploads/".$vo['thumb_pic']);
                            $data['thumb_pic']=$tlogo;
                        }
                    }
                }
            }
            $result=$model->where(array('id'=>$id))->save($data);
            if($result!==false){
                if($logo){
                    unlink("./Public/Uploads/".$vo['pic']);
                }
                $this->success('修改成功',U('Course/lis',array('p'=>$p)),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        //教练列表
        $jlist=M('Member')->where(array('type'=>'2'))->select();
        $this->jlist=$jlist;
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=M("Active");
        //$list=$model->find($id);
        $result=$model->where(array('id'=>$id))->save(array('is_show'=>'0'));
        if($result!==false){
            /*if($list['pic']){
                unlink("./Public/Uploads/".$list['pic']);
            }*/
            $this->success('删除成功',U('Course/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }

    public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        if(empty($data)){
            $this->error('请选择');die;
        }
        $model=M("Active");
        $result=$model->where(array('id'=>array('in',$data)))->save(array('is_show'=>'0'));
        /*foreach($data as $k=>$v){
            $log=$model->field('thumb_pic,pic')->where('id='.$v)->find();
            @unlink('./Public/Uploads/'.$log['pic']);
            @unlink('./Public/Uploads/'.$log['thumb_pic']);
        }
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->delete();*/
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Course/lis1'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $val=I('post.val');
        $model=M('Active');
        if($val==1){
            $model->where(array(
                'id'=>$mid
            ))->save(array(
                'is_nav'=>'2'));
            exit;
        }else{
            $model->where(array(
                'id'=>$mid
            ))->save(array(
                'is_nav'=>'1'));
            exit;
        }
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
        $url="<script>window.parent.document.getElementById('logo').value+='$log'";
                $url.="</script>";
        echo  $url;die;
        //echo json_encode(array('message'=>'boge','status'=>true));
    }

}