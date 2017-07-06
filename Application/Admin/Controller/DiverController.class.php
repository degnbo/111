<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class DiverController extends BaseController {
    public function lis(){
        $dmodel=M('Diver');
        /*$dlist=$imodel->alias('a')->field("a.*,b.cate_name,c.typename type_name")
            ->join('left join beidou_category b on b.id=a.cate_id')
            ->join('left join beidou_type c on c.id=a.type_id')
            ->select();*/
        $data=$this->search(12);
        $this->dlist=$data['data'];
        $this->page=$data['page'];
	    $this->display();
    }
    public function search($pageSize=20){
        $cmodel=M('Diver');
        $name = bian(I('get.keyword'));
        if($name){
            $where['goods_name']=$name;
        }
        $count = $cmodel->where($where)->count();
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
            $data['data'] = $cmodel->where($where)->order("sort_num")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
    }
    public function add(){
        $imodel=M('Diver');
        if(IS_POST){
            $data=I('post.');
            //dump($data);//die;
            $config=array(
                'maxSize'    =>    2*1024*1024,
                'savePath'   =>'goods/',
                'rootPath'=>'./Public/Uploads/',
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,
                'subName'    =>    array('date','Ymd')
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            if($_FILES['myfile']['error']==0){
                $ret=$upload->upload();
                //dump($ret);die;
                if($ret) {
                    $log1="/Public/Uploads/".$ret['myfile']['savepath'].$ret['myfile']['savename'];
                }else{
                    $log1='';
                }
            }
            $data['ptime']=strtotime($data['ptime']);
            $data['logo']=$log1;
            if($lastid=$imodel->add($data)){
                $imodel->where(array('id'=>$lastid))->save(array('sort_num'=>$lastid));
                $this->success('添加成功',U('Diver/lis'),1);
                exit;
            }else{
                $this->error('添加失败！');
            }
        }
        $this->display();

    }
    public function edit(){
        $id=I('get.id');
        //echo $id;exit;
        $p=I('get.p');
        $imodel=M('Diver');
        $vo=$imodel->where(array('id'=>$id))->find();
        $this->vo=$vo;
        if(IS_POST){
            $data=I('post.');
            $config=array(
                'maxSize'    =>    2*1024*1024,
                'savePath'   =>'goods/',
                'rootPath'=>'./Public/Uploads/',
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,
                'subName'    =>    array('date','Ymd')
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            if($_FILES['myfile']['error']==0){
                $ret=$upload->upload();
                if($ret) {
                    $log1="/Public/Uploads/".$ret['myfile']['savepath'].$ret['myfile']['savename'];
                    @unlink(".".$vo['logo']);
                }else{
                    $log1='';
                }
                $data['logo']=$log1;
            }
            if($data['ptime']){
                $data['ptime']=strtotime($data['ptime']);
            }
            //dump(I('post.'));exit;
                //echo I('post.title','','htmlspecialchars');die;
            $result=$imodel->where(array('id'=>$id))->save($data);
            if($result!==false){
                $this->success('修改成功',U('Diver/edit',array('id'=>$id,'p'=>$p)),1);
                exit;
            }else{
                $this->error($imodel->getError());
            }
        }
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=M('Diver');
        $list=$model->find($id);
        $model->where(array(
            'id'=>$id,
        ))->delete();
        unlink('.'.$list['logo']);
        $this->success('删除成功',U('Diver/lis'),1);
    }
	public function delmany(){
        $data=I('post.plsc');
        //dump($data);die;
        $model=M("Diver");
		foreach($data as $k=>$v){
			$log=$model->field('logo')->where('id='.$v)->find();
			unlink('.'.$log);
		}
        if($data){
            $tj=implode(',',$data);
        }
        $result=$model->where("id in(".$tj.")")->delete();
        //echo $model->getLastSql();die;
        if($result!==false){
            $this->success('删除成功',U('Diver/lis'),1);die;
        }else{
            $this->error('删除失败');die;
        }
    }
    public function ajaxChange(){
        $mid=I('post.mid');
        $val=I('post.val');
        $model=M('Diver');
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
                'is_rec'=>'1'));
            exit;
        }
    }
}