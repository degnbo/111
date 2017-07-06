<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class InformationController extends BaseController {
    public function lis(){
        $imodel=D('Information');
        /*$dlist=$imodel->alias('a')->field("a.*,b.cate_name,c.typename type_name")
            ->join('left join beidou_category b on b.id=a.cate_id')
            ->join('left join beidou_type c on c.id=a.type_id')
            ->select();*/
        $data=$imodel->search(12);
        $this->dlist=$data['data'];
        $this->page=$data['page'];
	    $this->display();
    }
    public function add(){
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        $this->tlist=$tlist;
        $cmodel=D('Category');
        $this->clist=$cmodel->getTree();
        $imodel=D('Information');
        if(IS_POST){
            if($imodel->create()){
                if($lastid=$imodel->add()){
                    $this->success('添加成功',U('Information/add'),1);
                    exit;
                }else{
                    $this->error($imodel->getError());
                }
            }else{
                $this->error($imodel->getError());
            }
        }
        $this->display();

    }
    public function edit(){
        $id=I('get.id');
        //echo $id;exit;
        $p=I('get.p');
        $imodel=D('Information');
        if(IS_POST){
            //dump(I('post.'));exit;
            if($imodel->create()){
                //echo I('post.title','','htmlspecialchars');die;
                $result=$imodel->where(array('id'=>$id))->save();
                if($result!==false){
                    $this->success('修改成功',U('Information/edit',array('id'=>$id,'p'=>$p)),1);
                    exit;
                }else{
                    $this->error($imodel->getError());
                }
            }else{
                $this->error($imodel->getError());
            }
        }
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        $this->tlist=$tlist;
        $cmodel=D('Category');
        $this->clist=$cmodel->getTree();
        $vo=$imodel->where(array('id'=>$id))->find();
        $this->vo=$vo;
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=D('Information');
        $model->where(array(
            'id'=>$id,
        ))->delete();
        $this->success('删除成功',U('Information/lis'),1);
    }
	public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Information");
		foreach($data as $k=>$v){
			$log1=$model->field('sm_logo,mid_logo')->where('id='.$v)->find();
			$mylog=str_replace('sm_','',$log1['sm_logo']);
			unlink('./Public/Uploads/'.$mylog);
			unlink('./Public/Uploads/'.$log1['mid_logo']);
			unlink('./Public/Uploads/'.$log1['sm_logo']);
		}
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->delete();
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Information/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $val=I('post.val');
        $model=M('Information');
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
}