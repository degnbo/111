<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CtypeController extends Controller {
    public function lis(){
        $model=M('Ctype');
        $list=$model->select();
        $tlist=array();
        foreach($list as $k=>$v){
            if($v['pid']==0){
                foreach($list as $m=>$n){
                    if($v['id']==$n['pid']){
                        $v['xq'][]=$n;
                    }
                }
                $tlist[]=$v;
            }
        }
        $this->tlist=$tlist;
        //dump($tlist);die;
        $this->display();
    }
    public function add(){
        $model=M('Category');
        $tmodel=M('Ctype');
        if(IS_POST){
            $data=I('post.');
            $data['ptime']=time();
            if($tmodel->add($data)){
                $this->success('添加成功',U('Ctype/lis'),1);
                exit;
            }else{
                $this->error($model->getError());
            }
        }
        $list=$model->where(array('pid'=>2))->order('sort_num asc')->select();
        $this->dlist=$this->getTree();
        $this->list=$list;
        $this->display();
    }
    public function edit(){
        $model=M('Category');
        $tmodel=M('Ctype');
        $id=I('get.id');
        $va=$tmodel->find($id);
        $this->va=$va;
        if(IS_POST){
            $data=I('post.');
            if(!$va['ptime']){
                $data['ptime']=time();
            }
            if($tmodel->where('id='.$id)->save($data)!==false){
                $this->success('修改成功',U('Ctype/lis'),1);
                exit;
            }else{
                $this->error($tmodel->getError());
            }
        }
        $list=$model->where(array('pid'=>2))->order('sort_num asc')->select();
        $this->list=$list;
        $this->dlist=$this->getTree();
        $this->display();
    }
    public function getTree(){
        $model=M('Ctype');
        $data=$model->select();
        return $this->resort($data);
    }
    public function resort($data,$pid=0,$level=0,$clear=true){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach($data as $key=>$val){
            if($val['pid']==$pid){
                $val['level']=$level;
                $arr[]=$val;
                $this->resort($data,$val['id'],$level+1,false);
            }
        }
        return $arr;
    }
    public function delete(){
        $tmodel=M('Ctype');
        $id=I('get.id');
        if($tmodel->delete($id)!==false){
            $this->success('删除成功',U('Ctype/lis'),1);
            exit;
        }else{
            $this->error($tmodel->getError());
        }
    }
}