<?php
namespace Admin\Controller;
use Think\Controller;
class GyearController extends BaseController {
    public function add(){
        $model=D('Gyear');
        if(IS_POST){
            //dump($_POST);exit;
            if($model->create()){
                if($model->add()){
                    $this->success('添加成功',U('Gyear/add'),1);
                    exit;
                }else{
                    //echo $model->getLastSql();exit;
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getLastSql();exit;
                $this->error($model->getError());
            }
        }
        $this->display();
    }
    public function lis(){
        $mmodel=D('Gyear');
        $dlist=$mmodel->order('gyear')->select();
        $this->dlist=$dlist;
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=D('Gyear');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$model->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Gyear/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }

    public function edit(){
        $id=I('get.id');
        $model=D('Gyear');
       /* if(IS_POST){
            if($model->create()){
                dump(I('post.'));exit;
                $result=$model->where(array('id'=>$id))->save();
                if($result!==false){
                    $this->success('修改成功',U('Member/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                echo $model->getLastSql();exit;
                $this->error($model->getError());
            }

        }*/
        $this->display();
    }
}
?>