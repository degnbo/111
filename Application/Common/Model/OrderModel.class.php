<?php
namespace Common\Model;
use Think\Model;
class OrderModel extends Model{
    protected $insertFields='member_id,goods_id,is_show,addtime,ordernum,zw_num,pay_addtime,shr_name,shr_address,shr_tel,post_method,pay_method,pay_status,post_status,tuikuan,tuikuan_status,total_price';
    protected $updateFields='id,member_id,is_show,goods_id,addtime,ordernum,zw_num,pay_addtime,shr_name,shr_address,shr_tel,post_method,pay_method,pay_status,post_status,tuikuan,tuikuan_status,total_price';
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

    public function _before_update($data,$option){
        $tid=I('post.type_id');
        /* echo $tid;
         exit;*/
    }
    public function _before_insert(&$data,$option){
        $data['addtime']=time();
        $data['is_show']='1';
        $data['ordernum']=build_order_no();
        $data['pay_status']='0';
        $data['post_status']='0';

        //dump($data);exit;

    }
    public function _after_insert($data,$option){
    }
    public function _before_delete($option)
    {
        //dump($option);exit;
    }
    //检测订单的有效时间
    Public function checkOrderexpire(){

    }
    //购买之后在总的座位中删除该座位
    //方案1
    public function reduceZw1($gid,$wz){
        $gmodel=M('Goods');
        $list=$gmodel->where(array('id'=>$gid))->find();
        $total_zw=explode(',',$list['wz_total']);
        $key=array_search($wz,$total_zw);
        if($key!==false){
            unset($total_zw[$key]);
        }
        $total_zw=implode(',',$total_zw);
        //return $total_zw;
        $gmodel->where(array('id'=>$gid))->save(array(
            'wz_total'=>$total_zw,
        ));
        return 1;
    }
    //方案2
    public function reduceZw($gid,$wz){
        //$this->deleteOrder($gid);
        $gmodel=M('Goods');
        $list=$gmodel->where(array('id'=>$gid))->find();
        $total_zw=explode(',',$list['wz_kx']);
        $key=array_search($wz,$total_zw);
        if($key!==false){
            unset($total_zw[$key]);
        }
        $total_zw=array_unique($total_zw);
        $total_zw=implode(',',$total_zw);
        //return $total_zw;
        //echo $list['wz_total'];
        if(!$list['wz_ym']){
            $wz_ym=$wz;
        }else {
            $wz_ym = explode(',', $list['wz_ym']);
            $wz_ym[] = $wz;
            $wz_ym=array_unique($wz_ym);
            $wz_ym=implode(',',$wz_ym);
        }
        $gmodel->where(array('id'=>$gid))->save(array(
            'wz_kx'=>$total_zw,
            'wz_ym'=>$wz_ym,
        ));
    }
    public function GetTotal($oid){
        $data=$this->field('sum(goods_price) tp,count(id) num')
            ->where(array(
            'id'=>array('in',$oid),
            ))->find();
        //echo $this->getLastSql();
        return $data;
    }
    public function ispayOrder($id,$condition){
        $where['xz_is_show']='1';
        $where['is_show']='1';
        if($id){
            $where['member_id']=$id;
        }
        if($condition=='1'){
            $where['pay_status']='1';
        }
        if($condition=='0'){
            $where['pay_status']='0';
        }
        $data=$this
            ->where($where)->select();
        foreach($data as $k=>$val){
            $va=explode('-',$val['zw_num']);
            $va="第".$va[0]."排第".$va[1]."列";
            $val['zw_num']=$va;
            $data[$k]=$val;
        }
        return $data;
    }
    public function checktime($id){
        if($id){
            $where['id']=$id;
        }
        $expiretime=C('ORDER_EXPIRE_TIME');
        $data=$this->where($where)->find();
        if(time()-$data['addtime']>$expiretime){
            //$cmodel->where('order')
            echo 'ss';
            //$this->error('该订单已失效');
        }else{
            echo 'o';
        }
    }
    //删除失效订单$one=2表示select1表示find
    public function deleteOrder2($gid){
        $et=C('ORDER_EXPIRE_TIME');
        $data=$this->where(array(
            'goods_id'=>$gid
        ))->select();
        //echo $et;
        ///有效的订单
        $yxlist=$this->field("GROUP_CONCAT(zw_num) zw")
            ->where(array(
                'goods_id'=>$gid,
                'addtime'=>array('egt',time()-$et),
            ))->group('goods_id')->find();
        //无效的订单
        $wxlist=$this->field("GROUP_CONCAT(zw_num) zw")
            ->where(array(
                'goods_id'=>$gid,
                'addtime'=>array('lt',time()-$et),
            ))->group('goods_id')->find();
        $wxzw=explode(',',$wxlist['zw']);
        $wxzw=array_unique($wxzw);
        foreach($wxzw as $k1=>$v1){
            $gmodel=M('Goods');
            $list=$gmodel->where(array(
                'id'=>$gid
            ))->find();
            $wz_ym=explode(',',$list['wz_ym']);
            $key=array_search($v1,$wz_ym);
            if($key!==false){
                unset($wz_ym[$key]);
            }
            $wz_ym=implode(',',$wz_ym);
            if($list['wz_kx']){
                $wz_kx=explode(',',$list['wz_kx']);
                $wz_kx[]=$v1;
                $wz_kx=array_unique($wz_kx);
                $wz_kx=implode(',',$wz_kx);
            }else{
                $wz_kx=$v1;
            }
            $gmodel->where(array(
                'id'=>$gid
            ))->save(array(
                'wz_kx'=>$wz_kx,
                'wz_ym'=>$wz_ym,
            ));
        }
        $yxzw=explode(',',$yxlist['zw']);
        $yxzw=array_unique($yxzw);
        foreach($yxzw as $k2=>$v2){
            $gmodel=M('Goods');
            $list1=$gmodel->where(array(
                'id'=>$gid
            ))->find();
            $wz_kx=explode(',',$list1['wz_kx']);
            $key=array_search($v2,$wz_kx);
            if($key!==false){
                unset($wz_kx[$key]);
            }
            $wz_kx=implode(',',$wz_kx);
            if($list1['wz_ym']){
                $wz_ym=explode(',',$list1['wz_ym']);
                $wz_ym[]=$v2;
                $wz_ym=array_unique($wz_ym);
                $wz_ym=implode(',',$wz_ym);
            }else{
                $wz_ym=$v2;
            }
            $gmodel->where(array(
                'id'=>$gid
            ))->save(array(
                'wz_kx'=>$wz_kx,
                'wz_ym'=>$wz_ym,
            ));
        }
    }
    public function checkexpire($oid,$gid,$val){
        $et=C('ORDER_EXPIRE_TIME');
        if(time()-$val['addtime']>$et){
            $this->where(array(
                'id'=>$oid
            ))->save(array(
                'is_show'=>'0',
            ));
            $gmodel=M('Goods');
            $list=$gmodel->where(array(
                'id'=>$gid
            ))->find();
            $wz_ym=explode(',',$list['wz_ym']);
            $key=array_search($val['zw_num'],$wz_ym);
            if($key!==false){
                unset($wz_ym[$key]);
            }
            $wz_ym=implode(',',$wz_ym);
            if($list['wz_kx']){
                $wz_kx=explode(',',$list['wz_kx']);
                $wz_kx[]=$val['zw_num'];
                $wz_kx=implode(',',$wz_kx);
            }else{
                $wz_kx=$list['wz_kx'];
            }
            $gmodel->where(array(
                'id'=>$gid
            ))->save(array(
                'wz_kx'=>$wz_kx,
                'wz_ym'=>$wz_ym,
            ));
        }
    }
    public function jiazw($data,$gid){
        $et=C('ORDER_EXPIRE_TIME');
        foreach($data as $k=>$val){
            if(time()-$val['addtime']<=$et){
                //echo time()-$val['addtime']."<br>";
                $this->where(array(
                    'id'=>$val['id'],
                ))->save(array(
                    'xz_is_show'=>'1',
                ));
                $gmodel=M('Goods');
                $list=$gmodel->where(array(
                    'id'=>$gid,
                ))->find();
                $wz_kx=explode(',',$list['wz_kx']);
                $key=array_search($val['zw_num'],$wz_kx);
                if($key!==false){
                    unset($wz_kx[$key]);
                }
                $wz_kx=array_unique($wz_kx);
                $wz_kx=implode(',',$wz_kx);
                if($list['wz_ym']){
                    $wz_ym=explode(',',$list['wz_ym']);
                    $wz_ym[]=$val['zw_num'];
                    $wz_ym=array_unique($wz_ym);
                    $wz_ym=implode(',',$wz_ym);
                }else{
                    $wz_ym=$val['zw_num'];
                }
                $gmodel->where(array(
                    'id'=>$gid
                ))->save(array(
                    'wz_kx'=>$wz_kx,
                    'wz_ym'=>$wz_ym,
                ));
            }
        }
    }
    public function ymzw($gid){
        $data=$this->where(array(
            'goods_id'=>$gid,
            'pay_status'=>'1',
        ))->select();
        foreach($data as $k=>$val){
                $gmodel=M('Goods');
                $list=$gmodel->where(array(
                    'id'=>$gid
                ))->find();
                $wz_kx=explode(',',$list['wz_kx']);
                $key=array_search($val['zw_num'],$wz_kx);
                if($key!==false){
                    unset($wz_kx[$key]);
                }
                $wz_kx=array_unique($wz_kx);
                $wz_kx=implode(',',$wz_kx);
                if($list['wz_ym']){
                    $wz_ym=explode(',',$list['wz_ym']);
                    $wz_ym[]=$val['zw_num'];
                    $wz_ym=array_unique($wz_ym);
                    $wz_ym=implode(',',$wz_ym);
                }else{
                    $wz_ym=$val['zw_num'];
                }
                $gmodel->where(array(
                    'id'=>$gid
                ))->save(array(
                    'wz_kx'=>$wz_kx,
                    'wz_ym'=>$wz_ym,
                ));
        }
    }
    public function jianzw($data,$gid){
        $et=C('ORDER_EXPIRE_TIME');
        echo $et;
        //dump($data);
        echo date("Y-m-d H:i:s",time()-$et);
        foreach($data as $k=>$val){
            echo time()-$val['addtime'];
            if(time()-$val['addtime']>$et){
                //echo time()-$val['addtime']."<br>";
                $this->where(array(
                    'id'=>$val['id'],
                ))->save(array(
                    'xz_is_show'=>'0',
                ));
                $gmodel=M('Goods');
                $list=$gmodel->where(array(
                    'id'=>$gid
                ))->find();
                $wz_ym=explode(',',$list['wz_ym']);
                $key=array_search($val['zw_num'],$wz_ym);
                if($key!==false){
                    unset($wz_ym[$key]);
                }
                $wz_ym=array_unique($wz_ym);
                $wz_ym=implode(',',$wz_ym);
                if($list['wz_kx']){
                    $wz_kx=explode(',',$list['wz_kx']);
                    $wz_kx[]=$val['zw_num'];
                    $wz_kx=array_unique($wz_kx);
                    $wz_kx=implode(',',$wz_kx);
                }else{
                    $wz_kx=$val['zw_num'];
                }
                $gmodel->where(array(
                    'id'=>$gid
                ))->save(array(
                    'wz_kx'=>$wz_kx,
                    'wz_ym'=>$wz_ym,
                ));
            }
        }
    }
    public function deleteOrder($gid){
        $data=$this->where(array(
            'goods_id'=>$gid,
            'pay_status'=>'0',
            'xz_is_show'=>'1',
        ))->select();
        //dump($data);
        $this->jianzw($data,$gid);
    }
    public function deleteOrder3($gid){
        $data=$this->where(array(
            'goods_id'=>$gid,
            'pay_status'=>'0',
            'xz_is_show'=>'1',
        ))->select();
        $this->jianzw($data,$gid);
        $this->jiazw($data,$gid);
        $this->ymzw($gid);
    }
    public function jzw($data,$gid){

    }
    public function setPaid1($orderId)
    {
        $this->where(array(
            'id' => array('eq', $orderId),
        ))->setField('pay_status', '1');
        $gmodel=M('Goods');
        $val=$this->where(array(
            'id' => array('eq', $orderId),
        ))->find();
        $list=$gmodel->where(array(
            'id'=>$val['goods_id'],
        ))->find();
        $wz_dzf=explode(',',$list['wz_dzf']);
        $key=array_search($val['zw_num'],$wz_dzf);
        if($key!==false){
            unset($wz_dzf[$key]);
        }
        if($list['wz_ym']){
            $wz_ym=explode(',',$list['wz_ym']);
            $wz_ym[]=$val['zw_num'];
            $wz_ym=array_unique($wz_ym);
            $wz_ym=implode(',',$wz_ym);
        }else{
            $wz_ym=$val['zw_num'];
        }
        $gmodel->where(array(
            'id'=>$val["goods_id"],
        ))->save(array(
            'wz_dzf'=>$wz_dzf,
            'wz_ym'=>$wz_ym,
        ));

    }
    public function setFinished($orderId)
    {
        $this->where(array(
            'id' => array('eq', $orderId),
        ))->setField('tuikuan', '0');
        // 根据定单ID取出总价和会员ID
        //$jf = $this->field('total_price,member_id')->find($orderId);
        // 更新会员的积分等信息
        //$this->execute("UPDATE php37_member SET jifen=jifen+{$jf['total_price']},jyz=jyz+{$jf['total_price']} WHERE id={$jf['member_id']}");
    }


}


?>