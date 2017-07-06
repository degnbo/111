<?php
namespace Common\Model;
use Think\Model;

class MeansModel extends Model{
    protected $insertFields='name,gk_is_show,sex,member_id,idnum,view_num,major,member_des,des,member_id';
    protected $updateFields='id,name,gk_is_show,sex,member_id,idnum,view_num,major,member_des,des,member_id';
    public $_grzl_validate=array(
        /*array('name','require','请填写真实姓名!',1),
        array('sex','require','请选择性别!',1),
        array('idnum','require','身份证号不能为空!',1),
        array('idnum','number','身份证格式错误!',1),
        array('idnum','18,18','身份证号为18位!',1,'length'),
        array('gaokaotime','require','选择参加高考年份!',1),
        array('sheng','require','填写省份!',1,'regex',1),
        array('shi','require','填写市!',1,'regex',1),
        array('qu','require','填写区!',1,'regex',1),
       /* array('sheng','require','填写省份!',2,'regex',2),
        array('shi','require','填写市!',2,'regex',2),
        array('qu','require','填写区!',2,'regex',2),
        array('xxadress','require','填写详细地址!',1),
        array('xxadress','1,50','50字以内!',1,'length'),
        array('school','require','填写学校!',1),
        array('school','1,50','50字以内!',1,'length'),
        //array('kdadress','require','填写接受快递地址!',1),
        //array('kdadress','require','填写接受快递地址!',1,'callback'),
        array('kdadress','1,50','50字以内!',2,'length'),
        array('qq','require','填写QQ号!',1),
        array('qq','number','qq格式错误!',1),
        array('qq','','qq号已存在!',1,'unique',1),
        array('qq','checkqq','qq号已存在!',1,'callback',2),
        array('email','require','填写邮箱!',1),
        array('email','email','填写邮箱格式!',1),
        array('email','','邮箱已存在!',1,'unique',1),
        array('email','checkemail','邮箱已存在!',1,'callback',2),*/
        //array('checkcode','check_code','验证码错误!',1,'callback'),
    );
    public function checkqq($qq){
        $id=session('home_id');
        $data=$this->where(array(
            'member_id'=>array('neq',$id),
            'qq'=>$qq,
        ))->find();
        if($data){
            return false;
        }else{
            return true;
        }
    }
    public function checkemail($email){
        $id=session('home_id');
        $data=$this->where(array(
            'member_id'=>array('neq',$id),
            'email'=>$email,
        ))->find();
        if($data){
            return false;
        }else{
            return true;
        }
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
        if(!$data['gk_is_show']){
            $data['gk_is_show']='1';
        }
        if(!$data['gaokaotime']){
            $data['gaokaotime']=date("Y",time());
        }
        //dump($data);exit;
        $pmodel=M('Province');
        $plist=$pmodel->where(array('Pcode'=>I('post.sheng')))->find();
        $data['sheng']=$plist['Pname'];
        $pmodel=M('City');
        $plist=$pmodel->where(array('Ccode'=>I('post.shi')))->find();
        $data['shi']=$plist['Cname'];
        $pmodel=M('Areacounty');
        $plist=$pmodel->where(array('Acode'=>I('post.qu')))->find();
        $data['qu']=$plist['Aname'];
        $membermodel=M('Member');
        $membermodel->where(array('id'=>$data['member_id']))->setField('name',$data['name']);
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'face/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['fileField']['error']==0){
            $info=$upload->upload();
            if($info) {
                //dump($info);exit;
                $log=$info['fileField']['savepath'].$info['fileField']['savename'];
                $midlog=$info['fileField']['savepath'].'thumb_'.$info['fileField']['savename'];
                $sj_midlog=$info['fileField']['savepath'].'thumb_sj'.$info['fileField']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(161,160)->save('./Public/Uploads/'.$midlog);
                $image->thumb(201,200)->save('./Public/Uploads/'.$sj_midlog);
                $membermodel=M('Member');
                $membermodel->where(array('id'=>$data['member_id']))->
                save(array(
                        'face'=>$midlog,
                        'sj_face'=>$sj_midlog,
                    ));
            }
        }
        //dump($data);exit;
    }
    protected function _before_update(&$data,$option){
        //dump($option);exit;
        //dump(I('post.'));exit;
        //dump($_FILES);exit;
        //dump($data);
        //echo $data['sheng'];exit;
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'face/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $membermodel=M('Member');
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['fileField']['error']==0){
            $info=$upload->upload();
            if($info) {
                //dump($info);exit;
                $log=$info['fileField']['savepath'].$info['fileField']['savename'];
                $midlog=$info['fileField']['savepath'].'thumb_'.$info['fileField']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(161,160)->save('./Public/Uploads/'.$midlog);
                $list=$membermodel->field('face')->where(array('id'=>$data['member_id']))->find();
                $tu=str_replace('thumb_','',$list['face']);
                unlink("./Public/Uploads/".$tu);
                unlink("./Public/Uploads/".$list['face']);
                $membermodel->where(array('id'=>$data['member_id']))->setField('face',$midlog);
            }
        }
        $pmodel=M('Province');
        if($data['sheng']){
            $plist=$pmodel->where(array('Pcode'=>I('post.sheng')))->find();
            $data['sheng']=$plist['Pname'];
        }else{
            unset($data['sheng']);
        }
        if( $data['shi']){
            $pmodel=M('City');
            $plist=$pmodel->where(array('Ccode'=>I('post.shi')))->find();
            $data['shi']=$plist['Cname'];
        }else{
            unset($data['shi']);
        }
        if($data['qu']){
            $pmodel=M('Areacounty');
            $plist=$pmodel->where(array('Acode'=>I('post.qu')))->find();
            $data['qu']=$plist['Aname'];
        }else{
            unset($data['qu']);
        }
    }
    protected function _before_delete($option){
        //dump($optio);
    }


}

?>