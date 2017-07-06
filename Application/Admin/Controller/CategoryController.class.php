<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends BaseController {
    public function index(){
        $cmodel=M('Category');
        $list=$cmodel->select();
        //dump($list);die;
        foreach($list as $k=>$v){
            $cmodel->where(array('id'=>$v['id']))->save(array('sort_num'=>$v['id']));
        }
        //$this->display();
    }
    public function ajax_up(){
        $id=I('post.mid');
        $pid=I('post.pid');
        $sort=I('post.val');
        //echo json_encode($sort);die;
        $model=M('Category');
        $data=array();
        $sj=$model->where(array(
            'sort_num'=>array('lt',$sort),
            'pid'=>$pid
        ))->order('sort_num desc')->find();
        //echo $model->getLastSql();die;
        //echo json_encode($sj);die;
        if($sj){
            $model->where(array('id'=>$id))->save(array('sort_num'=>$sj['sort_num']));
            $model->where(array('id'=>$sj['id']))->save(array('sort_num'=>$sort));
            //file_put_contents("d:/b.txt",$model->getLastSql());
            $now=$model->find($id);
            $updata=$model->find($sj['id']);
            $now['js']=$this->get_jw($id);
            $updata['js']=$this->get_all($sj['id']);
            $data['now']=$now;
            $data['updata']=$updata;
            //if
            //$data['jw']=$hz;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }
    public function ceshi(){
        dump($this->get_all(4));
        dump($this->get_jw(2));
    }
    public function get_all($id){
        $arr=array();
        $model=M('Category');
        $list=$model->order('sort_num asc')->select();
        $childs=$this->fzfindChild($list,$id);
        //dump($childs);
        if($childs){
            $childs[]=$id;
            $arr=$childs;
        }else{
            $arr[]=$id;
        }
        return $arr;
    }
    //找最后一个孩子
    public function get_jw($id){
        $model=M('Category');
        $list=$model->order('sort_num')->select();
        $childs=$this->findChild($list,$id);
        if($childs){
            return end($childs);
        }else{
            return $id;
        }
    }
    //倒序排列子类和父类，从子类的最后一个排到第一个再到父类
    function fzfindChild($data,$id,$clear=true)
    {
        static $arr =array();
        if ($clear) {
            $arr =array();
        }
        foreach ($data as $val) {
            if ($val['pid'] == $id) {
                array_unshift($arr,$val['id']);
                $this->fzfindChild($data, $val['id'], false);
            }
        }
        return $arr;
    }
    function findChild($data,$id,$clear=true)
    {
        static $arr =array();
        if ($clear) {
            $arr =array();
        }
        foreach ($data as $val) {
            if ($val['pid'] == $id) {
                $arr[] = $val['id'];
                $this->findChild($data, $val['id'], false);
            }
        }
        return $arr;
    }
    public function ajax_down(){
        $id=I('post.mid');
        $pid=I('post.pid');
        $sort=I('post.val');
        //echo json_encode($sort);die;
        $model=M('Category');
        $data=array();
        $sj=$model->where(array(
            'sort_num'=>array('gt',$sort),
            'pid'=>$pid
        ))->order('sort_num asc')->find();
        //echo json_encode($sj);die;
        if($sj){
            $model->where(array('id'=>$id))->save(array('sort_num'=>$sj['sort_num']));
            $model->where(array('id'=>$sj['id']))->save(array('sort_num'=>$sort));
            $now=$model->find($id);
            $updata=$model->find($sj['id']);
            $now['js']=$this->get_all($id);
            $updata['js']=$this->get_jw($sj['id']);
            $data['now']=$now;
            $data['updata']=$updata;
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }
    public function add(){
        $model=D('Category');
        $tmodel=M('Type');
        $this->tlist=$tmodel->select();
        if(IS_POST){
            //dump($_POST);exit;
            if($data=$model->create($_POST)){
                //dump($data);
                $data['content']=htmlentities($data['content'],ENT_COMPAT ,"UTF-8");
                //dump($data);die;
                if($lastid=$model->add($data)){
                    $model->where(array('id'=>$lastid))->setField('sort_num',$lastid);
                    $this->success('添加成功',U('Category/add'),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getlastSql();exit;
                $this->error($model->getError());
            }
        }
        $this->dlist=$model->getTree();
        //dump($this->dlist);
        $this->display();

    }
    public function lis(){
        $model=D('Category');
        $this->dlist=$model->getTree();
        //dump($this->dlist);
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $model=D('Category');
        //echo $id;die;

        //dump($this->tlist);exit;
        if(IS_POST){
            if($model->create()){
                //dump($model->create());die;
                if($model->where(array('id'=>$id))->save()!==false){
                    $this->success('修改成功',U('Category/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                $this->error($model->getError());
            }
        }
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        $tcmodel=M('TypeCate');
        $typeid= $tcmodel->where(array('cate_id'=>$id))->getField('type_id');
        echo $typeid['type_id'];//exit;
        if($typeid){
            $this->tid=$typeid['type_id'];
        }
        $this->assign('tlist',$tlist);
        $this->dlist=$model->getTree();
        //dump($this->dlist);
        $this->vo=$model->find($id);
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=M('Category');
        $list=$model->find($id);
        if($list['cate_logo']){
            unlink('./Public/Uploads/'.$list['logo']);
        }
        $result=$model->delete($id);
        /*if(false!==$result){
            $this->success('删除成功',U('Category/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();*/
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $type=I('post.type');
        $val=I('post.xs');
        $model=M('Category');
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
                    'is_nav'=>'0'));
                exit;
            }else{
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_nav'=>'1'));
                exit;
            }
        }


    }

}