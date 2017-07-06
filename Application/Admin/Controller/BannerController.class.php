<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class BannerController extends Controller {
	public function delete(){
        $id=I('get.id');
        $model=M('Banner');
        $logo=$model->find($id);
        $result=$model->delete($id);
		if($result!==false){
			unlink('./Public/Uploads/'.$logo['pic_url']);
			$this->success('删除成功',U('Banner/lis'),1);die;
		}else{
			$this->error('删除失败');
		}
    }
    public function lis(){
		$bmodel=M('Banner');
		$list=$bmodel->select();

		//dump($list);die;
		$this->list=$list;
        $this->display();
        //http://www.yemafinancial.com<?php echo jq("__SELF__")   /hd/xmt.htlml
    }
	public function add(){
		if(IS_POST){
			$model=M('Banner');
			$data=I('post.');
			$info=$this->pic_upload();
			if($info){
				$data['pic_url']=$info['myfile']['savepath'].$info['myfile']['savename'];
			}
			$data['addtime']=date('Y-m-d H:i:s',time());
			//dump($info);
			//dump($data);die;
			if($lastid=$model->add($data)){
				$this->success('添加成功',U('Banner/lis'),1);die;
			}else{
				$this->error('添加失败');
			}
		}
        $this->display();
        //http://www.yemafinancial.com<?php echo jq("__SELF__")   /hd/                                                                                                                                xmt.htlml
    }
	public function edit(){
		$model=M('Banner');
		$data=I('post.');
		$id=I('get.id');
		if(IS_POST){
			$data=I('post.');
			$data['mtime']=date('Y-m-d H:i:s',time());
			$info=$this->pic_upload();
			if($info){
				$data['pic_url']=$info['myfile']['savepath'].$info['myfile']['savename'];
			}
			if($model->where(array('id'=>$id))->save($data)!==false){
				$this->success('修改成功',U('Banner/lis'),1);
				exit;
			}else{
				$this->error($model->getError());
			}
		}
		$list=$model->find($id);
		$this->list=$list;
		$this->display();
	}
	protected function pic_upload(){
		$config=array(
				'maxSize'    =>    2*1024*1024,
				'savePath'   =>'banner/',
				'rootPath'=>'./Public/Uploads/',
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    true,
				'subName'    =>    array('date','Ymd')
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		if($_FILES['myfile1']['error']==0) {
			$info = $upload->upload();
			if ($info) {
				return $info;
			}
		}else {
			return false;
		}
	}


}

?>