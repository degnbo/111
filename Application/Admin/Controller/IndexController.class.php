<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $id=session('admin_id');
        $model=M('Login');
        //echo date("Y-m-d",1468249856);die;
        //echo time()-3600*24+456;die;
        //echo strtotime(date('Y-m-d'));die;
        //$condition[date_format()]
        //date_format(now(),'%Y-%m-%d')等价于to_days(now())；
        $sql="select count(*) zs from
        __PREFIX__login where from_unixtime(login_time,'%Y-%m-%d')=
        date_format(now(),'%Y-%m-%d')";
        $today=$model->query($sql);
        //echo $model->getLastSql();die;
        $sql2="select count(*) zs from
        __PREFIX__login where
        unix_timestamp(from_unixtime(login_time,'%Y-%m-%d'))=
        unix_timestamp(date_format(now(),'%Y-%m-%d'))-3600*24";
        $yesterday=$model->query($sql2);
        //echo $model->getLastSql();die;
        //dump($today);die;
        $this->total=$model->count();
        //dump($this->total);die;
        $this->today=$today[0]['zs'];
        $this->yesterday=$yesterday[0]['zs'];
        //$li=$model->getNav($id);
        //dump($li[0]);
        $this->display();
    }
    public function clearCache(){
        deldir('./Application/Runtime/Cache');
        $this->success('清除成功',U('Index/index'),1);
    }
	public function xgtx(){
		$id=session('admin_id');
		//$logo=$_FILES;
		$config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'ht/',
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
                //$smlog=$info['myfile']['savepath'].'sm_'.$info['myfile']['savename'];
                //$image=new \Think\Image();
                //$image->open('./Public/Uploads/'.$log);
                //$image->thumb(64,64,6)->save('./Public/Uploads/'.$smlog);
				$model=M("Admin");
				$list=$model->where(array('id'=>$id))->getField('logo');
				//echo $list;exit;
				if($list['logo']!='ht_touxiang.jpg'){
					//$mylog=str_replace('sm_','',$list);
					//unlink('./Public/Uploads/'.$mylog);
					//unlink('./Public/Uploads/'.$list);
				}				
				$model->where(array('id'=>$id))->save(array(
				     'logo'=>$log,
				));
            }
        }
		 $this->success('修改成功',U('Index/index'),1);
		//dump($logo);
	}
}