<?php
namespace Common\Model;
use Think\Model;

class MeansModel extends Model{
    protected $insertFields='level,zk';
    protected $updateFields='id,level,zk';
    public $_login_validate=array(
       /* array('phone','require','用户名不能为空!',1),
        array('password','require','密码不能为空!',1),
        array('checkcode','require','验证码不能为空!',1),
        //array('phone','checkUser','用户名错误!',1,'callback'),
        array('password','6,18','密码为6-18位!',1,'length'),
        array('checkcode','check_code','验证码错误!',1,'callback'),*/
    );

    protected function _before_update(&$data,$option){
        //dump(I('post.'));exit;
        //dump($_FILES);exit;

    }
    protected function _before_delete($option){
        //dump($optio);
    }


}

?>