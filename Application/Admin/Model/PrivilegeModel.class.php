<?php
namespace Admin\Model;
use Think\Model;

class PrivilegeModel extends Model{
    protected $insertFields='pri_name,pid,pri_url,sort_num';
    protected $updateFields='id,pri_name,pid,pri_url,sort_num';
    public $_login_validate=array(
       /* array('phone','require','用户名不能为空!',1),
        array('password','require','密码不能为空!',1),
        array('checkcode','require','验证码不能为空!',1),
        //array('phone','checkUser','用户名错误!',1,'callback'),
        array('password','6,18','密码为6-18位!',1,'length'),
        array('checkcode','check_code','验证码错误!',1,'callback'),*/
    );
    protected function _before_insert(&$data,$option){
        //dump($data);exit;
    }
    protected function _before_update(&$data,$option){
        //dump(I('post.'));exit;

    }
    protected function _before_delete($option){
        //dump($option);exit;
        $rpmodel=M('RolePri');
        $rpmodel->where(array('pri_id'=>$option['where']['id']))->delete();
    }
    public function getTree(){
        $data=$this->order('sort_num asc')->select();
        return $this->resort($data);
    }
    public function resort($data,$pid=0,$level=0,$clear=true){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach($data as $key=>$val){
            if($val['pid']==$pid){
                $val['level']=$level;
                $arr[]=$val;
                $this->resort($data,$val['id'],$level+1,false);
            }
        }
        return $arr;

    }
    public function getChildren($id){
        $data=$this->field('id,pid')->select();
        //return $data;
        $list=$this->myChildren($data,$id);
        //这里这个true与false表示清空刚才那个
        return $list;
    }
    private function myChildren($data,$id,$clear=true)
    {
        static $arr =array();
        if ($clear) {
            $arr =array();
        }
        foreach ($data as $val) {
            if ($val['pid'] == $id) {
                $arr[] = $val['id'];
                $this->myChildren($data, $val['id'], false);
            }
        }
        return $arr;
    }



}

?>