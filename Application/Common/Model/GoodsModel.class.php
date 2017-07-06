<?php
namespace Common\Model;
use Think\Model;
class GoodsModel extends Model{
    protected $insertFields='goods_name,wz_kx,wz_ym,wz_total,wz_yl,is_show,start_time,end_time,goods_desc,type_id,old_price,now_price,sm_logo,mid_logo,is_on_sale,sort_num,addtime,cate_id,dd';
    protected $updateFields='id,goods_name,wz_kx,wz_ym,wz_total,wz_yl,is_show,start_time,end_time,goods_desc,type_id,old_price,now_price,sm_logo,mid_logo,is_on_sale,sort_num,addtime,cate_id,dd';
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
    public function _before_update(&$data,$option){
        //dump($data);exit;
        $st=I('post.start_time');
        $et=I('post.end_time');
        if( $st)
            $data['start_time']=strtotime($st. "00:00:00");
        if($et)
            $data['end_time']=strtotime("{$et} 23:59:59");
        $id=$option['where']['id'];
        $cid=I('post.cate_id');
        //dump($id);echo $cid;exit;
        $gcmodel=M('GoodsCate');
        //修改：删除原来的再添加新的
        if($cid){
            $gcmodel->where(array('goods_id'=>$id))->delete();
            $gcmodel->add(array(
                'goods_id'=>$id,
                'cate_id'=>$cid,
            ));
        }
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'goods/',
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
                $image->thumb(500,500)->save('./Public/Uploads/'.$midlog);
                $image->thumb(168,168)->save('./Public/Uploads/'.$smlog);
                $data['sm_logo']=$smlog;
                $data['mid_logo']=$midlog;
                $log1=$this->field('sm_logo,mid_logo')->where('id='.$id)->find();
                $mylog=str_replace('sm_','',$log1['sm_logo']);
                unlink('./Public/Uploads/'.$mylog);
                unlink('./Public/Uploads/'.$log1['mid_logo']);
                unlink('./Public/Uploads/'.$log1['sm_logo']);

            }
        }
    }
    public function search($pageSize=20){
        $where ['is_show']='1';
        /*if($attr_name = I('get.attr_name'))
            $where['attr_name'] = array('like', "%$attr_name%");
        $attr_type = I('get.attr_type');
        if($attr_type != '' && $attr_type != '我')
            $where['attr_type'] = array('eq', $attr_type);
        if($type_id = I('get.type_id'))
            $where['type_id'] = array('eq', $type_id);*/
        /************************************* 翻页 ****************************************/
        $goodsName = I('get.keyword');
        if($goodsName){
            $data['goods_name'] = array('like', "%$goodsName%");
            $data['now_price'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        $onsale = I('get.onsale');
        if($onsale!=''&& $onsale=='1')
            $where['is_on_sale']=array('eq','1');
        if($onsale!=''&& $onsale=='2')
            $where['is_on_sale']=array('eq','0');
        $from = I('get.from'); // 开始价格
        $to = I('get.to');     // 最终价格
        if($from && $to)
            $where['now_price'] = array('between', array($from, $to));
        elseif ($from)
            $where['now_price'] = array('egt', $from);
        elseif ($to)
            $where['now_price'] = array('elt', $to);

        $orderby = 'id';  // 默认排序的字段
        $orderway = 'asc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc')
            $orderway = 'asc';
        $count = $this->where($where)->count();
        /*echo $this->getLastSql();
        echo $count;exit;*/
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
            where($where)->group('id')->order("sort_num asc,$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->select();
        }
            return $data;
        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }
    public function _before_insert(&$data,$option){
        $data['addtime']=time();
        $st=I('post.start_time');
        $et=I('post.end_time');
        if($st)
            $data['start_time']=strtotime($st. "00:00:00");
        if($et)
            $data['end_time']=strtotime("{$et} 23:59:59");
        //dump($data);exit;
        $config=array(
            'maxSize'    =>    2*1024*1024,
            'savePath'   =>'goods/',
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
            }
        }
    }
    public function _after_insert($data,$option){
        $cid=I('post.cate_id');
        $gcmodel=M('GoodsCate');
        $gcmodel->add(array(
            'goods_id'=>$data['id'],
            'cate_id'=>$cid));
    }
    public function _after_update($data,$option){
    }
    public function _before_delete($option){
        //dump($option);exit;
        $id=$option['where']['id'];
        $gcmodel=M('GoodsCate');
        $gcmodel->where(array(
            'goods_id'=>$id,
        ))->delete();
        $log=$this->field('sm_logo,mid_logo')->where('id='.$id)->find();
        $mylog=str_replace('sm_','',$log['sm_logo']);
        unlink('./Public/Uploads/'.$mylog);
        unlink('./Public/Uploads/'.$log['mid_logo']);
        unlink('./Public/Uploads/'.$log['sm_logo']);
    }
    //方案1
    public function checkGoods1($gid,$id){
        $omodel=D('Order');
        $list=$omodel->where(array(
            'goods_id'=>$gid,
            'member_id'=>$id,
        ))->order('addtime desc')->find();
        if($list){
            if($list['pay_status']=='1'){
                return false;
            }else{
                //有效时间
                $yxtime=C('ORDER_EXPIRE_TIME');
                if(time()-$list['addtime']>$yxtime && $list['xz_is_show']=='1'){
                    return true;
                }
                if(time()-$list['addtime']<=$yxtime){
                    return false;
                }
            }
        }else{
            return true;
        }
    }
    public function checkisKaiban($gid){
        $list=$this->where(array('id'=>$gid))->find();
        $stime=$list['start_time'];
        $etime=$list['end_time'];
        if($stime && $etime){
            if(time()>=$stime && time()<=$etime){
                return false;
            }
        }
    }
