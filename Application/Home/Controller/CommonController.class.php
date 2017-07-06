<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        $m=MODULE_NAME;
        //echo $m;die;
        if($m!='Home'&& $m!='Admin'){
            $this->redirect('Index/lis');
        }
    }
    public function _empty(){
        //空方法跳转
        $this->redirect('Index/index');
    }
    public function fy($id,$model_name,$pagesize=10){
        $model=M($model_name);
        $count = $model->where(array('cate_id'=>$id))->count();
        /*echo $this->getLastSql();
        echo $count;exit;*/
        if(!$count){
            $data['page']='无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        }else{
            import('Home.Page.Page');
            $page = new \Page($count, $pagesize);
            $page->setConfig('%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
            // 配置翻页的样式
            $page->rollPage=4;
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $model->
            where(array('cate_id'=>$id,'is_show'=>'1'))->order("sort_num asc")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
    }
    public function sheng(){
        $pmodel = M('Province');
        if (!S('plist')) {
            $plist = $pmodel->select();
            foreach($plist as $key=>$val){
                $va=str_replace('省','',$val["Pname"]);
                $va=str_replace('市','',$va);
                $va=str_replace('特别行政区','',$va);
                $va=str_replace('壮族自治区','',$va);
                $va=str_replace('维吾尔族自治区','',$va);
                $va=str_replace('回族自治区','',$va);
                $va=str_replace('自治区','',$va);
                $val['Pname']=$va;
                $plist[$key]=$val;
            }
            //dump($plist);exit;
            S('plist', $plist);
        }
        return S('plist');
    }
    public function ajax_checkphone(){
        $phone=I('post.param');
		$pmodel=M('Peizhi');
		$list=$pmodel->find();
		$huodong=$list['active_name'];
        $model=M('Active');
        $count=$model->where(array('phone'=>$phone,'active_name'=>$huodong))->count();
        if($count){
            echo '{"info":"该手机号已经报名了!","status":"n"}';exit;
        }else{
            echo '{"status":"y"}';exit;
        }
    }
	public function ajax_checkphone1(){
        $phone=I('post.param');
		$pmodel=M('Peizhi');
		$list=$pmodel->find();
		$huodong=$list['active_name'];
        $model=M('Active');
        $count=$model->where(array('phone'=>$phone))->count();
        if($count){
            echo '{"info":"该手机号已经报名了!","status":"n"}';exit;
        }else{
            echo '{"status":"y"}';exit;
        }
    }
    public function active(){
        $data=I('post.');
		$pezmodel=M('Peizhi');
		$pezlist=$pezmodel->find();
		$huodong=$pezlist['active_name'];
        if(!$data['name']){
            echo '{"info":"请输入姓名!","status":"n"}';exit;
        }
        if(!$data['phone']){
            echo '{"info":"请输入手机号!","status":"n"}';exit;
        }
        $model=M('Active');
        $sl=$model->where(array('active_name'=>$data['active_name']))->count();
        $data['addtime']=time();
		$data['active_name']=$huodong;
        if($model->add($data)){
            $sl=$sl+1;
            //echo '{"info":"报名成功","status":"y"}';exit;
            echo '{"info":"报名成功","status":"y","sl":"'.$sl.'"}';exit;
            //$this->success('表名成功',U(''));
        }else{
            echo '{"info":"报名失败","status":"n"}';exit;
            //$this->success('表明失败','',1);
        }
    }
	public function active1(){
        $data=I('post.');
		$pezmodel=M('Peizhi');
		$pezlist=$pezmodel->find();
		$huodong=$pezlist['active_name'];
        if(!$data['name']){
            echo '{"info":"请输入姓名!","status":"n"}';exit;
        }
        if(!$data['phone']){
            echo '{"info":"请输入手机号!","status":"n"}';exit;
        }
        $model=M('Active');
        $sl=$model->count();
        $data['addtime']=time();
		$data['active_name']=$huodong;
        if($model->add($data)){
            $sl=$sl+1;
            //echo '{"info":"报名成功","status":"y"}';exit;
            echo '{"info":"报名成功","status":"y","sl":"'.$sl.'"}';exit;
            //$this->success('表名成功',U(''));
        }else{
            echo '{"info":"报名失败","status":"n"}';exit;
            //$this->success('表明失败','',1);
        }
    }
	public function getsl(){
        //$zhi=I('post.zi');
		$pmodel=M('Peizhi');
		$list=$pmodel->find();
		$huodong=$list['active_name'];
        $model=M('Active');
        $data=$model->where(array('active_name'=>$huodong))->select();
		$sl=count($data);
		$aname=$huodong;
        echo json_encode(array(
		    'sl'=>$sl,
			'aname'=>$aname,
		));die;
    }
	public function gets2(){
        //$zhi=I('post.zi');
		$pmodel=M('Peizhi');
		$list=$pmodel->find();
		$huodong=$list['active_name'];
        $model=M('Active');
        $data=$model->count();
		$sl=count($data);
		$aname=$huodong;
        echo json_encode(array(
		    'sl'=>$sl,
			'aname'=>$aname,
		));die;
    }


}