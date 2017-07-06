<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class AdminController extends BaseController {
    public function index(){
        $model=D('Admin');
        $id=session('home_id');
        //dump($model->getNav($id));exit;
        $this->display();
    }
    public function add(){
        $model=D('Admin');
        if(IS_POST){
            $data=I('post.');
			//dump($data);die;
            if(in_array(1,$data['role_id']) && $data['is_deny']=='1'){
                $this->error('超级管理员不能被禁用');
                exit;
            }
            //dump($_POST);exit;
            if($model->create($_POST,1)){
                if($model->add()){
                    $this->success('添加成功',U('Admin/add'),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getlastSql();exit;
                $this->error($model->getError());
            }
        }
        $rmodel=M('Role');
        $this->rolelist=$rmodel->select();
        $this->dlist=$model->select();
        $this->display();

    }
    public function lis(){
        $model=D('Admin');
        $dlist=$model->lis();
        //处理的东西
        /*foreach($dlist as $key =>$val){
            if($val['role_id']){
                $val['roleid']=explode(',',$val['role_id']);
            }
            $dlist[$key]=$val;
        }*/
        //dump($dlist);exit;
        $this->dlist=$dlist;
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $model=D('Admin');
        if(IS_POST){
            $data=I('post.');
            if(in_array(1,$data['role_id']) && $data['is_deny']=='1'){
                $this->success('超级管理员不能被禁用',U("Admin/edit",array('id'=>$id)),1);
                exit;
            }
            //dump($_POST);exit;
            if($model->create()){
                $re=$model->where(array('id'=>$id))->save();
                if($re!==false){
                    $this->success('修改成功',U('Admin/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                $this->error($model->getError());
            }
        }
        $rmodel=D('Role');
        $this->rolelist=$rmodel->select();
        $roleId=$model->getRoleId($id);
        $this->roleId=$roleId;
		//dump($roleId);die;
        $this->vo=$model->find($id);
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=M('Admin');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$model->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Admin/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }
    public function ajaxChange(){
        $id=I('post.mid');
        $val=I('post.val');
        $model=D('Admin');
        file_put_contents('e:/a.txt',$val);
        $roleId=$model->getRoleId($id);
        if(in_array(1,$roleId)){
            echo 1;
        }else{
            if($val==1){
                $model->where(array(
                    'id'=>$id
                ))->save(array(
                    'is_deny'=>'0'));
                echo 0;
                exit;
            }else{
                $model->where(array(
                    'id'=>$id
                ))->save(array(
                    'is_deny'=>'1'));
                echo 0;
                exit;
            }
        }
    }


}