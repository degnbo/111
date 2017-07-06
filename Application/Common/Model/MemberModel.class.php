<?php
namespace Common\Model;
use Think\Model;

class MemberModel extends Model{
    protected $insertFields='phone,password,addtime,total_price,cpassword,type,face,level,ck_code,zk,checkcode,is_re,level_id,xxdz,jtdz,email,name,logo,thumb_logo,width,height,sex,csrq,id_num';
    protected $updateFields='id,phone,password,addtime,total_price,cpassword,type,face,level,ck_code,zk,checkcode,is_re,level_id,xxdz,jtdz,email,name,logo,thumb_logo,width,height,sex,csrq,id_num';
   /* public $_grzl_validate=array(
        array('sex','require','请选择性别!',1),
        array('idnum','require','身份证号不能为空!',1),
        array('gaokaotime','require','选择参加高考年份!',1),
        array('sheng','require','填写省份!',1),
        array('shi','require','填写市!',1),
        array('qu','require','填写区!',1),
        array('xxadress','require','填写详细地址!',1),
        array('qq','require','填写QQ号!',1),
        array('email','require','填写邮箱!',1),
        //array('kdadress','require','填写接受快递地址!',1,'callback'),
        array('password','18,18','身份证号为18位!',1,'length'),
        //array('checkcode','check_code','验证码错误!',1,'callback'),
    );*/
    public function search($pageSize=20){
        /************************************* 翻页 ****************************************/
        $dj=I('get.dj');
        $where['is_show']=array('eq',1);
        if($dj!=''){
            $where['level']=array('eq',$dj);
        }
        $goodsName = I('get.keyword');
        if($goodsName!=''){
            $data['email'] = array('like', "%$goodsName%");
            $data['name'] = array('like', "%$goodsName%");
            $data['phone'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        $onsale = I('get.type');
        if($onsale!=''&& $onsale!='all'){
            if($onsale=='teacher'){
                $where['type']=array('eq', '2');
            }
            if($onsale=='student'){
                $where['type']=array('eq', '1');
            }
        }
        $orderby = 'id';  // 默认排序的字段
        $orderway = 'desc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc')
            $orderway = 'asc';
        $count = $this->where($where)->count();
        //echo $this->getLastSql();
        //echo $count;exit;
        if(!$count){
            $data['page']='无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        }else{
            $page = new \Think\Page($count, $pageSize);
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            //join('left join beidou_member_level b on a.level_id=b.id')->
            /************************************** 取数据 ******************************************/
            $data['data'] = $this->alias('a')->field('a.*')->

            where($where)->order("$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }
    public function login(){
        header('content-type:text/html;charset=uft-8');
        //dump(I('cookie.'));
        $data=I('post.');//exit;
        if($list=$this->checkUser($data['phone'])){
            //dump($list);exit;
            if(md5($data['password'])==$list['password']){
                if($data['is_re']){
                    //$_COOKIE['home_user']=$data['phone'];
                    $_COOKIE['home_pw']=$data['password'];
                }
                //$_COOKIE['home_user']=$data['phone'];
                session('home_user',$list['phone']);
                session('home_user',$list['id']);
                return true;
            }else{
                $this->error='密码错误';
                return false;
            }
        }else{
            $this->error='用户名不存在';
            return false;
        }
    }
    //找回用户密码
    public function zhma($phone,$pw){
        $data=$this->alias('a')->field('a.id')->field('a.id,a.phone,a.password')
            ->join('left join beidou_means b on a.id=b.member_id')
            ->where(array(
                'a.phone'=>$phone,
                'b.email'=>$phone,
                'b.qq'=>$phone,
                '_logic'=>'or'
            ))->find();
        $this->where('id='.$data['id'])->setField('password',md5($pw));
        //echo $this->getError();
        //echo $this->getLastSql();
    }
    public function checkUser($phone){
        $data=$this->alias('a')->field('a.id,a.phone,a.password,a.is_re')
            ->join('left join beidou_means b on a.id=b.member_id')
            ->where(array(
                'a.phone'=>$phone,
                'b.email'=>$phone,
                'b.qq'=>$phone,
                '_logic'=>'or'
            ))->find();
        //$data='18233305970';
        return $data;
    }
    public function check_code($code){
        $verify=new \Think\Verify();
        $re=$verify->check($code);
        return $re;
    }
    protected function _before_insert(&$data,$option){
        //dump($data);exit;
        $data['addtime']=time();
        if(!$data['type']){
            $data['type']='1';
        }
        $data['is_re']='1';
        $data['password'] = md5($data['password']);
        $data['face']='touxiang.jpg';
        $data['total_price']=0;
        $data['ck_code']=0;
        //$data['zk']=0.0;
        //$data['is_re']='0';
        //dump($data);exit;
    }
    protected function _before_update(&$data,$option){
        //dump($data);exit;
        $data['total_price']=$data['total_price'];
        if($data{'password'}){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }

        //echo $level;exit;
        //dump($data);exit;
        //$id=$option['where']['id'];
    }
    protected function _before_delete($option){
        //dump($optio);
    }
}

?>