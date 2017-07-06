<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class PeizhiController extends Controller {
    public function lis(){
        $pmodel=M('Peizhi');
        //$id=I('get.id');
        $list=$pmodel->find();
        if($list){
            $this->list=$list;
        }
        $id=I('post.id');
        if(IS_POST){
            if($id){
                $data=I('post.');
				$data['rs']=str_replace('，',',',$data['rs']);
                //dump($data);//exit;
                $logo=$this->editpic($id);
                if($logo){
                    $data['logo']=$logo;
                }
                $result=$pmodel->save($data);
                if($result!==false){
                    $this->success('修改成功',U('Peizhi/lis'),1);
                    exit;
                }else{
                    $this->error($pmodel->getError());
                }
            }else{
                //dump($_FILES);
                $data=I('post.');
				$data['rs']=str_replace('，',',',$data['rs']);
                $logo=$this->addpic($id);
                $data['logo']=$logo;
                //dump($data);exit;
                if($lastid=$pmodel->add($data)){
                    $this->success('添加成功',U('Peizhi/lis'),1);
                    exit;
                }else{
                    $this->error($pmodel->getError());
                }
            }
        }
        $this->display();
    }
    public function addpic(){
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'peizhi/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile']['error']==0){
            $info=$upload->upload();
            if($info) {
                //dump($info);exit;
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $midlog=$info['myfile']['savepath'].'mid_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(546,60)->save('./Public/Uploads/'.$midlog);
            }
        }
        return $midlog;
    }
    public function editpic($id){
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'peizhi/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $midlog='';
        if($_FILES['myfile']['error']==0){
            $info=$upload->upload();
            if($info) {
                //echo $info;exit;
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $midlog=$info['myfile']['savepath'].'mid_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(546,60,6)->save('./Public/Uploads/'.$midlog);
				$image->thumb(546,60,6)->save('./Public/Uploads/'.$midlog);
                $model=M('Peizhi');
                $logo=$model->where(array('id'=>$id))->getField('logo');
                //echo $logo;//exit;
                $tu=str_replace('mid_','',$logo);
                unlink('./Public/Uploads/'.$logo);
                unlink('./Public/Uploads/'.$tu);
            }
        }
        return $midlog;
    }
}