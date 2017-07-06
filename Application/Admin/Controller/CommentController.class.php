<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class CommentController extends BaseController {
    public function lis(){
        $list=$this->search(10);
        $this->list=$list['data'];
        $this->page=$list['page'];
        $this->display();
    }
    public function search($pageSize=20){
        $cmodel=M('Comment');
        $name = bian(I('get.keyword'));
        if($name){
            $where['b.name|c.tname']=array('like', "%$name%");
        }
        $count = $cmodel->alias('a')->
        join("left join __MEMBER__ b on a.member_id=b.id")->
        join("left join __ACTIVE__ c on a.active_id=c.id")->
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
            $data['data'] = $cmodel->alias('a')->field('a.*,b.name,c.tname,c.address,c.acode')->
            join("left join __MEMBER__ b on a.member_id=b.id")->
            join("left join __ACTIVE__ c on a.active_id=c.id")->
            where($where)->order("uptime desc")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
    }
	public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Comment");
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->delete();
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Comment/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function adetial(){
        $id = I('get.aid');
        $model = M('Active');
        $list = $model->alias('a')->field('a.*,b.name pname')
            ->join('left join __MEMBER__ b on a.member_id=b.id')->
            where(array('a.id' => $id))->find();
        //dump($list);
        //举报消息
        $amodel=M("Report");
        //发布人举报
        $alist1=$amodel->where(array('aid'=>$id,'uid'=>$list['member_id']))->
        find();
        //已参与人数
        $omodel=M("Order");
        $anumber=$omodel->where(array('active_id'=>$id,'pay_status'=>array('in','1,2,3,5,6')))->count();
        $list['sl']=$anumber;
        $this->alist1=$alist1;
        //dump($alist1);
        //参与人举报
       /* $alist2=$amodel->alias('a')->field('a.content,a.addtime,b.name')
            ->join('left join __MEMBER__ b on a.uid=b.id')->
            where(array('a.aid'=>$id,'a.uid'=>array('neq',$list['member_id'])))->select();
        $this->alist2=$alist2;*/
        $this->list = $list;
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=M('Comment');
        $model->where(array(
            'id'=>$id,
        ))->delete();
        $this->success('删除成功',U('Comment/lis'),1);
    }
    public function sh(){
        $id=I('get.id');
        $p=I('get.p');
        $model=M('Comment');
        $list=$model->find($id);
        if($list['is_show']==1){
            $model->where(array('id'=>$id))->save(array('is_show'=>'2'));
            $this->success('审核成功',U('Comment/lis',array('p'=>$p)),1);
        }else{
            $model->where(array('id'=>$id))->save(array('is_show'=>'1'));
            $this->success('取消审核',U('Comment/lis',array('p'=>$p)),1);
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $type=I('post.type');
        $val=I('post.xs');
        $model=M('Comment');
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