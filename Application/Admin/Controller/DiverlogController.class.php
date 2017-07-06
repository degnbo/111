<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class DiverlogController extends BaseController {
    public function lis(){
        $list=$this->search(10);
        $this->list=$list['data'];
        $this->page=$list['page'];
        //dump($list);die;
        $this->display();
    }
    public function search($pageSize=20,$type='1'){
        $cmodel=M('Diverlog');
        $name = bian(I('get.keyword'));
        $where['is_show']=array('eq',$type);
        if($name){
            $where['b.name|a.course_name|a.teacher']=$name;
        }
        $count = $cmodel->alias('a')->
        join("left join __MEMBER__ b on a.member_id=b.id")->
        where($where)->count();
        if(!$count){
            $data['page']='无查询数据!';
        }else{
            $page = new \Think\Page($count, $pageSize);
            $page->rollPage=6;
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $cmodel->alias('a')->field('a.*,b.name')->
            join("left join __MEMBER__ b on a.member_id=b.id")->
            where($where)->order("ptime desc")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
    }
	public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Diverlog");
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->save(array('is_show'=>'2'));
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Diverlog/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function delete(){
        $id=I('get.id');
        $model=M('Diverlog');
        $model->where(array(
            'id'=>$id,
        ))->save(array('is_show'=>'2'));
        $this->success('删除成功',U('Diverlog/lis'),1);
    }
    public function rebak(){
        $list=$this->search(10,'2');
        $this->list=$list['data'];
        $this->page=$list['page'];
        //dump($list);die;
        $this->display();
    }

    public function xq(){
        $id=I('get.id');
        $model=M('Loglist');
        $dmodel=M('Diverlog');
        $list=$model->where(array('log_id'=>$id))->order('sort_num')->select();
        $this->list=$list;
        $lj=$dmodel->find($id);
        $this->lj=$lj;
        $this->display();
    }
    //批量还原
    public function rebakmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Diverlog");
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->save(array('is_show'=>'1'));
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('还原成功',U('Diverlog/rebak'),1);die;
        }else{
            $this->error('还原失败');die;
        }
    }
    //还原
    public function hy(){
        $id=I('get.id');
        $model=M('Diverlog');
        $model->where(array(
            'id'=>$id,
        ))->save(array('is_show'=>'1'));
        $this->success('还原成功',U('Diverlog/rebak'),1);
    }
    public function sh(){
        $id=I('get.id');
        $p=I('get.p');
        $model=M('Diverlog');
        $list=$model->find($id);
        if($list['is_show']==1){
            $model->where(array('id'=>$id))->save(array('is_show'=>'2'));
            $this->success('审核成功',U('Diverlog/lis',array('p'=>$p)),1);
        }else{
            $model->where(array('id'=>$id))->save(array('is_show'=>'1'));
            $this->success('取消审核',U('Diverlog/lis',array('p'=>$p)),1);
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $type=I('post.type');
        $val=I('post.xs');
        $model=M('Diverlog');
        if($type==1){
            if($val==1){
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_rec'=>'2'));
                exit;
            }else{
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_rec'=>'2'));
                exit;
            }
        }else{
            if($val==1){
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_hot'=>'2'));
                exit;
            }else{
                $model->where(array(
                    'id'=>$mid
                ))->save(array(
                    'is_hot'=>'1'));
                exit;
            }
        }


    }
}