//是否购买过该商品
    public function checkGoods($gid,$id){
        $omodel=D('Order');
        $yxtime=C('ORDER_EXPIRE_TIME');
        $yp=$omodel->where(array(
            'goods_id'=>$gid,
            'member_id'=>$id,
            'pay_status'=>'1',
        ))->find();
        if($yp){
            return false;
        }else{
            $list=$omodel->where(array(
                'goods_id'=>$gid,
                'member_id'=>$id,
                'pay_status'=>'0',
                'xz_is_show'=>'1',
            ))->select();
            if($list){
                foreach($list as $k =>$v){
                    if(time()-$v['addtime']<=$yxtime){
                        //echo 2;exit;
                        return false;
                    }
                }
                return true;
            }else{
                return true;
            }
        }
    }
    public function sheng(){
        $pmodel = M('Province');
        if (!S('plist')) {
            $plist = $pmodel->select();
            foreach($plist as $key=>$val){
                $va=str_replace('省','',$val["Pname"]);
                $va=str_replace('市','',$va);
                $va=str_replace('特别行政区','',$va);
                $va=str_replace('壮族自治区','',$va);
                $va=str_replace('维吾尔族自治区','',$va);
                $va=str_replace('回族自治区','',$va);
                $va=str_replace('自治区','',$va);
                $val['Pname']=$va;
                $plist[$key]=$val;
            }
            //dump($plist);exit;
            S('plist', $plist);
        }
        return S('plist');
    }
    public function getWeizitotal($goodsId){
        $wdata=array();
        $model=M('Zuowei');
        $data=$model->select();
        $list=$this->where(array('id'=>$goodsId))->find();
        $wzarr=explode(',',$list['wz_total']);
        $ylarr=explode(',',$list['wz_yl']);
        $kxarr=explode(',',$list['wz_kx']);
        $ymarr=explode(',',$list['wz_ym']);
        $wdata['wzarr']=$wzarr;
        $wdata['ylarr']=$ylarr;
        $wdata['kxarr']=$kxarr;
        $wdata['ymarr']=$ymarr;
        $arr=array();
        foreach($data as $k=>$val){
            if($val){
                $v=explode('_',$val['hang_zwh']);
                if($v[1]){
                    $arr[$v[0]]['weizi'][]=$v[1];
                }
            }
        }
        $wdata['arr']=$arr;
        return $wdata;
    }
    //商品栏目数据
    public function getGoodsCate($cid,$limit=40){
        $list=$this->alias('a')->field('a.*,b.cate_id')
            ->join('left join beidou_goods_cate b on b.goods_id=a.id')
            ->where(array(
                'b.cate_id'=>$cid,
                'a.is_on_sale'=>'1',
            ))->limit($limit)->select();
        return $list;
    }
    public function getGoodsCateSearch($cid,$limit=40){
        $list=$this->alias('a')->field('a.*')
            ->join('left join beidou_goods_cate b on b.goods_id=a.id')
            ->where(array(
                'b.cate_id'=>$cid,
                'a.is_on_sale'=>'1',
            ))->limit($limit)->select();
        return $list;
    }
}

?>