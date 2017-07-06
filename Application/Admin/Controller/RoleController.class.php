<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class RoleController extends BaseController {
    public function index(){
        $this->display();
    }
    public function add(){
        $model=D('Role');
        if(IS_POST){
            //dump($_POST);exit;
            if($model->create($_POST)){
                if($model->add()){
                    $this->success('添加成功',U('Role/add'),1);
                    exit;
                }else{
                    echo $model->getLastSql();exit;
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getlastSql();exit;
                $this->error($model->getError());
            }
        }
        $pmodel=D('Privilege');
        $this->plist=$pmodel->getTree();
        $this->display();

    }
    public function lis(){
        $rmodel=D('Role');
        $dlist=$rmodel->alias('a')->
        field('a.id,a.role_name,GROUP_CONCAT(c.pri_name) as pri_list')->
        join('left join beidou_role_pri b on a.id=b.role_id')
            ->join('left join beidou_privilege c on c.id=b.pri_id')
            ->group('a.id')
            ->select();
        $this->dlist=$dlist;
        //dump($dlist);die;
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $model=D('Role');
        if(IS_POST){
            if($model->create()){
                //dump(I('post.'));exit;
                $result=$model->where(array('id'=>$id))->save();
                if($result!==false){
                    $this->success('修改成功',U('Role/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                echo $model->getLastSql();exit;
                $this->error($model->getError());
            }
        }
        $this->vo=$model->find($id);
        $pmodel=D('Privilege');
        $rpmodel=M('RolePri');
        $pri_id=$rpmodel->field('pri_id')->where(array('role_id'=>$id))->select();
        $arr=array();
        foreach($pri_id as $val){
            $arr[]=$val['pri_id'];
        }
        $this->parr=$arr;
        $this->plist=$pmodel->getTree();
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=D('Role');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$model->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Role/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }

}