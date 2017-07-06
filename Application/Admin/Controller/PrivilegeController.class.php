<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class PrivilegeController extends BaseController {
    public function index(){
        $this->display();
    }
    public function add(){
        $model=D('Privilege');
        //dump(I('post.'));exit;
        if(IS_POST){
            //dump($_POST);exit;
            if($model->create()){
				//dump($model->create($_POST));exit;
                if($model->add()){
                    //$this->redirect("Privilege/lis");
                    $this->success('添加成功',U('Privilege/lis'),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getlastSql();exit;
                $this->error($model->getError());
            }
        }
        $this->dlist=$model->getTree();
        //dump( $this->dlist);
        $this->dlist;
        $this->display();

    }
    public function lis(){
        $pmodel=D('Privilege');
        $this->dlist=$pmodel->getTree();
        //dump($this->dlist);
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $model=D('Privilege');
        if(IS_POST){
            if($model->create()){
                $result=$model->where(array('id'=>$id))->save();
                if($result!==false){
                    $this->success('修改成功',U('Privilege/edit',array('id'=>$id)),1);
                    exit;
                }else{
                    echo  $model->getLastSql();
                    echo mysql_error();exit;
                    $this->error($model->getError());
                }
            }else{
                $this->error($model->getError());
            }
        }
        $this->dlist=$model->getTree();
        //dump($this->dlist);
        $data=$model->getChildren($id);
        $data[]=$id;
        //dump($data);
        $this->children=$data;
        $this->vo=$model->find($id);
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $pmodel=D('Privilege');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$pmodel->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Privilege/lis'),1);
            exit;
        }else {
            $this->error($pmodel->getError(),'',1);
        }
        $this->display();
    }
	public function editSortnum(){
		$id=I('post.id');
	    $num=I('post.num');
		$url=I('post.url');
		$model=M('Privilege');
		$model->where(array('id'=>$id))->save(array(
		    'sort_num'=>$num,
			'pri_url'=>$url,
		));
		echo json_encode(array(
		    'id'=>$id,
			'num'=>$num,
			'url'=>$url,
		));die;
	}

}