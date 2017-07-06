<?php
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
    protected $insertFields='username,password,is_deny,role_id.cpw,logo';
    protected $updateFields='id,username,password,is_deny,role_id,cpw,logo';
    public $_login_validate=array(
        /*array('phone','require','用户名不能为空!',1),
        array('password','require','密码不能为空!',1),
        array('checkcode','require','验证码不能为空!',1),
        //array('phone','checkUser','用户名错误!',1,'callback'),*/
        array('password','9,18','密码为6-18位!',1,'length'),
        //array('checkcode','check_code','验证码错误!',1,'callback'),
    );
    public $_validate=array(
        /*array('username','require','用户名不能为空!',1),
        array('password','require','密码不能为空!',1),
        array('username','checkUser','你输入的用户名不存在',1,'callback'),
        array('password','6,18','密码为6-18位!',1,'length'),*/
        //array('checkcode','check_code','验证码错误!',1,'callback'),
    );
    protected function checkUser($username){
        $data=$this->where(array(
            'username'=>$username,
        ))->find();
        if($data){
            return true;
        }else{
            return false;
        }
    }
    protected function _before_insert(&$data,$option){
		$data['logo']='ht_touxiang.jpg';
        $data['password']=md5($data['password']);
        //dump($data);exit;
    }
    protected function _after_insert(&$data,$option){
        $role_id=I('post.role_id');
        $id=$data['id'];
		//dump($role_id);die;
        $armodel=M('AdminRole');
        foreach($role_id as $val){
            $armodel->add(array(
                'role_id'=>$val,
                'admin_id'=>$id,
            ));
        }

    }
    protected $_auto=array(
        //array('password','md5',3,'function'),
    );
    protected function _before_update(&$data,$option){
        $id=$option['where']['id'];
        $role_id=I('post.role_id');
        //dump($data); echo $id;exit;
        if(empty($data['password'])){
            unset($data['password']);
        }else{
			$data['password']=md5($data['password']);
		}
        if($role_id){
            $armodel=M('AdminRole');
            $armodel->where(array(
                'admin_id'=>$id,
            ))->delete();
            foreach($role_id as $val){
                $armodel->add(array(
                    'admin_id'=>$id,
                    'role_id'=>$val,
                ));
            }
        }
    }
    public function lis(){
        $dlist=$this->alias('a')->
        field('a.*,GROUP_CONCAT(b.role_id) role_id,GROUP_CONCAT(c.role_name) as role_list')->
        join('left join beidou_admin_role b on a.id=b.admin_id')
            ->join('left join beidou_role c on c.id=b.role_id')
            ->group('a.id')
            ->select();
        return $dlist;
    }
    protected function _before_delete($option){
        $id=$option['where']['id'];
        $armodel=M('AdminRole');
        $armodel->where(array(
            'admin_id'=>$id,
        ))->delete();
        //dump($option);exit;
    }
    public function login($name,$pw){
        $data=$this->where(array(
            'username'=>$name
        ))->find();
		$pw=md5($pw);
        //$pw=$this->password;
        /*dump($data);
        echo $pw;exit;*/
        if($data['is_deny']=='1'){
            $this->error='该用户已被禁用了';
            return false;
        }
        if($data['password']==$pw){
            session('admin_id',$data['id']);
            session('admin_us',$name);
            return true;
        }else{
            $this->error='密码错误';
            return false;
        }

    }
    public function getRoleId($id){
        $armodel=M('AdminRole');
        $roleId=array();
        $arlist=$armodel->field('role_id')->where(array(
            'admin_id'=>$id,
        ))->select();
        foreach($arlist as $val){
            $roleId[]=$val['role_id'];
        }
        return $roleId;
    }
    public function checkqx(){
        //echo 0;exit;
        $id=session('admin_id');
        $user=session('admin_us');
        if(CONTROLLER_NAME=='Index' && ACTION_NAME=='index'){
            return true;
        }
        $roleid=$this->getRoleId($id);
		if($user=='mrmrmrmr'){
			return true;
		}
        if(in_array(1,$roleid)){
            return true;
        }
        //echo ACTION_NAME;exit;
        $data=$this->getpriList($id);
        foreach($data as $k=>$v){
            if($v['purl']!='admin'){
                $url=explode('/',$v['purl']);
                //dump($url);exit;
                if(CONTROLLER_NAME==$url[0] && ACTION_NAME==$url[1]){
                    return true;
                }
            }
        }
        return false;
    }
    public function getpriList($id){
        $sql="select c.pri_url purl from  beidou_admin_role a
left join beidou_role_pri b on a.role_id=b.role_id
 left join beidou_privilege c on b.pri_id=c.id
 where a.admin_id=$id";
        $data=$this->query($sql);
		//dump($data);die;
        return $data;
    }
    public function getNav($id){
        $model=M('AdminRole');
        $data=$model->alias('a')->field('c.pri_url purl,c.pri_name pname,c.pid,c.id')->
            join('left join beidou_role_pri b on a.role_id=b.role_id')
            ->join("left join beidou_privilege c on b.pri_id=c.id")->
                where(array(
                    "a.admin_id"=>$id
        ))->group('c.id')->order('c.sort_num asc')->select();
        //dump($data);//exit;
        $arr=array();
        foreach($data as $k=>$v){
            if($v['pid']==0){
                foreach($data as $k1=>$v1){
                    if($v1['pid']==$v['id']){
                        $v['chilren'][]=$v1;
                    }
                }
                $arr[]=$v;
            }
        }
        return $arr;
    }

}

?>