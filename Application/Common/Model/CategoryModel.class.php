<?php
namespace Common\Model;
use Think\Model;
class CategoryModel extends Model{
    protected $insertFields='cate_name,ptime,is_wl,is_show,content,is_nav,pid,type_id,sort_num,cate_logo,cate_des,cate_title,link_url';
    protected $updateFields='id,cate_name,ptime,is_wl,is_show,content,is_nav,pid,type_id,sort_num,cate_logo,cate_des,cate_title,link_url';
    //protected $_validate = array(
        //第5个参数0表示表中字段存在就验证，这表中没有role_id顾不能用0
        //array('cat', 'myfun', '角色必选', 1,'callback',3),
        //array('username', 'require', '用户名不能为空', 1,'regex',3),
       /* array('password', 'require', '密码不能为空', 1,'regex',1),
        array('username', '1,30', '用户名最长不能超过 30 个字符！', 1, 'length', 3),
        array('password', '1,32', '密码最长不能超过 32 个字符！', 1, 'length', 1),
        array('password', '1,32', '密码最长不能超过 32 个字符！', 2, 'length', 2),
        array('cpw', 'password', '两次输入的密码不一致', 0,'confirm',3),
        array('username', '', '用户名不能重复', 1,'unique',3),
        array('is_deny', '是,否', '是否禁用的值必须是是或否',0,'in',3),*/
        //array('password', 'require', '密码不能为空', 1),
        //array('goods_name','require','商品名称不能为空!',1),
    //);
    public function getTree(){
        //$data=$this->where(array('pid'=>0))->order('sort_num asc')->select();
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
    public function getParent($id,$num='m'){
        static $arr=array();
        $list=$this->where(array('id'=>$id))->find();;
        $data=$this->select();
        foreach($data as $k=>$v){
            if($v['id']==$list['pid']){
                $arr[]=$v;
            }
        }
        if($num=='m'){
            $arr[]=$list;
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
    protected function _before_insert(&$data,$option){
        //dump($data);
        //dump(I('post.'));exit;
        $data['ptime']=time();
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'category/',
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
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $smlog=$info['myfile']['savepath'].'sm_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                //$image->thumb(260,166)->save('./Public/Uploads/'.$smlog);
                $data['cate_logo']=$log;
            }
        }
        //dump($data);exit;
    }
    public function _before_update(&$data,$option){
        $tid=I('post.type_id');
       /* echo $tid;
        exit;*/
        $tid=I('post.type_id');
        $id=$option['where']['id'];
        $tcmodel=M('TypeCate');
        $tcmodel->where(array(
            'cate_id'=>$id,
        ))->delete();
        if($tid){
            $tcmodel->add(array(
                'cate_id'=>$id,
                'type_id'=>$tid,
            ));
        }
        if(!$data['ptime']){
            $data['ptime']=time();
        }
        if(!$data['is_wl']){
            $data['is_wl']=1;
        }
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'category/',
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
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $smlog=$info['myfile']['savepath'].'sm_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                //$image->thumb(260,166)->save('./Public/Uploads/'.$smlog);
                $data['cate_logo']=$log;
                $log1=$this->field('cate_logo')->where('id='.$id)->find();
                if($log1){
                    //$mylog=str_replace('sm_','',$log1['cate_logo']);
                    //unlink('./Public/Uploads/'.$mylog);
                    unlink('./Public/Uploads/'.$log1['cate_logo']);
                }
            }
        }
    }
    public function _after_insert($data,$option){
        //dump($data);
        $tcmodel=M('TypeCate');
        if(I('post.type_id')){
            $tcmodel->add(array(
                'type_id'=>I('post.type_id'),
                'cate_id'=>$data['id'],
            ));
        }
    }
    public function _before_delete($option){
        //dump($option);exit;
        $id=$option['where']['id'];
        $log1=$this->field('cate_logo')->where('id='.$id)->find();
        if($log1){
            $mylog=str_replace('sm_','',$log1['cate_logo']);
            unlink('./Public/Uploads/'.$mylog);
            unlink('./Public/Uploads/'.$log1['cate_logo']);
        }
        if(is_array($id))
        {
            $this->error = '不支持批量删除';
            return FALSE;
            //多个值删除用下面这种
            //$this->where("id in(".$id.")")->delete();
        }
        $cid=$this->getChildren($id);
        $cid=implode(",",$cid);
        //不能用delete会造成死循环
        if($cid){
            $this->execute("delete from beidou_category where id in($cid)");
        }
        $tcmodel=M('TypeCate');
        $tcmodel->where(array(
            'cate_id'=>$id,
        ))->delete();
    }
    public function getOneCate($cid,$num=5){
        $arr=array();
        $data=$this->select();
        $gmodel=D('Goods');
        $imodel=D('Information');
        //dump($data);
        foreach($data as $k=>$v){
            if($v['pid']==$cid){
                $glist=$gmodel->getGoodsCate($v['id'],5);
                $info=$imodel->getInfo($v['id'],12);
                $v['gdata']=$glist;
                $v['info']=$info;
                $data[$k]=$v;
                $arr[]=$v;
                if(count($arr)>=$num){
                    break;
                }
            }
        }
        return $arr;
    }
    public function getAllCate(){
        $arr=array();
        $data=$this->select();
        foreach($data as $key=>$val){
            if($val['pid']==0){
                foreach($data as $k=>$v){
                    if($v['pid']==$val['id'])
                        $val['children'][]=$v;
                }
                $arr[]=$val;
            }
        }
        return $arr;
    }
    public function getType($limit=5){
        $tmodel=M('Type');
        $list=$tmodel->where(array(
            'is_show'=>'1'))->limit($limit)->select();
        return $list;
    }
    //获取每一个页面的随机轮播图
    //这个轮播图是随机生成的
    public function getSjlist($cid,$limit=10){
        $model=M('Goods');
        $childid=$this->getChildren($cid);
        $childid[]=$cid;
        $arr=array();
        $list=$model->alias('a')->field('a.sm_logo,a.goods_name,a.dd,a.id,a.addtime,a.now_price,a.old_price')
            ->join('left join beidou_goods_cate b on b.goods_id=a.id')
            ->where(array(
                'b.cate_id'=>array('in',$childid),
                'a.is_on_sale'=>'1',
            ))->select();
        if(count($list)<$limit){
            $limit=count($list);
        }
        $keys=array_rand($list,$limit);
        foreach($keys as $v){
            $arr[]=$list[$v];
        }
        return $arr;
    }

}

?>