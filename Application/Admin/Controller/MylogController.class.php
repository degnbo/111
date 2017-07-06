<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class MylogController extends BaseController {
    public function lis(){
        //日志列表
        $filename="./Public/jour/";
        $list=scandir($filename);
        $arr=array();
        foreach($list as $k=>$v){
            $data=array();
            if($v=='.' || $v==".."){
                unset($list[$k]);
            }else{
                $data['name']=$v;
                $data['size']=format_bytes(filesize($filename.$v));
                $arr[]=$data;
            }
        }
        $this->list=$arr;
        $this->display();
    }
    public function lisxq(){
        $filename=I('get.id');
        import('Admin.Log.Log');
        $log=new \Log();
        $list=$log->readLog('./Public/jour/'.$filename);
        //dump($list);die;
        $this->list=$list;
        $this->display();
    }

}