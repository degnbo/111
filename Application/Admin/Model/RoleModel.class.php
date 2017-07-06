<?php
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model{
    protected $insertFields='role_name,pri_id';
    protected $updateFields='id,role_name,pri_id';
    public $_login_validate=array(
       /* array('phone','require','用户名不能为空!',1),
        array('password','require','密码不能为空!',1),
        array('checkcode','require','验证码不能为空!',1),
        //array('phone','checkUser','用户名错误!',1,'callback'),
        array('password','6,18','密码为6-18位!',1,'length'),
        array('checkcode','check_code','验证码错误!',1,'callback'),*/
    );
    protected function _after_insert($data,$option){
        $pri_id=I('post.pri_id');
        $id=$data['id'];
        foreach($pri_id as $val){
            $rpmodel=M('RolePri');
            $rpmodel->add(array(
                'pri_id'=>$val,
                'role_id'=>$id,
            ));
        }
    }
    protected function _before_insert($data,$option){
        //$pri_id=I('post.pri_id');
        //dump($pri_id);exit;

    }
    protected function _before_update(&$data,$option){
        //dump($data);exit;
        $pri_id=I('post.pri_id');
        $id=$option['where']['id'];
        $rpmodel=M('RolePri');
        $rpmodel->where(array(
            'role_id' => $option['where']['id'],
        ))->delete();
        foreach($pri_id as $val){
            $rpmodel->add(array(
                'pri_id'=>$val,
                'role_id'=>$id,
            ));
        }
       // dump(I('post.'));exit;
    }
    protected function _before_delete($option){
        $id=$option['where']['id'];
        $rpmodel=M('Role_pri');
        $rpmodel->where(array(
            'role_id'=>$id
        ))->delete();
        $armodel=M('AdminRole');
        $armodel->where(array(
            'role_id'=>$id,
        ))->delete();
        //dump($option);exit;
    }


}

?>