<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class TypeController extends Controller {
    public function lis(){
        $tmodel=M('Type');
        $model=M('Category');
        $list=$tmodel->select();
        foreach($list as $k=>$v){
            $v['cname']=$model->where(array('id'=>$v['cate_id']))->getField('cate_name');
            $list[$k]=$v;
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function add(){
        $model=M('Category');
        $tmodel=M('Type');
        if(IS_POST){
            if($tmodel->add(I('post.'))){
                $this->success('添加成功',U('Type/lis'),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        $list=$model->order('sort_num asc')->select();
        $this->list=$list;
        $this->display();
    }
    public function edit(){
        $model=M('Category');
        $tmodel=M('Type');
        $id=I('get.id');
        if(IS_POST){
            if($tmodel->where('id='.$id)->save(I('post.'))!==false){
                $this->success('修改成功',U('Type/lis'),1);
                exit;
            }else{
                $this->error($tmodel->getError());
            }
        }
        $this->va=$tmodel->find($id);
        $list=$model->order('sort_num asc')->select();
        $this->list=$list;
        $this->display();
    }
    public function delete(){
        $tmodel=M('Type');
        $id=I('get.id');
        if($tmodel->delete($id)!==false){
            $this->success('删除成功',U('Type/lis'),1);
            exit;
        }else{
            $this->error($tmodel->getError());
        }
    }
}