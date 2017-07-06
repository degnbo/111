<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CourseController extends BaseController {
    public function index(){
        $this->display();
    }
    public function lis(){
        $list=$this->search(10);
        //dump($list);die;
        $this->assign(array(
            'dlist'=>$list['data'],
            'plist'=>$list['page']
        ));
        $clist=getTree();
        $this->clist=$clist;
        $this->display();
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
    public function add(){
        //一级分类
        $cmodel=M('Ctype');
        $flist=$cmodel->where(array('pid'=>0))->select();
        $this->flist=$flist;
        //strip_tags
        $model=M('Course');
        if(IS_POST){
            //dump($_POST);exit;{
            $data=I('post.');
            if($data['start_time']){
                $data['start_time']=strtotime($data['start_time']);
            }else{
                $data['start_time']=time();
            }
            //dump($data);die;
            if($lastid=$model->add($data)){
                $model->where(array('id'=>$lastid))->setField('sort_num',$lastid);
                $this->success('添加成功',U('Course/add'),1);
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
    public function search($pageSize=20){
        $cmodel=M('Course');
        $goodsName = bian(I('get.keyword'));
        if($goodsName){
            $data['a.name'] = array('like', "%$goodsName%");
            $data['a.price'] = array('like', "%$goodsName%");
            $data['b.tname'] = array('like', "%$goodsName%");
            $data['c.tname'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        $cid=I('get.stype');
        if($cid){
            $cateid=getChildren($cid,'Ctype');
            $cateid[]=$cid;
            $where['a.stype']=array('in',$cateid);
        }
        //dump($where);die;
        $orderby = 'id';  // 默认排序的字段
        $orderway = 'asc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc')
            $orderway = 'asc';
        if($odby == 'id_desc')
            $orderway = 'desc';
        $count = $cmodel->alias('a')->
        join("left join __CTYPE__ b on a.ftype=b.id")->
        join("left join __CTYPE__ c on a.stype=c.id")->
        where($where)->count();
        //echo $this->getLastSql();
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
            $data['data'] = $cmodel->alias('a')->field('a.*,b.tname fname,c.tname sname')->
            join("left join __CTYPE__ b on a.ftype=b.id")->
            join("left join __CTYPE__ c on a.stype=c.id")->
            where($where)->order("sort_num asc,$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->select();
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
        $cmodel=M('Ctype');
        $model=M('Course');
        $vo=$model->find($id);
        $this->vo=$vo;
        $flist=$cmodel->where(array('pid'=>0))->select();
        $this->flist=$flist;
        $slist=$cmodel->where(array('pid'=>$vo['ftype']))->select();
        $this->slist=$slist;
        //strip_tags
        if(IS_POST){
            //dump($_POST);exit;
            $data=I('post.');
            if($data['start_time']){
                $data['start_time']=strtotime($data['start_time']);
            }else{
                $data['start_time']=time();
            }
            //dump($data);die;
            $result=$model->where(array('id'=>$data['id']))->save($data);
            if($result!==false){
                $this->success('修改成功',U('Course/lis',array('p'=>$p)),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        //教练列表
        $jlist=M('Member')->where(array('type'=>'2'))->select();
        //dump($jlist);die;
        $this->jlist=$jlist;
        //$this->dlist=$model->getTree();
        //dump($this->dlist);
        $this->display();
    }
    public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Course");
        foreach($data as $k=>$v){
            $log=$model->field('thumb_logo,logo')->where('id='.$v)->find();
            unlink('./Public/Uploads/'.$log['logo']);
            unlink('./Public/Uploads/'.$log['thumb_logo']);
        }
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->delete();
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Course/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $val=I('post.val');
        $model=M('Course');
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