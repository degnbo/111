<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class OrderController extends BaseController
{
    public function index()
    {
        $model = D('Admin');
        $id = session('admin_id');
        $li = $model->getNav($id);
        //dump($li[0]);
        $this->display();
    }

    public function lis()
    {
        $data=$this->search1();
        $this->dlist=$data['data'];
        $this->page=$data['page'];
        $this->display();
    }

    public function lis1()
    {
        $data=$this->search();
        $this->dlist=$data['data'];
        $this->page=$data['page'];
        $this->display();
    }
    public function lis2(){
        $id=I('get.id');
        //echo $id;
        $data=$this->search1(10,$id);
        $this->dlist=$data['data'];
        $this->page=$data['page'];
        $this->display();
    }
    public function lis3(){
        $id=I('get.id');
        $list=$this->search3(10,$id);
        //dump($list);
        $this->list=$list['data'];
        $this->page=$list['page'];
        $this->display();
    }
    public function search3($pageSize=2,$aid=null){
        $cmodel=M('Comment');
        $name = bian(I('get.keyword'));
        if($aid){
            $where['a.active_id']=$aid;
        }
        //echo $pageSize;
        if($name){
            $where['b.name|c.tname']=array('like', "%$name%");
        }
        $count = $cmodel->alias('a')->
        join("left join __MEMBER__ b on a.member_id=b.id")->
        join("left join __ACTIVE__ c on a.active_id=c.id")->
        where($where)->count();
        //echo $count;
        //echo $cmodel->getLastSql();//die;
        if(!$count){
            $data['page']='无查询数据!';
        }else{
            $page = new \Think\Page($count,$pageSize);
            $page->rollPage=6;
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            //dump($page);
            /************************************** 取数据 ******************************************/
            $data['data'] = $cmodel->alias('a')->field('a.*,b.name,c.tname,c.address,c.acode')->
            join("left join __MEMBER__ b on a.member_id=b.id")->
            join("left join __ACTIVE__ c on a.active_id=c.id")->
            where($where)->order("a.uptime desc")->limit($page->firstRow,$page->listRows)->select();
            //echo $cmodel->getLastSql();die;
        }
        return $data;
    }

    public function search($pageSize = 10)
    {
        $model = M('Active');
        //$where['is_show']='1';
        $keyword = I('get.keyword');
        if ($keyword) {
            $where['a.ordernum|a.tname|b.name'] = array('like', "%$keyword%");
        }
        $count = $model->alias('a')->
        field('a.addtime,a.pay_time,a.is_pay,a.ordernum,a.id,a.price,a.number,a.tname,b.name,a.acode')->
        join('left join __MEMBER__ b on a.member_id=b.id')->
        where($where)->count();
        /*echo $count;
        echo $model->getLastSql();die;*/
        if (!$count) {
            $data['page'] = '暂无查询数据!';
        } else {
            $page = new \Think\Page($count, $pageSize);
            $page->setConfig('%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END%');
            // 配置翻页的样式
            $page->setConfig('prev', "上一页");
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $model->alias('a')->
            field('a.addtime,a.pay_time,a.is_pay,a.ordernum,a.id,a.price,a.number,a.tname,b.name,a.acode')->
            join('left join __MEMBER__ b on a.member_id=b.id')->limit($page->firstRow,$page->listRows)->
                where($where)->order('a.addtime desc')->select();
        }
        return $data;
    }
    public function search1($pageSize = 10,$aid=null)
    {
        $model = M('Order');
        //$where['is_show']='1';
        if($aid){
            $where['a.active_id']=$aid;
        }
        $keyword = I('get.keyword');
        if ($keyword) {
            $where['a.ordernum|b.name|c.tname'] = array('like', "%$keyword%");
        }
        $count = $model->alias('a')->
        field('a.pay_status,a.pay_time,a.ordernum,a.id,a.active_id,a.active_price,b.name,c.tname')->
        join('left join __MEMBER__ b on a.member_id=b.id')
            ->join('left join __ACTIVE__ c on a.active_id=c.id')->
        where($where)->count();
        //echo $count;
        //echo $model->getLastSql();die;
        if (!$count) {
            $data['page'] = '暂无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        } else {
            $page = new \Think\Page($count, $pageSize);
            $page->setConfig('%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END%');
            // 配置翻页的样式
            $page->setConfig('prev', "上一页");
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $model->alias('a')->
            field('a.pay_status,a.pay_time,a.ordernum,a.id,a.active_price,b.name,c.tname')->
            join('left join __MEMBER__ b on a.member_id=b.id')
                ->join('left join __ACTIVE__ c on a.active_id=c.id')->
            limit($page->firstRow,$page->listRows)->
            where($where)->order('a.addtime desc')->select();
        }
        return $data;
    }

    public function delete()
    {
        $id = I('get.id');
        $model = M('Order');
        $model->where(array(
            'id' => $id,
        ))->save(array(
            'is_show' => '0'
        ));
        $this->success('删除成功', U('Order/lis'), 1);
    }

    public function xiangqing()
    {
        $id = I('get.id');
        $model = M('Order');
        $list = $model->where(array('id' => $id))->find();
        $da = explode('-', $list['zw_num']);
        $list['zw_num'] = '第' . $da[0] . '排第' . $da[1] . '列';
        $this->list = $list;
        $this->display();
    }

    /**
     * 发布活动详情
     */
    public function pub_detail()
    {
        $id = I('get.id');
        $model = M('Active');
        $list = $model->alias('a')->field('a.*,b.name pname')
            ->join('left join __MEMBER__ b on a.member_id=b.id')->
        where(array('a.id' => $id))->find();
        //dump($list);
        //举报消息
        $amodel=M("Report");
        //发布人举报
        $alist1=$amodel->where(array('aid'=>$id,'uid'=>$list['member_id']))->
        find();
        //已参与人数
        $omodel=M("Order");
        $anumber=$omodel->where(array('active_id'=>$id,'pay_status'=>array('in','1,2,3,5,6')))->count();
        $list['sl']=$anumber;
        $this->alist1=$alist1;
        //dump($alist1);
        //参与人举报
        $alist2=$amodel->alias('a')->field('a.content,a.addtime,b.name')
            ->join('left join __MEMBER__ b on a.uid=b.id')->
            where(array('a.aid'=>$id,'a.uid'=>array('neq',$list['member_id'])))->select();
        $this->alist2=$alist2;
        $this->list = $list;
        $this->display();
    }

    public function join_detail()
    {
        $id = I('get.id');
        $model = M('Order');
        //dump($model->find($id));die;
        $list = $model->alias('a')->field('a.*,b.number,b.price,b.number,b.start_time,b.tname,b.pic,b.address,c.name,d.id pid,d.name pname')
            ->join('left join __ACTIVE__ b on a.active_id =b.id')
            ->join('left join __MEMBER__ d on b.member_id=d.id')
            ->join('left join __MEMBER__ c on a.member_id=c.id')
            ->where(array('a.id' => $id))->find();
        //echo $model->getLastSql();
        //已参与人数
        $anumber=$model->where(array('active_id'=>$list['active_id'],'pay_status'=>array('in','1,2,3,5,6')))->count();
        $list['sl']=$anumber;
        $this->list = $list;
        //dump($list);//die;

        $amodel=M("Report");
        //发布人举报
        $alist1=$amodel->where(array('aid'=>$list['active_id'],'uid'=>$list['pid']))->
        find();
        $this->alist1=$alist1;
        //dump($alist1);
        //参与人举报
        $alist2=$amodel->alias('a')->field('a.content,a.addtime,b.name')
            ->join('left join __MEMBER__ b on a.uid=b.id')->
        where(array('a.aid'=>$list['active_id'],'a.uid'=>array('neq',$list['pid'])))->select();
        $this->alist2=$alist2;
        //dump($alist1);
        //dump($alist2);
        $this->display();
    }
    public function pub_return(){
        $id=I('get.id');
        $amodel=M("Active");
        $alist=$amodel->find($id);
        //die;
        //$this->pub_refund('active',$alist['ordernum'],1,'5');die;
        //echo json_encode($alist);die;
        if($alist['start_time']>time()){
            echo json_encode(array('msg'=>'后台处理活动开始之后的退款！','status'=>false));die;
        }else{
            //if(time()-$alist['start_time']>=24*3600){
                if($alist['is_pay']==1){
                    $price=$alist['number']*$alist['price']*100;
                    //echo $price;die;
                    $this->pub_refund('active',$alist['ordernum'],$price,'5');
                    //echo json_encode(array('msg'=>'你还未支付呢！1','status'=>false));die;
                }elseif($alist['is_pay']==0){
                    echo json_encode(array('msg'=>'你还未支付呢！','status'=>false));die;
                }elseif($alist['is_pay']==2){
                    echo json_encode(array('msg'=>'退款中！','status'=>false));die;
                }elseif($alist['is_pay']==3){
                    echo json_encode(array('msg'=>'已退款！','status'=>false));die;
                }elseif($alist['is_pay']==4){
                    echo json_encode(array('msg'=>'活动已取消了！','status'=>false));die;
                }elseif($alist['is_pay']==5){
                    echo json_encode(array('msg'=>'非签到，已经退款！','status'=>false));die;
                }elseif($alist['is_pay']==6){
                    echo json_encode(array('msg'=>'不能退款','status'=>false));die;
                }

            /*}else{
                echo json_encode(array('msg'=>'活动开始24小时之后才能退款','status'=>false));die;
            }*/
        }
    }
    public function join_return(){
        $model=M("Order");
        $id=I('get.id');
        $amodel=M("Active");
        $list=$model->find($id);
        $alist=$amodel->find($list['active_id']);
        //die;
        //$this->pub_refund('order',$list['ordernum'],$list['active_price'],'5');die;
        //echo json_encode($alist);die;
        if($alist['start_time']>time()){
            echo json_encode(array('msg'=>'后台处理活动开始之后的退款！','status'=>false));die;
        }else{
            if($alist['acode']==1){
                echo json_encode(array('msg'=>'后台发布的活动不能退押金！','status'=>false));die;
            }
            //if(time()-$alist['start_time']>=24*3600){
                if($list['pay_status']==1){
                    $price=$list['active_price']*100;
                    //echo $list['active_price'];die;
                    $this->pub_refund('order',$list['ordernum'],$price,'5');
                    //echo json_encode(array('msg'=>'你还未支付呢！1','status'=>false));die;
                }elseif($list['pay_status']==0){
                    echo json_encode(array('msg'=>'你还未支付呢！','status'=>false));die;
                }elseif($list['pay_status']==2){
                    echo json_encode(array('msg'=>'退款中！','status'=>false));die;
                }elseif($list['pay_status']==3){
                    echo json_encode(array('msg'=>'已退款！','status'=>false));die;
                }elseif($list['pay_status']==4){
                    echo json_encode(array('msg'=>'活动已取消了！','status'=>false));die;
                }elseif($list['pay_status']==5){
                    echo json_encode(array('msg'=>'非签到已退款！','status'=>false));die;
                }elseif($list['pay_status']==6){
                    echo json_encode(array('msg'=>'不能退款！','status'=>false));die;
                }
            /*}else{
                echo json_encode(array('msg'=>'活动开始24小时之后才能退款','status'=>false));die;
            }*/
        }
        //echo json_encode($list);die;
    }
    //发布活动未开始的退款
    public function pub_refund($model, $out_trade_no, $refund_fee = '1',$status='5')
    {
        import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
        //输入需退款的订单号
        if (!isset($out_trade_no) || !isset($refund_fee)) {
            $out_trade_no = " ";
            $refund_fee = "1";
        } else {
            $time_stamp = uniqid() . time();
            //商户退款单号，商户自定义，此处仅作举例
            $out_refund_no = "$out_trade_no" . "$time_stamp";
            //总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
            $total_fee = $refund_fee;
            //echo $refund_fee;
            //echo $total_fee;die;
            //使用退款接口
            $refund = new \Refund_pub();
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //sign已填,商户无需重复填写
            $refund->setParameter("out_trade_no", "$out_trade_no");//商户订单号
            $refund->setParameter("out_refund_no", "$out_refund_no");//商户退款单号
            $refund->setParameter("total_fee", "$total_fee");//总金额
            $refund->setParameter("refund_fee", "$refund_fee");//退款金额
            $refund->setParameter("op_user_id", \WxPayConf_pub::MCHID);//操作员
            //非必填参数，商户可根据实际情况选填
            //$refund->setParameter("sub_mch_id","XXXX");//子商户号
            //$refund->setParameter("device_info","XXXX");//设备号
            //$refund->setParameter("transaction_id","XXXX");//微信订单号
            //调用结果
            $refundResult = $refund->getResult();
            //echo json_encode($refundResult);die;
            if ($refundResult["return_code"] == "SUCCESS" && $refundResult["result_code"]=='SUCCESS') {
                //发布活动
                $amodel = M('Active');
                $omodel = M('Order');
                if ($model == 'active') {
                    $amodel->where(array('ordernum' => $out_trade_no))->setField('is_pay', $status);
                } elseif ($model == 'order') {
                    //$aid = $omodel->where(array('ordernum' => $out_trade_no))->getField('active_id');
                    //$ac_status = $amodel->where(array('id' => $aid))->getField('is_pay');
                    $omodel->where(array('ordernum' => $out_trade_no))->setField('pay_status', $status);
                    /*if ($ac_status == '4') {
                        $omodel->where(array('ordernum' => $out_trade_no))->setField('pay_status', '4');
                    } else {
                        $omodel->where(array('ordernum' => $out_trade_no))->setField('pay_status', '3');
                    }*/
                }
            }
            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($refundResult["result_code"] == "FAIL" || $refundResult["return_code"] == "FAIL") {
                echo json_encode(array('msg' => "通信出错：" . $refundResult['err_code_des'], 'status' => false));die;
            } else {
                echo json_encode(array('msg' => "退款成功", 'status' => true));
            }
        }
    }
    public function chaxun_order(){
        $onum=I("get.onum");
        import("Home.WxPayPubHelper.WxPayPubHelper",APP_PATH,".php");

        //退款的订单号
        if (!isset($onum))
        {
            $out_trade_no = " ";
        }else{
            $out_trade_no = $onum;

            //使用订单查询接口
            $orderQuery = new \OrderQuery_pub();
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //sign已填,商户无需重复填写
            $orderQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号
            //非必填参数，商户可根据实际情况选填
            //$orderQuery->setParameter("sub_mch_id","XXXX");//子商户号
            //$orderQuery->setParameter("transaction_id","XXXX");//微信订单号

            //获取订单查询结果
            $orderQueryResult = $orderQuery->getResult();

            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($orderQueryResult["return_code"] == "FAIL") {
                echo "通信出错：".$orderQueryResult['return_msg']."<br>";
            }
            elseif($orderQueryResult["result_code"] == "FAIL"){
                echo "错误代码：".$orderQueryResult['err_code']."<br>";
                echo "错误代码描述：".$orderQueryResult['err_code_des']."<br>";
            }
            else{
                echo "交易状态：".$orderQueryResult['trade_state']."<br>";
                echo "设备号：".$orderQueryResult['device_info']."<br>";
                echo "用户标识：".$orderQueryResult['openid']."<br>";
                echo "是否关注公众账号：".$orderQueryResult['is_subscribe']."<br>";
                echo "交易类型：".$orderQueryResult['trade_type']."<br>";
                echo "付款银行：".$orderQueryResult['bank_type']."<br>";
                echo "总金额：".($orderQueryResult['total_fee']/100)."<br>";
                echo "现金券金额：".$orderQueryResult['coupon_fee']."<br>";
                echo "货币种类：".$orderQueryResult['fee_type']."<br>";
                echo "微信支付订单号：".$orderQueryResult['transaction_id']."<br>";
                echo "商户订单号：".$orderQueryResult['out_trade_no']."<br>";
                echo "商家数据包：".$orderQueryResult['attach']."<br>";
                echo "支付完成时间：".$orderQueryResult['time_end']."<br>";
            }
        }
    }

    public function find_order(){
        $onum=I("get.onum");
        import("Home.WxPayPubHelper.WxPayPubHelper",APP_PATH,".php");
        //要查询的订单号
        if (!isset($onum))
        {
            $out_trade_no = " ";
        }else{
            $out_trade_no = $onum;

            //使用退款查询接口
            $refundQuery = new \RefundQuery_pub();
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //sign已填,商户无需重复填写
            $refundQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号
            // $refundQuery->setParameter("out_refund_no","XXXX");//商户退款单号
            // $refundQuery->setParameter("refund_id","XXXX");//微信退款单号
            // $refundQuery->setParameter("transaction_id","XXXX");//微信退款单号
            //非必填参数，商户可根据实际情况选填
            //$refundQuery->setParameter("sub_mch_id","XXXX");//子商户号
            //$refundQuery->setParameter("device_info","XXXX");//设备号

            //退款查询接口结果
            $refundQueryResult = $refundQuery->getResult();

            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($refundQueryResult["return_code"] == "FAIL") {
                //echo '未退款';
                echo "通信出错：".$refundQueryResult['return_msg']."<br>";
            }
            else{
                /*if($refundQueryResult['result_code']=="SUCCESS"){
                    echo '该订单已退款';
                }else{
                    echo $refundQueryResult['err_code_des'];
                }*/
                echo "业务结果：".$refundQueryResult['result_code']."<br>";
                echo "错误代码：".$refundQueryResult['err_code']."<br>";
                echo "错误代码描述：".$refundQueryResult['err_code_des']."<br>";
                echo "公众账号ID：".$refundQueryResult['appid']."<br>";
                echo "商户号：".$refundQueryResult['mch_id']."<br>";
                echo "子商户号：".$refundQueryResult['sub_mch_id']."<br>";
                echo "设备号：".$refundQueryResult['device_info']."<br>";
                echo "签名：".$refundQueryResult['sign']."<br>";
                echo "微信订单号：".$refundQueryResult['transaction_id']."<br>";
                echo "商户订单号：".$refundQueryResult['out_trade_no']."<br>";
                echo "退款笔数：".$refundQueryResult['refund_count']."<br>";
                echo "商户退款单号：".$refundQueryResult['out_refund_no']."<br>";
                echo "微信退款单号：".$refundQueryResult['refund_idrefund_id']."<br>";
                echo "退款渠道：".$refundQueryResult['refund_channel']."<br>";
                echo "退款金额：".($refundQueryResult['refund_fee']/100)."<br>";
                echo "现金券退款金额：".$refundQueryResult['coupon_refund_fee']."<br>";
                echo "退款状态：".$refundQueryResult['refund_status']."<br>";
            }
        }
    }

    public function orderclear()
    {
        $model = M('Order');
        /*$model->where(array(
		    //'is_show'=>'0',
			'xz_is_show'=>'0',
		))->delete();*/
        //echo $model->getLastSql();exit;
        $this->success('清理成功', U('Order/lis'), 1);
    }
}
