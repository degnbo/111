<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
        header('content-type:text/html;charset=utf-8');
        if(!session("?home_id")) {
            $this->redirect("Login/login");
            //$this->success('你还没有登录呢！',U('Login/login'),1);
            exit;
        }
    }
    public function _empty(){
        //空方法跳转
        $this->redirect('Index/index');
    }
   /* public function _empty($name){
        $this->a_name($name);
    }
    public function a_name($name){
        echo 'hello,欢迎'.$name;
    }*/

}