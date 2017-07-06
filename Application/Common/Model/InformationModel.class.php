<?php
namespace Common\Model;
use Think\Model;
class InformationModel extends Model{
    protected $insertFields='title,des,content,cate_id,type_id,ptime,addtime,sort_num,is_show,sm_logo,mid_logo';
    protected $updateFields='id,title,des,content,cate_id,type_id,ptime,addtime,sort_num,is_show,sm_logo,mid_logo';
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
    protected function _before_insert(&$data,$option){
        //dump($data);
        //dump(I('post.'));exit;
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'information/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        //exit;
        if($_FILES['myfile']['error']==0){
            $info=$upload->upload();
            if(!$info) {
                $this->error=$upload->getError();
                return false;
            }else{
                //dump($info);exit;
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $smlog=$info['myfile']['savepath'].'sm_'.$info['myfile']['savename'];
                $midlog=$info['myfile']['savepath'].'mid_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(168,168)->save('./Public/Uploads/'.$smlog);
                $image->thumb(500,500)->save('./Public/Uploads/'.$midlog);
                $data['sm_logo']=$smlog;
                $data['mid_logo']=$midlog;
            }
        }
        $data['addtime']=time();
        $data['ptime']=time();

        //dump($data);exit;
    }
    public function _before_update(&$data,$option){
        $id=$option['where']['id'];
        //dump($id);exit;
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'information/',
            'rootPath'=>'./Public/Uploads/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile']['error']==0){
            $info=$upload->upload();
            if($info) {
                //dump($info);exit;
                $log=$info['myfile']['savepath'].$info['myfile']['savename'];
                $smlog=$info['myfile']['savepath'].'sm_'.$info['myfile']['savename'];
                $midlog=$info['myfile']['savepath'].'mid_'.$info['myfile']['savename'];
                $image=new \Think\Image();
                $image->open('./Public/Uploads/'.$log);
                $image->thumb(168,168)->save('./Public/Uploads/'.$smlog);
                $image->thumb(500,500)->save('./Public/Uploads/'.$midlog);
                $data['sm_logo']=$smlog;
                $data['mid_logo']=$midlog;
                $log1=$this->field('sm_logo,mid_logo')->where('id='.$id)->find();
                $mylog=str_replace('sm_','',$log1['sm_logo']);
                unlink('./Public/Uploads/'.$mylog);
                unlink('./Public/Uploads/'.$log1['mid_logo']);
                unlink('./Public/Uploads/'.$log1['sm_logo']);
            }
        }
       /* echo $tid;
        exit;*/
    }
    public function _after_insert($data,$option){
        //dump($data);

    }
    public function _before_delete($option){
        //dump($option);exit;
        $id=$option['where']['id'];
        $log1=$this->field('sm_logo,mid_logo')->where('id='.$id)->find();
        $mylog=str_replace('sm_','',$log1['sm_logo']);
        unlink('./Public/Uploads/'.$mylog);
        unlink('./Public/Uploads/'.$log1['mid_logo']);
        unlink('./Public/Uploads/'.$log1['sm_logo']);
    }
    public function getInfo($cid,$limit=40){
        $list=$this->alias('a')->field('a.*')
            ->join('left join beidou_category b on b.id=a.cate_id')
            ->where(array(
                'a.cate_id'=>$cid,
                'a.is_show'=>'1'
            ))->limit($limit)->select();
        return $list;
    }
    public function search($pageSize=20){
        $keword = I('get.keyword');
        if($keword){
            $data['title'] = array('like', "%$keword%");
            $data['des'] = array('like', "%$keword%");
            $data['content'] = array('like', "%$keword%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        //
        $onshow = I('get.onshow');
        if($onshow!=''&& $onshow!='all'){
            if($onshow=='1'){
                $where['a.is_show']='1';
            }
            if($onshow=='2'){
                $where['a.is_show']='0';
            }
        }
        $orderby = 'a.id';  // 默认排序的字段
        $orderway = 'asc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc'){
            $orderway = 'asc';
        }
        if($odby=='id_desc'){
            $orderway = 'desc';
        }
        $count = $this->alias('a')->where($where)->count();
        /*echo $count;exit;*/
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
            /************************************** 取数据 ******************************************/
            $data['data'] = $this->
            alias('a')->field("a.*,b.cate_name,c.typename type_name")
                ->join('left join beidou_category b on b.id=a.cate_id')
                ->join('left join beidou_type c on c.id=a.type_id')->
            where($where)->order("sort_num asc,$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->select();
        }
        return $data;
        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }

}

?>