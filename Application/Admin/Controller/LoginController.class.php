<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function _initialize(){
        header("Content-type: text/html; charset=utf-8");
        $msg=array('model'=>CONTROLLER_NAME,'name'=>session('admin_us'),'action'=>ACTION_NAME);
        tp_log($msg);
    }
    public function index(){
        $this->display();
    }
    public function login(){
        $model=D('Admin');
        $data=I('post.');
        if(IS_AJAX){
            if($model->create()) {
				$pw=$model->password;
				//echo $pw;die;
                if($model->login($data['username'],$pw)){
                    //$this->success('登录成功', U('Index/index'), 1);
                    $lmodel=M('Login');
                    $lmodel->add(array(
                        'admin_id'=>session('admin_id'),
                        'login_time'=>time(),
                    ));
                    echo json_encode(array('status'=>'y','info'=>'登录成功！'));exit;
                }else{
                    echo json_encode(array('info'=>$model->getError(),'status'=>'n'));die;
                }
            }else{
                echo json_encode(array('info'=>$model->getError(),'status'=>'n'));die;
            }
        }
        $this->display();
    }
    public function verify(){
        $config =array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'fontttf'=>    '1.ttf',
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }

    public function logout()
    {
        session('admin_us',null);
        session('admin_id',null);
        $this->success('等待中...', U('Login/login'), 1);
        exit;
    }
    public function checkUser(){
        $name=$_POST['param'];
        $model=D('Admin');
        $data=$model->where(array(
            'username'=>$name,
        ))->find();
        if($data){
            echo json_encode(array('status'=>'y'));
        }else{
            echo json_encode(array('status'=>'n','info'=>'你输入的用户名不存在'));
        }
    }
    public function checkVerify(){
        $yzm=$_POST['param'];
        $verify=new \Think\Verify();
        if(!$verify->check($yzm)){
            echo json_encode(array('status'=>'n','info'=>'验证码错误！'));
        }else{
            echo json_encode(array('status'=>'y'));
        }
    }
}