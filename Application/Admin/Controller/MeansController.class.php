<?php
namespace Admin\Controller;
use Think\Controller;
class MeansController extends BaseController {
    public function add(){
        $this->display();
    }
    public function lis(){
        $mmodel=D('Means');
        $dlist=$mmodel->select();
        $this->dlist=$dlist;
        $this->display();
    }
    public function edit(){
        $mid=I('get.mid');
        //echo $mid;die;
        $model=M('Means');
       /* if($id){
            $this->vo=$model->where(array('id'=>$id))->find();
        }*/
        //课程分类
        $tlist=$this->getTree("Ctype",'id','asc');
        $this->tlist=$tlist;
        //dump($tlist);die;
        $list=M('Means')->where(array('member_id'=>$mid))->find();
        if($list){
            if(IS_POST){
                $data=I('post.');
                //$data['member_des']=removeXSS($_POST['member_des']);
                //$data['member_des']=$_POST['member_des'];
                $data['member_des']=htmlentities($_POST['member_des'],ENT_COMPAT ,"UTF-8");
                //$data['member_id']=$mid;
                //dump($data);exit;
                $result=$model->where(array('member_id'=>$mid))->save($data);
                if($result!==false){
                    $this->success('修改成功',U('Means/edit',array('mid'=>$mid)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }
            $this->vo=$list;
        }else{
            if(IS_POST){
                $data=I('post.');
                $data['member_id']=$mid;
                $data['member_des']=htmlentities($_POST['member_des'],ENT_COMPAT ,"UTF-8");
                $result=$model->where(array('id'=>$mid))->add($data);
                if($result!==false){
                    $this->success('添加成功',U('Means/edit',array('mid'=>$mid)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }
            //$this->vo='';
        }

        $this->display();
    }
    public function edit1(){
        $mid=I('get.mid');
        //echo $mid;die;
        $model=M('Student');
        $list=$model->where(array('member_id'=>$mid))->find();
        if($list){
            if(IS_POST){
                $data=I('post.');
                $result=$model->where(array('member_id'=>$mid))->save($data);
                if($result!==false){
                    $this->success('修改成功',U('Means/edit1',array('mid'=>$mid)),1);
                    exit;
                }else{
                    $this->error('修改失败！');
                }
            }
            $this->vo=$list;
        }else{
            if(IS_POST){
                $data=I('post.');
                $data['member_id']=$mid;
                $result=$model->where(array('id'=>$mid))->add($data);
                if($result!==false){
                    $this->success('添加成功',U('Means/edit1',array('mid'=>$mid)),1);
                    exit;
                }else{
                    $this->error('添加失败！');
                }
            }
        }
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=D('Means');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$model->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Means/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }
    public function ajaxChange(){
        $val=I('post.val');
        $mid=I('post.mid');
        $model=M('Means');
        if($val==1){
            $model->where(array(
                'member_id'=>$mid
            ))->save(array(
                'gk_is_show'=>'0'));
            exit;
        }else{
            $model->where(array(
                'member_id'=>$mid
            ))->save(array(
                'gk_is_show'=>'1'));
            exit;
        }
    }
}
?>