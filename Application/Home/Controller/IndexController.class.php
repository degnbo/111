<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController
{
    public function _initialize()
    {
        header("Content-type: text/html; charset=utf-8");
    }

    /**
     * @param $sid
     * @return mixed设置短信接口
     */

    public function set_notice($phone,$id,$code)
    {
        $redis = new \redis();
        $result = $redis->connect('127.0.0.1', 6379);
        $data = json_encode(array('code' => $code,'phone'=>$phone,'time' => time()));
        $redis->setex($phone.$id,15 * 60, $data);
        //return $notice_coed;
    }

    /**
     * @param $phone
     * @param $code
     * 清除session
     */
    public function clear_notice($phone,$id)
    {
        $redis = new \redis();
        $result = $redis->connect('127.0.0.1', 6379);
        $redis->set($phone.$id,'');
        //return $notice_coed;
    }

    /***
     * @param $phone
     * @return bool|string获取短信接口
     */
    public function get_notice($phone,$id)
    {
        $redis = new \redis();
        $result = $redis->connect('127.0.0.1', 6379);
        $data = $redis->get($phone.$id);
        $data = json_decode($data,true);
        return $data;
    }
    public function checkphone(){
        $mydata = file_get_contents('php://input');
        $info = json_decode($mydata, true);
        $sk = $this->get_session($info['session_id']);
        $openId = $sk['openid'];
        $mmodel = M('Member');
        $userlist = $mmodel->where(array('openid' => $openId))->find();
        if($userlist){
            if($userlist['phone']){
                echo json_encode(array('msg' => '已绑定手机号！', 'status' => 'success','uid'=>$userlist['id'],'phone'=>$userlist['phone']));die;
            }else{
                echo json_encode(array('msg' => '未绑定手机号！', 'status' => 'error','uid'=>$userlist['id']));die;
            }
        }else{
            echo json_encode(array('msg' => '获取用户信息失败！', 'status' => 'error'));die;
        }
    }
    public function ceshi11(){
        $phone =17310848698 ;
        //header("Content-type: text/html; charset=utf-8");
        import('Home.dx.TopSdk', APP_PATH, ".php");
        $c = new \TopClient;
        $c->appkey = '23806715';
        $c->secretKey = 'f5b0b46656114105e55f975efc182bc5';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("OHO运动");
        $code = rand(1000, 9999);//获取随机验证码
        $req->setSmsParam('{"secret":"' . $code . '"}');
        $req->setRecNum("$phone");
        $req->setSmsTemplateCode("SMS_66010398");
        $resp = $c->execute($req);
        dump($resp);
    }
    public function dx()
    {
        $mydata = file_get_contents('php://input');
        $info = json_decode($mydata, true);
        $sk = $this->get_session($info['session_id']);
        $openId = $sk['openid'];
        $mmodel = M('Member');
        //echo json_encode($info);die;
        $userlist = $mmodel->where(array('openid' => $openId))->find();
        if ($userlist) {
            $phone = $info['phone'];
            //17310848698
        } else {
            echo json_encode(array('msg' => '获取用户信息失败！', 'status' => 'error'));
            die;
        }
        //header("Content-type: text/html; charset=utf-8");
        import('Home.dx.TopSdk', APP_PATH, ".php");
        $c = new \TopClient;
        $c->appkey = '23806715';
        $c->secretKey = 'f5b0b46656114105e55f975efc182bc5';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("OHO运动");
        $code = rand(1000, 9999);//获取随机验证码
        $req->setSmsParam('{"secret":"' . $code . '"}');
        $req->setRecNum("$phone");
        $req->setSmsTemplateCode("SMS_66010398");
        $resp = $c->execute($req);
        if ($resp->result->err_code == 0 && $resp->result->success) {
            $this->set_notice($phone,$userlist['id'], $code);
            echo json_encode(array('msg' => '发送成功！', 'status' => 'success'));
        } else {
            echo json_encode(array('msg' => '发送失败！', 'status' => 'error'));
        }
        //dump($resp);die;
    }

    /**
     * 验证code
     */
    public function check_code()
    {
        $mydata = file_get_contents('php://input');
        $info = json_decode($mydata, true);
        $sk = $this->get_session($info['session_id']);
        $phone=$info['phone'];
        $code=$info['code'];
        $openId = $sk['openid'];
        $mmodel = M('Member');
        $userlist = $mmodel->where(array('openid' => $openId))->find();
        if ($userlist) {
            //$phone=$info['phone'];
            //17310848698
            $notice_data = $this->get_notice($phone,$userlist['id']);
            //echo json_encode(array('msg' => '验证码错误', 'data'=>$notice_data, 'status' => 'error'));die;
            if(!$notice_data['code']){
                echo json_encode(array('msg' => '验证码错误', 'code' => '105', 'status' => 'error'));die;
            }
            if (time()- $notice_data['time'] > 15 * 60) {
                echo json_encode(array('msg' => '短信验证码过期，请重新发送！','code' => '101', 'status' => 'error'));
            } else {
                if ($notice_data['code'] == $code) {
                    if($notice_data['phone']!=$phone){
                        echo json_encode(array('msg' => '验证码与手机号不一致', 'code' => '104', 'status' => 'error'));die;
                    }else{
                        $this->clear_notice($phone,$userlist['id']);
                        $mmodel->where(array('id'=>$userlist['id']))->setField('phone',$phone);
                        echo json_encode(array('msg' => '修改成功', 'code' => '0', 'status' => 'success'));
                    }
                } else {
                    echo json_encode(array('msg' => '短信验证码错误', 'code' => '102', 'status' => 'error'));
                }
            }
        } else {
            echo json_encode(array('msg' => '获取用户信息失败！', 'code' => '103', 'status' => 'error'));
            die;
        }
    }

    public function index()
    {
        header("location:http://www.oxygenhoop.com");
    }
    /*public function filter_name($str){
        import('Home.dealCard.class#preg',APP_PATH,".php");
        $preg = new \preg($str);
        return $preg -> str();
    }*/
    /**
     * 发布活动
     */
    public function add_active()
    {
        $sj = $_FILES;
        if (empty($sj)) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
        } else {
            $data = $_POST;
        }
        $starttime = strtotime($data['date'] . " " . $data['time']);
        if ($starttime <= (time() + 29 * 60)) {
            echo json_encode(array('msg' => '开始时间要在当前时间29分钟之后', 'status' => 'error'));
            die;
        }
        $sk = $this->get_session($data['session_id']);
        //$sk=json_decode(S($data['session_id']),true);
        //echo json_encode(array('msg' => '添加失败', 'status' => false,'data'=>$data));die;
        $openId = $sk['openid'];
        $mmodel = M('Member');

        $list = $mmodel->where(array("openid" => $openId))->find();
        if ($list) {
            $model = M('Active');
            if ($data['date']) {
                $data['start_time'] = strtotime($data['date'] . " " . $data['time']);
            }
            if (!empty($sj)) {
                $logo = $this->savepic();
                if ($logo) {
                    $data['pic'] = $logo['logo'];
                    $data['thumb_pic'] = $logo['thumb_logo'];
                }
            }
            $data['tname'] = filter_name($data['name']);
            $data['addtime'] = time();
            $data['member_id'] = $list['id'];

            $data['number'] = $data['num'];
            $data['address'] = $data['location'];
            $data['type_id'] = $data['type'];
            $data['acode'] = 0;
            $data['price'] = M('Peizhi')->getField('yajin');
            //echo json_encode($data);die;
            $lastid = $model->
            field('tname,lat,lng,start_time,number,address,phone,addtime,vcode,member_id,type_id,pic,thumb_pic,price')
                ->add($data);
            //echo $model->getLastSql();
            //echo json_encode(array('msg' => '添加失败', 'status' => false,'data'=>$data));die;
            //echo $model->getLastSql();die;
            if ($lastid) {
                //$ordernum=C('PRE_NO').$lastid;
                //$model->where(array('id'=>$lastid))->setField('ordernum',$ordernum);
                echo json_encode(array(
                    'msg' => '添加成功',
                    'status' => 'success',
                    'active_id' => $lastid));
                die;
            } else {
                echo json_encode(array('msg' => '添加失败', 'status' => 'error'));
                die;
            }
        } else {
            echo json_encode(array('msg' => '添加失败', 'status' => 'error'));
            die;
        }
    }

    /**
     * 获取发布活动价格
     */
    public function get_price()
    {
        $id = I('get.id');
        $model = M('Active');
        $mmodel = M('Member');
        $list = $model->field('start_time,tname,member_id,price,number,id')->
        find($id);
        //echo json_encode($list);die;
        //echo $model->getLastSql();die;
        $list['start_time'] = date("Y.m.d H:i:s", $list['start_time']);
        $list['total_price'] = $list['number'] * $list['price'];
        $list['username'] = $mmodel->where(array('id' => $list['member_id']))->getField('name');
        echo json_encode($list);
    }

    /**
     * 获取参与活动价格
     */
    public function get_join_price()
    {
        $id = I('get.id');
        $omodel = M('Order');
        //echo $id;die;
        $list = $omodel->alias('a')->
        field('a.ordernum,a.active_id,
        a.active_price total_price,a.id,b.addtime,b.tname,d.name username')
            ->join('left join __ACTIVE__ b on a.active_id=b.id')
            ->join('left join __MEMBER__ d on b.member_id=d.id')->
            /* ->join('left join __MEMBER__ c on a.member_id=c.id')->*/
            where(array('a.id' => $id))->find();
        //echo $omodel->getLastSql();die;
        $list['start_time'] = date("Y.m.d H:i:s", $list['addtime']);
        echo json_encode($list);
    }

    /**
     * @param $id
     * 删除发布活动
     */
    public function cancel_active($id)
    {
        $amodel = M('Active');
        $list = $amodel->find($id);
        $type = $list['is_pay'];
        if ($type == 0) {
            $amodel->delete($id);
            unlink('./Public/Uploads/' . $list['pic']);
            unlink('./Public/Uploads/' . $list['thumb_pic']);
            echo json_encode(array('is_pay' => '-1', 'msg' => '删除'));//0未支付直接删除
        } elseif ($type == 1) {
            //活动已经开始了
            if ($list['start_time'] <= time()) {
                if ($list['join_num'] > 0) {
                    echo json_encode(array('is_pay' => '1', 'is_cancel' => '1', 'msg' => '在活动开始之后有人报名不能取消'));
                    //1不起作用
                } else {
                    //没有人报名活动取消
                    if($list['price']==0){
                        $amodel->where(array('id'=>$id))->setField('is_pay','5');
                        echo json_encode(array('is_pay' => '5', 'msg' => '非签到，已退款'));
                    }else{
                        $price = $list['number'] * $list['price'] * 100;
                        $this->pub_refund('active', $list['ordernum'], $price, '5');
                        echo json_encode(array('is_pay' => '5', 'msg' => '非签到，已退款'));
                    }
                }
            } else {
                //活动未开始
                //退款接口
                $omodel = M('Order');
                $price = $list['price'] * $list['number'] * 100;
                if($price==0){
                    $amodel->where(array('id'=>$id))->save(array(
                        'is_pay'=>'4',
                        'join_num'=>0
                    ));
                }else {
                    $result1 = $this->pub_refund('active', $list['ordernum'], $price, '4');
                    if($result1['status']) {
                        $amodel->where(array('id' => $id))->setField('join_num', 0);
                    }
                }
                $olist = $omodel->where(array('active_id' => $list['id']))->select();
                //echo json_encode($olist);die;
                if (!empty($olist)) {
                    foreach ($olist as $k => $v) {
                        $oprice = $v['active_price'] * 100;
                        if ($v['pay_status'] == '1') {
                            if($oprice==0){
                                $omodel->where(array('id' => $v['id']))
                                    ->save(array(
                                        'pay_status' => '4',
                                    ));
                            }else{
                                $this->pub_refund('order', $v['ordernum'], $oprice, '4');
                            }
                            /*if ($result['status']) {
                                $amodel->where(array('id' => $id))->setDec('join_num', 1);
                            }*/
                        }
                    }
                }
                    //$omodel->where(array('active_id'=>$id))->save(array('is_show'=>'0'));
                    //$amodel->where(array('id'=>$id))->save(array('is_pay'=>'2','is_cancel'=>'0'));
                echo json_encode(array('is_pay' => '4', 'msg' => '活动取消'));//2已取消
            }
        } elseif ($type == 2) {
            echo json_encode(array('is_pay' => '2', 'msg' => '退款中'));//2已 退款中
        } elseif ($type == 3) {
            echo json_encode(array('is_pay' => '3', 'msg' => '已签到，已退款'));//3已退款，签到退款
        } elseif ($type == 4) {
            echo json_encode(array('is_pay' => '4', 'msg' => '已取消'));//4已 取消
        } elseif ($type == 5) {
            echo json_encode(array('is_pay' => '5', 'msg' => '非签到，已退款'));//5其他情况退款
        } elseif ($type == 6) {
            echo json_encode(array('is_pay' => '6', 'msg' => '未退款'));//6不能退款
        }
    }

    /**
     * @param $id
     * 删除未购买参加活动
     */
    public function cancel_join_active($id)
    {
        $omodel = M('Order');
        $list = $omodel->find($id);
        $amodel = M('Active');
        $type = $list['pay_status'];
        if ($type == 0) {
            $omodel->delete($id);
            echo json_encode(array('is_pay' => '-1', 'msg' => '删除'));
            die;//0未支付直接删除
        } elseif ($type == 1) {
            $alist = $amodel->where(array('id' => $list['active_id']))->find();
            if ($alist['start_time'] <= time()) {
                echo json_encode(array('is_pay' => '1', 'msg' => "活动开始之前才能取消！"));
                die;
            } else {
                //退款接口
                $price = $list['active_price'] * 100;
                if($price==0){
                    $omodel->where(array('id' => $id))
                        ->save(array(
                            'pay_status' => '4',
                        ));
                    if ($alist['join_num'] > 0) {
                        $amodel->where(array('id' => $list['active_id']))->setDec('join_num', 1);
                    }
                    echo json_encode(array('is_pay' => '4', 'msg' => "活动取消"));
                }else{
                    $result = $this->pub_refund("order", $list['ordernum'], $price, '4');
                    if ($result['status']) {
                        if ($alist['join_num'] > 0) {
                            $amodel->where(array('id' => $list['active_id']))->setDec('join_num', 1);
                        }
                        echo json_encode(array('is_pay' => '4', 'msg' => "活动取消"));
                    } else {
                        echo json_encode(array('is_pay' => '1', 'msg' => "退款失败"));
                    }
                }
                //$omodel->where(array('id'=>$id))->setField('pay_status','4');
            }
        } elseif ($type == 2) {
            echo json_encode(array('is_pay' => '2', 'msg' => "退款中"));
            die;
        } elseif ($type == 3) {
            echo json_encode(array('is_pay' => '3', 'msg' => "已退款"));
            die;
        } elseif ($type == 4) {
            echo json_encode(array('is_pay' => '4', 'msg' => "活动取消"));
            die;
        } elseif ($type == 5) {
            echo json_encode(array('is_pay' => '5', 'msg' => '未签到，已退款'));//5其他情况退款
        } elseif ($type == 6) {
            echo json_encode(array('is_pay' => '6', 'msg' => '未退款'));//6不能退款
        }
    }

    /**
     * 报名活动
     */
    public function join_active()
    {
        $mydata = file_get_contents('php://input');
        $info = json_decode($mydata, true);
        $sk = $this->get_session($info['session_id']);
        $openId = $sk['openid'];
        $mmodel = M('Member');
        //S('hh',json_encode($info));//die;
        $userlist = $mmodel->where(array('openid' => $openId))->find();
        if ($userlist) {
            $model = M('Order');
            $amodel = M('Active');
            $alist = $amodel->field('acode,price,join_num,member_id,start_time,number,id,is_pay,start_time')->find($info['aid']);
            if ($alist['number'] <= 0) {
                echo json_encode(array('msg' => "活动没有人数！", 'status' => false));
                die;
            }
            if ($alist['member_id'] == $userlist['id']) {
                echo json_encode(array('msg' => "不能报名自己发布的活动！", 'status' => false));
                die;
            }
            if ($alist['start_time'] < time()) {
                echo json_encode(array('msg' => "请在活动开始之前报名！", 'status' => false));
                die;
            }
            //$count = $model->where(array('active_id' => $info['aid'], 'pay_status' => '1'))->count();
            if ($alist['join_num'] >= $alist['number']) {
                echo json_encode(array('msg' => "报名人数已满！", 'status' => false));
                die;
            }
            $list = $model->where(array(
                'member_id' => $userlist['id'],
                'active_id' => $info['aid'],
                'pay_status' => array('neq', '4')
            ))->find();
            //echo json_encode(array('msg' => "下单成功!", 'status' => false,'info'=>$list));die;
            //后台发布的活动
            if ($alist['acode'] == '1') {
                if (empty($list)) {
                    $lastid = $model->add(array(
                        'member_id' => $userlist['id'],
                        'active_id' => $info['aid'],
                        'pay_status' => '0',
                        'is_show' => '1',
                        'active_price' => $alist['price'],
                        'addtime' => time(),
                        //'ordernum'=>build_order_no(),
                    ));
                    echo json_encode(array('msg' => "下单成功!", 'status' => true, 'order_id' => $lastid));
                    die;
                } else {
                    if ($list['pay_status'] == '0') {
                        echo json_encode(array('msg' => "你已经下过单了!", 'status' => false, 'order_id' => $list['id']));
                        die;
                    } elseif ($list['pay_status'] != '4') {
                        echo json_encode(array('msg' => "你已经报过名了!", 'status' => false));
                        die;
                    }
                }
            } else {
                if ($alist['is_pay'] == '0') {
                    echo json_encode(array('msg' => "你报名的活动未支付！", 'status' => false));
                    die;
                } else {
                    if (empty($list)) {
                        /*if ($alist['start_time'] < time()) {
                            echo json_encode(array('msg' => "请在活动开始之前报名！", 'status' => false));die;
                        }*/
                        $lastid = $model->add(array(
                            'member_id' => $userlist['id'],
                            'active_id' => $info['aid'],
                            'pay_status' => '0',
                            'is_show' => '1',
                            'active_price' => $alist['price'],
                            'addtime' => time(),
                            //'ordernum'=>build_order_no(),
                        ));
                        echo json_encode(array('msg' => "下单成功!", 'status' => true, 'order_id' => $lastid));
                        die;
                    } else {
                        if ($list['pay_status'] == '0') {
                            echo json_encode(array('msg' => "你已经下过单了!", 'status' => false));
                            die;
                        } elseif ($list['pay_status'] != '4') {
                            echo json_encode(array('msg' => "你已经报过名了!", 'status' => false));
                            die;
                        }
                    }
                }
            }
        } else {
            echo json_encode(array('msg' => "获取用户信息失败!", 'status' => false));
        }
    }

    public function savepic()
    {
        $config = array(
            'maxSize' => 2 * 1024 * 1024,
            'savePath' => 'active/',
            'rootPath' => './Public/Uploads/',
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date', 'Ymd')
        );
        $log = false;
        $upload = new \Think\Upload($config);// 实例化上传类
        if ($_FILES['file']['error'] == 0) {
            $info = $upload->upload();
            if ($info) {
                $tp = array();
                $logo = $info['file']['savepath'] . $info['file']['savename'];
                $smlogo = $info['file']['savepath'] . 'thumb_' . $info['file']['savename'];
                $image = new \Think\Image();
                $image->open('./Public/Uploads/' . $logo);
                $image->thumb(160, 160, 3)->save('./Public/Uploads/' . $smlogo);
                $tp['logo'] = $logo;
                $tp['thumb_logo'] = $smlogo;
                $log = $tp;
            } else {
                return $log;
            }
        }
        return $log;
    }

    /**
     * 栏目分类
     */
    public function get_type()
    {
        //C('DB_NAME','jietuo');
        $model = M('Category');
        $list = $model->field("id,cate_name")->
        where(array('pid' => 0, 'is_show' => '1'))->
        order('sort_num')->select();
        //dump($list);die;
        echo json_encode($list);
        die;
    }

    /* public function get_type1(){
         //C('DB_NAME','jietuo');
         $model=M('Category');
         $list=$model->field("id,cate_name")->where(array('pid'=>0))->select();
         dump($list);die;
         echo json_encode($list);die;
     }*/
    public function get_type_list($type_id = null, $lat = null, $lng = null)
    {
        //file_put_contents("D:/c.txt",json_encode(array('jd'=>$lat,'wd'=>$lng)));
        $model = M('Active');
        $where['b.type'] = array('neq', '2');
        $where['a.is_pay'] = array('eq', '1');
        $where['a.start_time'] = array('egt', time());
        $lj = "https://" . $_SERVER['HTTP_HOST'] . "/Public/Uploads/";
        if (!empty($lat) && !empty($lng)) {
            $jl = "ROUND(6378.138*2*ASIN(SQRT(POW(SIN((" . $lat . "*PI()/180-a.lat*PI()/180)/2),2)+COS(" . $lat . "*PI()/180)*COS(lat*PI()/180)*POW(SIN((" . $lng . "*PI()/180-lng*PI()/180)/2),2)))*1000) as jl";
            //$where['_string']
            $field = 'a.*,c.cate_name,CONCAT("' . $lj . '",a.thumb_pic) thumb_logo,CONCAT("' . $lj . '",a.pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime,' . $jl;
            $order = 'jl asc';
            $sj = 10000;
            $having = "jl<=" . $sj;
            //$having="";
        } else {
            $field = 'a.*,c.cate_name,CONCAT("' . $lj . '",a.thumb_pic) thumb_logo,CONCAT("' . $lj . '",a.pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime';
            $order = 'a.id asc';
            $having = "";
        }
        //echo $lj;
        if (!empty($type_id)) {
            $where['a.type_id'] = $type_id;
        }
        $list = $model->alias('a')->
        field($field)->
        join('left join __MEMBER__ b on a.member_id=b.id')->
        join('left join __CATEGORY__ c on a.type_id=c.id')
            ->where($where)->order($order)->
            having($having)->select();
        //echo $model->getLastSql();
        foreach ($list as $k => $v) {
            $v['join_num'] = $this->get_total_count($v['id']);
            $list[$k] = $v;
        }
        echo json_encode($list);
    }

    /**
     * 获取banner列表
     */
    public function get_tj_list()
    {
        $model = M('Active');
        $lj = "https://" . $_SERVER['HTTP_HOST'] . "/Public/Uploads/";
        $list = $model->
        field('id,CONCAT("' . $lj . '",pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime')
            ->where(array('acode' => array('eq', '1'), 'is_nav' => '1', 'is_show' => '1'))->limit(5)->select();
        echo json_encode($list);die;
        //dump($list);die;
        /*$list = $model->alias('a')->
        field('a.id,c.cate_name,CONCAT("' . $lj . '",a.pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime')->
        join('left join __MEMBER__ b on a.member_id=b.id')->
        join('left join __CATEGORY__ c on a.type_id=c.id')
            ->where(array('b.type' => array('eq', '2')))->limit(5)->select();*/
    }

    public function get_active_detial()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $aid=$getinfo['aid'];
        $model = M('Active');
        $lj = "https://" . $_SERVER['HTTP_HOST'] . "/Public/Uploads/";
        $list = $model->alias('a')->
        field('a.*,b.name username,c.cate_name,CONCAT("' . $lj . '",a.pic) logo,
        CONCAT("' . $lj . '",a.thumb_pic) thumb_logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime')->
        join('left join __MEMBER__ b on a.member_id=b.id')->
        join('left join __CATEGORY__ c on a.type_id=c.id')
            ->where(array('a.id' => $aid))->find();
        //$list['price'] = $list['price'];
        $list['join_num'] = $this->get_total_count($aid);
        $mmodel = M('Member');
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $userlist = $mmodel->where(array('openid' => $openId))->find();
        $omodel=M('Order');
        if($userlist['id']==$list['member_id']){
            //发布活动
            $list['attribute']='a';
            if(time()>$list['start_time']){
                $list['zt']=8;
            }else{
                $list['zt']=$list['is_pay'];
            }
        }else{
            if(time()>$list['start_time']){
                $list['zt']=8;
            }else{
                $list['attribute']='b';
                $olist=$omodel->where(array(
                    'active_id'=>$aid,
                    'member_id'=>$userlist['id'],
                    'pay_status' => array('neq', '4')
                ))->find();
                if($olist){
                    $list['zt']=$olist['pay_status'];
                    $list['oid']=$olist['id'];
                }else{
                    $olist1=$omodel->where(array(
                        'active_id'=>$aid,
                        'member_id'=>$userlist['id'],
                        'pay_status' => array('eq', '4')
                    ))->find();
                    if($olist1){
                        $list['zt']='4';
                        ///$list['oid']=$olist1['id'];
                    }else{
                        $list['zt']='7';
                    }
                }
            }
        }
        //echo $model->getLastsql();
        //dump($list);die;
        echo json_encode($list);
    }

    public function get_total_count($id)
    {
        $model = M('Order');
        $list = $model->where(array(
            'pay_status' => array('in', '1,2,3,5,6'),
            'active_id' => $id
        ))->count();
        return $list;
    }

    public function get_user_info($mid = 3)
    {
        $model = M('Member');
        $lj = "https://" . $_SERVER['HTTP_HOST'];
        $list = $model->find($mid);
        $list['logo'] = $lj . $list['logo'];
        if ($list['pic_list']) {
            $urls = explode('|', $list['pic_list']);
        }
        if (!empty($urls)) {
            foreach ($urls as $k => $v) {
                $urls[$k] = $lj . $v;
            }
        }
        $list['pic_list'] = $urls;
        echo json_encode($list);
    }

    public function search($keyword = null, $lat = null, $lng = null)
    {
        $where['a.is_pay'] = array('eq', '1');
        $where['a.start_time'] = array('egt', time());
        //file_put_contents("D:/c.txt",json_encode(array('jd'=>$lat,'wd'=>$lng)));//die;
        $lj = "https://" . $_SERVER['HTTP_HOST'] . "/Public/Uploads/";
        if ($keyword) {
            $where['a.tname|c.cate_name'] = array('like', "%$keyword%");
        }
        if ($lat && $lng) {
            $jl = "ROUND(6378.138*2*ASIN(SQRT(POW(SIN((" . $lat . "*PI()/180-lat*PI()/180)/2),2)+COS(" . $lat . "*PI()/180)*COS(lat*PI()/180)*POW(SIN((" . $lng . "*PI()/180-lng*PI()/180)/2),2)))*1000) as jl";
            //$where['_string']
            $field = 'a.*,c.cate_name,CONCAT("' . $lj . '",a.thumb_pic) thumb_logo,CONCAT("' . $lj . '",a.pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime,' . $jl;
            $order = 'jl asc';
            //$where['jl']=array('elt','10000');
            $sj = 10000;
            $having = "jl<=" . $sj;
        } else {
            $field = 'a.*,c.cate_name,CONCAT("' . $lj . '",a.thumb_pic) thumb_logo,CONCAT("' . $lj . '",a.pic) logo,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime';
            $order = 'a.addtime desc';
            $having = '';
        }
        $model = M('Active');
        $list = $model->alias('a')->field($field)->
        join('left join __CATEGORY__ c on a.type_id=c.id')
            ->where($where)->order($order)->having($having)->select();
        //echo $model->getLastSql();
        //dump($list);die;
        foreach ($list as $k => $v) {
            $v['join_num'] = $this->get_total_count($v['id']);
            $list[$k] = $v;
        }
        echo json_encode($list);
    }

    public function login()
    {
        $code = I('get.code');
        //$code="051t7igV0knOXW1nPrgV0cE5gV0t7igQ";
        //echo $code;
        $appid = "wxc767748b8c5bfa4b";
        //$appsecret="3c956cb6890cee94be9908b8cbdd26dd";
        $appsecret = "16be8797f2b3b7b3bd968416346dcbd8";
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$appsecret&js_code=$code&grant_type=authorization_code";
        $data = $this->getData($url);
        $list = json_decode($data, true);
        if ($list['errcode']) {
            echo json_encode(array('status' => false));
            die;
        }
        $id = session_id();
        /*if(!S($id)){
            S($id,$data,3600*24*5);
        }*/
        $redis = new \redis();
        $result = $redis->connect('127.0.0.1', 6379);
        $sid = $redis->get($id);
        if (!$sid) {
            $redis->setex($id, 3600 * 24 * 7, $data);
        }
        echo json_encode(array('status' => true, 'session_id' => $id));
        die;
    }

    public function get_session($sid)
    {
        $redis = new \redis();
        $result = $redis->connect('127.0.0.1', 6379);
        $sid = $redis->get($sid);
        $sk = json_decode($sid, true);
        return $sk;
    }

    public function check_login()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        //file_put_contents("D:/c.txt",$mydata);//die;
        //获取session_id
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $sessionKey = $sk['session_key'];
        import('Home.PHP.wxBizDataCrypt', APP_PATH, ".php");
        $appid = "wxc767748b8c5bfa4b";
        $encryptedData = $getinfo['encryptedData'];
        $iv = $getinfo['iv'];
        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data, true);
            $model = M("Member");
            $list = $model->where(array('openid' => $openId))->find();
            if (empty($list)) {
                if($data['avatarUrl']){
                    $info = $this->downloadImageFromWeiXin($data['avatarUrl']);
                    $time = time();
                    $pathname = "./Public/tempfile/forver/" . date('Ymd');
                    $pathname1 = "/Public/tempfile/forver/" . date('Ymd');
                    if (!file_exists($pathname)) {
                        mkdir($pathname, 0777, true);
                    }
                    $filename = $time . substr(uniqid(), 0, 5) . ".png";
                    $local_file = $pathname . "/" . $filename;
                    $file_dir = fopen($local_file, "w");
                    if (false !== $file_dir) {
                        if (false !== fwrite($file_dir, $info['body'])) {
                            fclose($file_dir);
                        }
                    }
                    if(!filesize($local_file)){
                        $file_path='';
                    }else{
                        $file_path=$pathname1 . "/" . $filename;
                    }
                }else{
                    $file_path='';
                }
                $model->add(array(
                    'openid' => $openId,
                    'name' => $data['nickName'],
                    'logo' =>$file_path,
                    'sex' => $data['gender'],
                    'type' => '1',
                    'addtime' => $data['watermark']['timestamp'],
                ));
            } else {
                if($list['logo']==''){
                    $info = $this->downloadImageFromWeiXin($data['avatarUrl']);
                    $time = time();
                    $pathname = "./Public/tempfile/forver/" . date('Ymd');
                    $pathname1 = "/Public/tempfile/forver/" . date('Ymd');
                    if (!file_exists($pathname)) {
                        mkdir($pathname, 0777, true);
                    }
                    $filename = $time . substr(uniqid(), 0, 5) . ".png";
                    $local_file = $pathname . "/" . $filename;
                    $file_dir = fopen($local_file, "w");
                    if (false !== $file_dir) {
                        if (false !== fwrite($file_dir, $info['body'])) {
                            fclose($file_dir);
                        }
                    }
                    if(!filesize($local_file)){
                        $file_path='';
                    }else{
                        $file_path=$pathname1 . "/" . $filename;
                    }
                    $model->where(array('id'=>$list['id']))->setField('logo',$file_path);
                }
            }
            echo json_encode(array('status' => true, 'msg' => 'ok'));
        } else {
            echo $errCode;
            die;
        }
    }

    /**
     * 用户修改
     */
    public function edit_user()
    {
        $sj = $_FILES;
        $data = $_POST;
        //echo json_encode($sj);
        //echo json_encode($data);die;
        $logo = $this->savepic1();
        //echo json_encode($logo);die;
        if ($logo) {
            $data['logo'] = "/Public/tempfile/" . $logo;
        }
        $model = M('Member');
        $oldpic = $model->where(array('id' => $data['id']))->getField('logo');
        $result = $model->field('sex,birth,logo,name,lianxi')->
        where(array('id' => $data['id']))->save($data);
        if ($result !== false) {
            @unlink("." . $oldpic);
            echo json_encode(array(
                'msg' => 'ok',
                'status' => true,
            ));
        } else {
            @unlink("." . $data['logo']);
            echo json_encode(array(
                'msg' => 'error',
                'status' => false,
            ));
        }
    }

    /**
     * 处理多张图片
     */
    public function deal_many_pic()
    {
        $sj = $_FILES;
        $id = I('post.id');
        $model = M('Member');
        $url = $model->find($id);
        $info = $this->upload2(2, "shangchuan/");
        //echo json_encode($sj);die;
        //dump($info);die;
        //echo $id;die;
        if ($info['file']['error'] == 0) {
            if ($url['pic_list']) {
                $urls = explode('|', $url['pic_list']);
                $urls[] = "/Public/" . $info['file']['savepath'] . $info['file']['savename'];
                $lj = implode('|', $urls);

            } else {
                $urls = "/Public/" . $info['file']['savepath'] . $info['file']['savename'];
                $lj = $urls;
            }
            $result = $model->where(array('id' => $id))->setField('pic_list', $lj);
            //echo $model->getLastSql();die;
            if ($result !== false) {
                echo json_encode(array('msg' => 'ok', 'status' => true));
                exit;
            } else {
                echo json_encode(array('msg' => 'error', 'status' => false));
                die;
            }
        } else {
            echo json_encode(array('msg' => 'error', 'status' => false));
            die;
        }
        //file_put_contents("D:/b.txt",print_r($sj,true));
        //echo json_encode($sj);
    }

    public function upload2($size = 2, $lanmu = 'shangchuan/')
    {
        $config = array(
            'maxSize' => $size * 1024 * 1024,
            'savePath' => $lanmu,
            'rootPath' => './Public/',
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date', 'Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if ($_FILES['file']['error'] == 0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        } else {
            return false;
        }
    }

    public function savepic1()
    {
        $config = array(
            'maxSize' => 2 * 1024 * 1024,
            'savePath' => 'forver/',
            'rootPath' => './Public/tempfile/',
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date', 'Ymd')
        );
        $log = '';
        $upload = new \Think\Upload($config);// 实例化上传类
        if ($_FILES['myfile']['error'] == 0) {
            $info = $upload->upload();
            if ($info) {
                //echo json_encode($info);die;
                $log = $info['myfile']['savepath'] . $info['myfile']['savename'];
            }
        }
        return $log;
    }

    /**
     *删除图片
     *
     */
    public function delete_pic()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $id = $getinfo['id'];
        $tp = $getinfo['tp'];
        $model = M('Member');
        $url = $model->where(array('id' => $id))->find();
        $urls = explode('|', $url['pic_list']);
        foreach ($urls as $k => $v) {
            if ($tp == $v) {
                unset($urls[$k]);
                unlink("." . $tp);
            }
        }
        $lj = implode('|', $urls);
        $url = $model->field('pic_list')->where(array('id' => $id))->setField('pic_list', $lj);
    }

    /**
     * @param $id
     * 修改列表
     */
    public function get_user_list($id)
    {
        $model = M("Member");
        $lj = "https://" . $_SERVER['HTTP_HOST'];
        $list = $model->where(array('id' => $id))->find();
        if (empty($list)) {
            echo json_encode(array('status' => false, 'msg' => '请求错误！'));
        } else {
            $url = $list['pic_list'];
            if ($url) {
                $urls = explode('|', $url);
            } else {
                $urls = array();
            }
            $list['web_server'] = $lj;
            $list['pic_list'] = $urls;
            $list['logo'] = $lj . "/" . $list['logo'];
            echo json_encode(array('status' => true, 'msg' => 'ok', 'data' => $list));
        }
    }

    /**
     * 个人中心
     */
    public function get_user()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $sk = $this->get_session($getinfo['session_id']);
        //$sk=json_decode(S($getinfo['session_id']),true);
        //echo json_encode($getinfo);die;
        $openId = $sk['openid'];
        $model = M("Member");
        $lj = "https://" . $_SERVER['HTTP_HOST'];
        $list = $model->field('logo,name,id')->where(array('openid' => $openId))->find();
        if (empty($list)) {
            echo json_encode(array('status' => false, 'msg' => '请求错误！'));
        } else {
            //我发布的活动
            $amodel = M('Active');
            $alist = $amodel->field('acode,tname,is_pay,id,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime')->
            where(array('member_id' => $list['id']))->
            limit(3)->order('id desc')->select();
            //echo $amodel->getLastSql();
            $list['logo'] = $lj . $list['logo'];
            $list['my_active'] = $alist;
            //我参与的活动
            $omodel = M('Order');
            $olist = $omodel->alias('a')->
            field('a.id,a.is_comment,a.pay_status,b.tname,b.acode,b.start_time,FROM_UNIXTIME(b.start_time,"%Y-%m-%d %H:%i") starttime,a.ordernum,a.active_id,a.member_id')->
            join('left join __ACTIVE__ b on a.active_id=b.id')
                ->where(array('a.member_id' => $list['id']))->
                limit(3)->order('a.addtime desc')->select();
            foreach ($olist as $k => $v) {
                if (time() > $v['start_time']) {
                    if ($v['pay_status'] == '0') {
                        $v['is_can_pl'] = '0';
                    } else {
                        $v['is_can_pl'] = '1';
                    }
                } else {
                    $v['is_can_pl'] = '0';
                }
                $olist[$k] = $v;
            }
            $list['join_active'] = $olist;
            //echo $omodel->getLastSql();die;
            echo json_encode(array('status' => true, 'msg' => 'ok', 'data' => $list));
        }
    }

    /**
     * 发布评价
     */
    public function comment()
    {
        $sj = $_FILES;
        if (empty($sj)) {
            $data = file_get_contents('php://input');
            $info = json_decode($data, true);
        } else {
            $info = $_POST;
        }
        $sk = $this->get_session($info['session_id']);
        //$sk=json_decode(S($getinfo['session_id']),true);
        //echo json_encode($getinfo);die;
        $openId = $sk['openid'];
        $model = M('Member');
        $uid = $model->where(array('openid' => $openId))->getField('id');
        if (!$uid) {
            echo json_encode(array('msg' => '请求错误', 'status' => false));
            die;
        }
        $info['uid'] = $uid;
        //echo json_encode($info);die;
        $cmodel = M("Comment");
        $list = $cmodel->where(array('oid' => $info['id']))->find();
        if (!$list) {
            if ($sj) {
                if ($tp = $this->comment_pic()) {
                    $info['logo'] = $tp['file']['savepath'] . $tp['file']['savename'];
                } else {
                    $info['logo'] = '';
                }
            } else {
                $info['logo'] = '';
            }
            $lastid = $cmodel->add(array(
                'oid' => $info['id'],
                'member_id' => $info['uid'],
                'active_id' => $info['aid'],
                'content' => $info['content'],
                'uptime' => time(),
                'addtime' => time(),
                'is_show' => '1',
                'star' => $info['star'],
                'logo' => $info['logo'],
            ));
            //echo json_encode(array('msg' => '评价成功', 'status' => false,'info'=>$cmodel->getLastSql()));
            M('Order')->where(array('id' => $info['id']))
                ->setField('is_comment', '2');
            echo json_encode(array('msg' => '评价成功', 'status' => true));
        } else {
            echo json_encode(array('msg' => '你已经评价过了', 'status' => false));
        }
    }

    public function comment_pic($size = 2, $lanmu = 'comment/')
    {
        $config = array(
            'maxSize' => $size * 1024 * 1024,
            'savePath' => $lanmu,
            'rootPath' => './Public/Uploads/',
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date', 'Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if ($_FILES['file']['error'] == 0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        } else {
            return false;
        }
    }

    //举报
    public function active_report()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        //file_put_contents("D:/c.txt",$mydata);//die;
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $model = M('Member');
        $amodel = M('Report');
        $hmodel = M('Active');
        $hlist = $hmodel->field('start_time')->find($getinfo['aid']);
        if ($hlist['start_time'] > time()) {
            echo json_encode(array('msg' => '活动未开始！', 'status' => false));
            die;
        }
        $list = $model->where(array('openid' => $openId))->find();
        if ($list) {
            $alist = $amodel->where(array('aid' => $getinfo['aid'], 'uid' => $list['id']))->find();
            if ($alist) {
                echo json_encode(array('msg' => '你已经举报过了', 'status' => false));
            } else {
                $amodel->add(array(
                    "uid" => $list['id'],
                    'aid' => $getinfo['aid'],
                    'addtime' => time(),
                    'content' => $getinfo['content'],
                ));
                echo json_encode(array('msg' => '举报成功', 'status' => true));
                die;
            }
        } else {
            echo json_encode(array('msg' => '请求错误', 'status' => false));
            die;
        }
    }

    /**
     * 我发布的活动列表
     */
    public function my_pub_active_list()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        //file_put_contents("D:/c.txt",$mydata);//die;
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $model = M('Member');
        $list = $model->where(array('openid' => $openId))->find();
        $num = $getinfo['num'];
        if ($list) {
            $amodel = M('Active');
            if (empty($num)) {
                $num = '';
            }
            $alist = $amodel->field('acode,tname,is_pay,id,FROM_UNIXTIME(start_time,"%Y-%m-%d %H:%i") starttime')->
            where(array('member_id' => $list['id']))->
            order('addtime desc')->limit($num)->select();
            echo json_encode($alist);
        } else {
            echo json_encode(array());
        }
    }

    /**
     * 我参与的活动列表
     */
    public function my_join_active_list()
    {
        //$id=I('get.id');
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        //file_put_contents("D:/c.txt",$mydata);//die;
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $model = M('Member');
        $list = $model->where(array('openid' => $openId))->find();
        $num = $getinfo['num'];
        if ($list) {
            $omodel = M('Order');
            if (empty($num)) {
                $num = '';
            }
            $olist = $omodel->alias('a')->
            field('a.id,a.is_comment,a.pay_status,b.acode,b.start_time,FROM_UNIXTIME(b.start_time,"%Y-%m-%d %H:%i") starttime,b.tname,a.active_id')->
            join('left join __ACTIVE__ b on a.active_id=b.id')
                ->where(array('a.member_id' => $list['id']))
                ->order('a.addtime desc')->limit($num)->select();
            foreach ($olist as $k => $v) {
                if (time() > $v['start_time']) {
                    if ($v['pay_status'] == '0') {
                        $v['is_can_pl'] = '0';
                    } else {
                        $v['is_can_pl'] = '1';
                    }
                } else {
                    $v['is_can_pl'] = '0';
                }
                $olist[$k] = $v;
            }
            echo json_encode($olist);
        } else {
            echo json_encode(array());
        }
    }

    public function comment_list()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $model = M('Member');
        $list = $model->where(array('openid' => $openId))->find();
        //echo json_encode($getinfo);die;
        if ($list) {
            $cmodel = M("Comment");
            $result = $cmodel->where(array('oid' => $getinfo['id']))->find();
            $result['logo'] = "https://" . $_SERVER['HTTP_HOST'] . "/Public/Uploads/" . $result['logo'];
            echo json_encode($result);
        }
    }

    public function downloadImageFromWeiXin($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        //print_r($package);die;
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        return array_merge(array('body' => $package), array('header' => $httpinfo));
    }

    public function getData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 添加评论
     * post请求
     */
    public function add_comment()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        //file_put_contents("D:/c.txt",$mydata);//die;
        $sk = $this->get_session($getinfo['session_id']);
        $openId = $sk['openid'];
        $model = M('Member');
        //echo json_encode($getinfo);die;
        $list = $model->where(array('openid' => $openId))->find();
        if ($list) {
            $cmodel = M("Commemt");
            $result = $cmodel->add(array(
                'member_id' => $list['id'],
                'active_id' => $getinfo['active_id'],
                'lat' => $getinfo['lat'],
                'lng' => $getinfo['lng'],
                'content' => $getinfo['content'],
                'address' => $getinfo['address'],
                'is_show' => '1',
                'uptime' => time(),
            ));
            if ($result) {
                echo json_encode(array("msg" => '添加成功!', 'status' => true));
            } else {
                echo json_encode(array("msg" => '添加失败!', 'status' => false));
            }
        }
    }

    public function about()
    {
        $pmodel = M("Peizhi");
        $list = $pmodel->field('content')->find();
        echo json_encode($list);
    }

    public function ewm()
    {
        $aid = I('get.aid');
        $lj = "https://" . $_SERVER['HTTP_HOST'];
        //$lj="http://192.168.0.72";
        import('Home.Ewm.phpqrcode', APP_PATH, ".php");
        //$lj."/index.php/Home/Index/del_sign/aid/"
        $url = $aid;
        //$value = $_GET['url'];//二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        \QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
    }

    public function del_sign()
    {
        $uid = I('get.uid');
        $aid = I('get.aid');
        $amodel = M('Active');
        $omodel = M('Order');
        $alist = $amodel->find($aid);
        //echo json_encode($alist);die;
        $olist = $omodel->where(array(
            'active_id' => $aid,
            'member_id' => $uid,
            'pay_status' => array('neq', '4'),
        ))->find();
        if ($olist) {
            if ($alist['start_time'] > time()) {
                echo json_encode(array('msg' => '活动还未开始！', 'status' => 'error'));
                die;
            } else {
                if (time() - $alist['start_time'] > 3600) {
                    echo json_encode(array('msg' => '活动开始后一个小时内签到有效！', 'status' => 'error'));
                } else {
                    //echo json_encode($olist);die;
                    if ($olist['pay_status'] == '1') {
                        if ($alist['acode'] == '1') {
                            //$this->pub_refund1('order', $olist['ordernum'], 1);
                            //签到未退款//后台活动
                            $omodel->where(array('ordernum' => $olist['ordernum']))->setField('pay_status', '6');
                            echo json_encode(array('msg' => '签到成功', 'status' => 'success'));
                            die;
                        } else {
                            $price = $alist['price'] * $alist['number'] * 100;
                            $oprice = $alist['price'] * 100;
                            if ($alist['is_pay'] == '1') {
                                if($price==0){
                                    $amodel->where(array('id' => $aid))->setField('is_pay', '3');
                                }else{
                                    $this->pub_refund1('active', $alist['ordernum'], $price);
                                }
                                if($oprice==0){
                                    $omodel->where(array('ordernum' => $olist['ordernum']))->setField('is_pay', '3');
                                }else{
                                    $this->pub_refund1('order', $olist['ordernum'], $oprice);
                                }
                                echo json_encode(array('msg' => '签到成功', 'status' => 'success'));
                            } elseif ($alist['is_pay'] == '0') {
                                echo json_encode(array('msg' => '操作异常', 'status' => 'error'));
                            } elseif ($alist['is_pay'] == '2') {
                                echo json_encode(array('msg' => '订单处理中，操作异常', 'status' => 'error'));
                            } elseif ($alist['is_pay'] == '3') {
                                if($oprice==0){
                                    $omodel->where(array('ordernum' => $olist['ordernum']))->setField('is_pay', '3');
                                }else{
                                    $this->pub_refund1('order', $olist['ordernum'], $oprice);
                                }
                                echo json_encode(array('msg' => '签到成功', 'status' => 'success'));
                            } elseif ($alist['is_pay'] == '4') {
                                echo json_encode(array('msg' => '活动已取消，操作异常', 'status' => 'error'));
                            } elseif ($alist['is_pay'] == '5') {
                                echo json_encode(array('msg' => '非签到，已退款', 'status' => 'error'));
                            } elseif ($alist['is_pay'] == '6') {
                                echo json_encode(array('msg' => '未退款', 'status' => 'error'));
                            }
                        }
                    } else {
                        echo json_encode(array('msg' => '签到过了！', 'status' => 'error'));
                    }
                }
            }
        } else {
            echo json_encode(array('msg' => '你未报名该活动！', 'status' => 'error'));
        }
    }

    //扫码签到退款
    public function pub_refund1($model, $out_trade_no, $refund_fee = '1')
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
            //$refund_fee = '1';
            //使用退款接口
            $refund = new \Refund_pub();
            $refund->setParameter("out_trade_no", "$out_trade_no");//商户订单号
            $refund->setParameter("out_refund_no", "$out_refund_no");//商户退款单号
            $refund->setParameter("total_fee", "$total_fee");//总金额
            $refund->setParameter("refund_fee", "$refund_fee");//退款金额
            $refund->setParameter("op_user_id", \WxPayConf_pub::MCHID);//操作员
            $refundResult = $refund->getResult();
            if ($refundResult["return_code"] == "SUCCESS") {
                //发布活动
                $amodel = M('Active');
                $omodel = M('Order');
                if ($model == 'active') {
                    $amodel->where(array('ordernum' => $out_trade_no))->setField('is_pay', '3');
                } elseif ($model == 'order') {
                    $omodel->where(array('ordernum' => $out_trade_no))->setField('pay_status', '3');
                    /*$aid=$omodel->where(array('ordernum'=>$out_trade_no))->getField('active_id');
                    $ac_status=$amodel->where(array('id'=>$aid))->getField('is_pay');*/
                }
            }
            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($refundResult["return_code"] == "FAIL") {
                echo json_encode(array('msg' => "通信出错：" . $refundResult['return_msg'], 'status' => false));
            } else {
                echo json_encode(array('msg' => "退款成功", 'status' => true));
            }
        }
    }

    function  log_result($file, $word)
    {
        $fp = fopen($file, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d-%H：%M：%S", time()) . "\n" . $word . "\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * 支付成功消息通知
     */
    public function notify_url()
    {
        //include_once("./log_.php");
        import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        //使用通用通知接口
        $notify = new \Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        //file_put_contents("./a.txt",$xml);
        //S('name',$xml);
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL");//返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");//返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        //有这个的话成功之后只返回一次，不会返回8次
        echo $returnXml;
        //S('name',$returnXml);

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        //以log文件形式记录回调信息
        $log_name = "./Public/logs/notify.log";//log文件路径
        $this->log_result($log_name, "【接收到的notify通知】:\n" . $xml . "\n");

        if ($notify->checkSign() == TRUE) {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【通信出错】:\n" . $xml . "\n");
                //$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【业务出错】:\n" . $xml . "\n");
            } else {
                $postxml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                $trade_no = $postxml->out_trade_no;
                $trade_id = $postxml->transaction_id;
                //$product_id = $postxml->product_id;
                //$goods_tag = $postxml->goods_tag;
                $amodel = M('Active');
                $omodel = M('Order');
                /*if($goods_tag=='active'){
                    $amodel->where(array('id' => "$product_id "))
                        ->save(array(
                            'ordernum'=>"$trade_no",
                            'is_pay' => '1',
                            'pay_time' => time(),
                            'trade_id' => "$trade_id"
                        ));
                }elseif($goods_tag=='order'){
                    $omodel->where(array('id' => "$product_id"))
                        ->save(array(
                            'pay_status' => '1',
                            'trade_id' => "$trade_id",
                            'pay_time' => time(),
                            'ordernum'=>"$trade_no",
                        ));
                }*/
                $list = $amodel->where(array('ordernum' => "$trade_no"))->find();
                if ($list) {
                    $amodel->where(array('ordernum' => "$trade_no"))
                        ->save(array(
                            //'ordernum'=>$trade_no,
                            'is_pay' => '1',
                            'pay_time' => time(),
                            'trade_id' => "$trade_id"
                        ));
                } else {
                    $activeid = $omodel->where(array('ordernum' => "$trade_no"))->getField('active_id');
                    $amodel->where(array('id' => $activeid))->setInc('join_num', 1);
                    //S('wo',$amodel->getLastSql());
                    $omodel->where(array('ordernum' => "$trade_no"))
                        ->save(array(
                            'pay_status' => '1',
                            'trade_id' => "$trade_id",
                            'pay_time' => time(),
                            //'ordernum'=>$trade_no,
                        ));
                }
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【支付成功】:\n" . $xml . "\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }

    //移动h5支付
    public function h_pay()
    {
        import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $unifiedOrder = new \UnifiedOrder_pub();
        $unifiedOrder->setParameter("body", "fsdf");//商品描述
        $unifiedOrder->setParameter("total_fee", '1');//总金额
        $timeStamp = time();
        $out_trade_no = \WxPayConf_pub::APPID . "$timeStamp";
        $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");
        $unifiedOrder->setParameter("trade_type", "MWEB");//交易类型
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("scene_info", '{"h5_info": {"type":"IOS","app_name": "王者荣耀","bundle_id": "pai.qmchina.net"}}');//交易类型
        $data = $unifiedOrder->getPrepayId2($url);
        dump($data);
        die;
    }

    //发送红包
    public function hb()
    {
        import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $unifiedOrder = new \UnifiedOrder_pub();
        $unifiedOrder->setParameter("mch_billno", '10000098201411111234567890');//
        $unifiedOrder->setParameter("send_name", '10000098201411111234567890');//
        $unifiedOrder->setParameter("re_openid", 'o8wkZ0T68cU8AjdqcbfpYZYaV1eI');//
        $unifiedOrder->setParameter("total_amount", '1');//总金额
        $unifiedOrder->setParameter("total_num", '1');//总金额
        $timeStamp = time();
        //$out_trade_no = \WxPayConf_pub::APPID . "$timeStamp";
        //$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");
        $unifiedOrder->setParameter("wishing", '号');
        $unifiedOrder->setParameter("act_name", '1fsdf');
        $unifiedOrder->setParameter("remark", '12');
        $unifiedOrder->setParameter("scene_id", 'PRODUCT_1');
        $data = $unifiedOrder->getPrepayId1($url);
        dump($data);die;


    }

    //get获取
    public function getData1($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function postData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }

    /**
     * 发布活动支付
     */
    public function pub_weipay()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $sk = $this->get_session($getinfo['session_id']);
        $openid = $sk['openid'];
        $model = M("Active");
        $list = $model->find($getinfo['id']);
        //echo json_encode($getinfo);
        //echo json_encode($list);die;
        if (empty($list)) {
            echo json_encode(array('msg' => '你查询的数据不存在', 'status' => 'error'));
            die;
        } else {
            if ($list['is_pay'] == '0') {
                $active_name = $list['tname'];
                $activeid = $list['id'];
                //$ordernum=$list['ordernum'];
                $price = $list['number'] * $list['price'] * 100;
                if($list['price']==0){
                    $model->where(array('id' => $getinfo['id']))
                        ->save(array(
                            'is_pay' => '1',
                            'pay_time' => time(),
                        ));
                    echo json_encode(array('msg' => '支付成功！', 'status' => 'success'));
                    //S('wo',$amodel->getLastSql());
                    die;
                }
                import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
                //使用jsapi接口
                $jsApi = new \JsApi_pub();
                $unifiedOrder = new \UnifiedOrder_pub();
                //设置统一支付接口参数
                //设置必填参数
                //appid已填,商户无需重复填写
                //mch_id已填,商户无需重复填写
                //noncestr已填,商户无需重复填写
                //spbill_create_ip已填,商户无需重复填写
                //sign已填,商户无需重复填写
                $unifiedOrder->setParameter("openid", "$openid");//商品描述
                $unifiedOrder->setParameter("body", "$active_name");//商品描述
                //自定义订单号，此处仅作举例
                $timeStamp = time();
                $out_trade_no = \WxPayConf_pub::APPID . "$timeStamp";
                //fsfsdfdsds
                //fsfsdfdsds12
                $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
                $model->where(array('id' => $getinfo['id']))->setField('ordernum', $out_trade_no);
                $total_fee = $price;
                $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
                $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
                $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
                //非必填参数，商户可根据实际情况选填
                //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
                //$unifiedOrder->setParameter("device_info","XXXX");//设备号
                //$unifiedOrder->setParameter("attach","XXXX");//附加数据
                //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
                //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
                $unifiedOrder->setParameter("goods_tag", "active");//商品标记
                //$unifiedOrder->setParameter("openid","XXXX");//用户标识
                $unifiedOrder->setParameter("product_id", "$activeid");//商品ID
                $prepay_id = $unifiedOrder->getPrepayId();
                //dump($prepay_id);die;
                //=========步骤3：使用jsapi调起支付============
                $jsApi->setPrepayId($prepay_id);
                $jsApiParameters = $jsApi->getParameters();
                //dump($jsApiParameters);die;
                //$this->jsApiParameters=$jsApiParameters;
                //dump($jsApiParameters);die;
                //dump(json_decode($jsApiParameters));die;
                //$this->display();
                echo $jsApiParameters;
            } elseif ($list['is_pay'] == '1') {
                echo json_encode(array('msg' => '你已经支付过了', 'status' => 'error'));
                die;
            } elseif ($list['is_pay'] == '2') {
                echo json_encode(array('msg' => '退款中', 'status' => 'error'));
                die;
            } elseif ($list['is_pay'] == '3') {
                echo json_encode(array('msg' => '你已经退款了', 'status' => 'error'));
                die;
            } elseif ($list['is_pay'] == '4') {
                echo json_encode(array('msg' => '该活动已经取消了', 'status' => 'error'));
                die;
            } elseif ($list['is_pay'] == '5') {
                echo json_encode(array('msg' => '其他原因，已退款', 'status' => 'error'));
                die;
            } elseif ($list['is_pay'] == '6') {
                echo json_encode(array('msg' => '未退款', 'status' => 'error'));
                die;
            }
        }
    }

    /**
     *参与活动支付
     */
    public function join_weipay()
    {
        $mydata = file_get_contents('php://input');
        $getinfo = json_decode($mydata, true);
        $sk = $this->get_session($getinfo['session_id']);
        $openid = $sk['openid'];
        $model = M("Order");
        $amodel = M("Active");
        $list = $model->find($getinfo['id']);
        //echo json_encode($getinfo);
        //echo json_encode($getinfo);die;
        if (empty($list)) {
            echo json_encode(array('msg' => '你查询的数据不存在', 'status' => 'error'));die;
        } else {
            //支付的有效时间控---该需求还未碰
            $alist = $amodel->find($list['active_id']);
            if ($alist['is_pay'] == '4') {
                echo json_encode(array('msg' => '活动已取消！', 'status' => 'error'));
                die;
            } else {
                if ($list['pay_status'] == '0') {
                    if($list['active_price']==0){
                        echo json_encode(array('msg' => '支付成功！', 'status' => 'success'));
                        //S('wo',$amodel->getLastSql());
                        $model->where(array('id' => $getinfo['id']))
                            ->save(array(
                                'pay_status' => '1',
                                'pay_time' => time(),
                            ));
                        $amodel->where(array('id' => $list['active_id']))->setInc('join_num', 1);
                        die;
                    }
                    $active_name = M('Active')->where(array('id' => $list['active_id']))->getField('tname');
                    //$ordernum=$list['ordernum'];
                    $price = $alist['price'] * 100;
                    $oderid = $getinfo['id'];
                    import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
                    //使用jsapi接口
                    $jsApi = new \JsApi_pub();
                    $unifiedOrder = new \UnifiedOrder_pub();
                    //设置统一支付接口参数
                    //设置必填参数
                    //appid已填,商户无需重复填写
                    //mch_id已填,商户无需重复填写
                    //noncestr已填,商户无需重复填写
                    //spbill_create_ip已填,商户无需重复填写
                    //sign已填,商户无需重复填写
                    $unifiedOrder->setParameter("openid", "$openid");//商品描述
                    $unifiedOrder->setParameter("body", "$active_name");//商品描述
                    //自定义订单号，此处仅作举例
                    $timeStamp = time();
                    $out_trade_no = \WxPayConf_pub::APPID . "$timeStamp";
                    //fsfsdfdsds
                    //fsfsdfdsds12
                    $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
                    $model->where(array('id' => $getinfo['id']))->setField('ordernum', $out_trade_no);
                    $total_fee = $price;
                    $unifiedOrder->setParameter("total_fee", $total_fee);//总金额
                    $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL);//通知地址
                    $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
                    //非必填参数，商户可根据实际情况选填
                    //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
                    //$unifiedOrder->setParameter("device_info","XXXX");//设备号
                    //$unifiedOrder->setParameter("attach","XXXX");//附加数据
                    //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
                    //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
                    $unifiedOrder->setParameter("goods_tag", "order");//商品标记
                    //$unifiedOrder->setParameter("openid","XXXX");//用户标识
                    $unifiedOrder->setParameter("product_id", "$oderid");//商品ID
                    $prepay_id = $unifiedOrder->getPrepayId();
                    //dump($prepay_id);die;
                    //=========步骤3：使用jsapi调起支付============
                    $jsApi->setPrepayId($prepay_id);
                    $jsApiParameters = $jsApi->getParameters();
                    //dump($jsApiParameters);die;
                    //$this->jsApiParameters=$jsApiParameters;
                    //dump($jsApiParameters);die;
                    //dump(json_decode($jsApiParameters));die;
                    //$this->display();
                    echo $jsApiParameters;
                } else {
                    echo json_encode(array('msg' => '你已经支付过了', 'status' => 'error'));
                    die;
                }
            }
        }
    }


    /**
     * @param $id
     * 发布活动支付
     */
    public function my_publish_active_pay()
    {
        $id = I('get.id');
        $model = M('Active');
        $list = $model->field('is_pay,number,price,vcode,phone,id')->find($id);
        $price = $list['number'] * $list['price'];
        $list['total_price'] = $price;
        echo json_encode(array('msg' => "支付成功", 'status' => '1000', 'data' => $list));
        /*if($list['is_pay']=='1'){
            echo json_encode(array('msg'=>"你已经支付过了！",'status'=>'4001','data'=>$list));
        }elseif($list['is_pay']=='2'){
            echo json_encode(array('msg'=>"你退款申请中！",'status'=>'4002','data'=>$list));
        }elseif($list['is_pay']=='3'){
            echo json_encode(array('msg'=>"你已退款了！",'status'=>'4003','data'=>$list));
        }elseif($list['is_pay']=='0'){
            //$ordernum=C('PRE_NO').$id;
            $model->where(array('id'=>$id))->save(array(
                'is_pay'=>'1',
            ));
            $model->where(array('id'=>$id))->setInc('join_num',1);
            $data=$model->field('is_pay,vcode,phone')->find($id);
            $data['total_price']=$price;
            echo json_encode(array('msg'=>"支付成功！",'status'=>'1000','data'=>$data));
        }*/
    }

    /**
     * @param $id
     * 参与活动支付
     */
    public function my_join_active_pay()
    {
        $id = I('get.id');
        $model = M('Order');
        $list = $model->alias('a')->
        field('a.pay_status is_pay,a.active_price price,b.vcode,b.phone,b.is_cancel,a.active_id,a.id')->
        join('left join __ACTIVE__ b on a.active_id=b.id')->
        where(array('a.id' => $id))->find();
        $list['total_price'] = $list['price'];
        echo json_encode(array('msg' => "支付成功！", 'status' => '4001', 'data' => $list));
        //echo json_encode($list);die;
        /*if($list['is_cancel']=='0'){
            echo json_encode(array('msg'=>"该活动已经取消了！",'status'=>'4004','data'=>$list));die;
        }
        $list['total_price']=$list['price'];
        if($list['is_pay']=='1'){
            echo json_encode(array('msg'=>"你已经支付过了！",'status'=>'4001','data'=>$list));
        }elseif($list['is_pay']=='2'){
            echo json_encode(array('msg'=>"你退款申请中！",'status'=>'4002','data'=>$list));
        }elseif($list['is_pay']=='3'){
            echo json_encode(array('msg'=>"你已退款了！",'status'=>'4003','data'=>$list));
        }elseif($list['is_pay']=='0'){
            $model->where(array('id'=>$id))->
            save(array('pay_status'=>'1'));
            $data=$model->alias('a')->
            field('a.pay_status is_pay,a.active_price price,b.vcode,b.phone')->
            join('left join __ACTIVE__ b on a.active_id=b.id')->
            where(array('a.id'=>$id))->find();
            $data['total_price']=$list['price'];
            echo json_encode(array('msg'=>"支付成功！",'status'=>'1000','data'=>$data));
        }*/
    }


    //发布活动未开始的退款
    public function pub_refund($model, $out_trade_no, $refund_fee = '1', $type = '4')
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
            //$refund_fee = '1';
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
            if ($refundResult["return_code"] == "SUCCESS" && $refundResult["result_code"]=='SUCCESS') {
                //发布活动
                $amodel = M('Active');
                $omodel = M('Order');
                if ($model == 'active') {
                    $amodel->where(array('ordernum' => $out_trade_no))->setField('is_pay', $type);
                } elseif ($model == 'order') {
                    $omodel->where(array('ordernum' => $out_trade_no))->setField('pay_status', $type);
                }
            }
            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($refundResult["return_code"] == "FAIL" || $refundResult["return_code"] == "FAIL") {
                $result['msg'] = "通信出错：" . $refundResult['return_msg'];
                $result['status'] = false;
                return $result;
            } else {
                $result['msg'] = "退款成功";
                $result['status'] = true;
                return $result;
            }
        }
    }
    public function pub_refund2()
    {
        import("Home.WxPayPubHelper.WxPayPubHelper", APP_PATH, ".php");
        //输入需退款的订单号
        $out_trade_no = "wxc767748b8c5bfa4b1492410395";
        $refund_fee = '1';
        if (!isset($out_trade_no) || !isset($refund_fee)) {
            $out_trade_no = " ";
            $refund_fee = "1";
        } else {
            $time_stamp = uniqid() . time();
            //商户退款单号，商户自定义，此处仅作举例
            $out_refund_no = "$out_trade_no" . "$time_stamp";
            //总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
            $total_fee = $refund_fee;

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
            /* if($refundResult["return_code"] == "SUCCESS"){
                 //发布活动
                 if($model=='active'){
                     $amodel=M('Active');
                     $amodel->where(array('ordernum'=>$out_trade_no))->setField('is_pay','3');
                 }elseif($model=='order'){
                     $amodel=M('Order');
                     $amodel->where(array('ordernum'=>$out_trade_no))->setField('pay_status','3');
                 }
             }*/
            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($refundResult["return_code"] == "FAIL") {
                echo "通信出错：" . $refundResult['return_msg'] . "<br>";
            } else {
                echo "业务结果：" . $refundResult['result_code'] . "<br>";
                echo "错误代码：" . $refundResult['err_code'] . "<br>";
                echo "错误代码描述：" . $refundResult['err_code_des'] . "<br>";
                echo "公众账号ID：" . $refundResult['appid'] . "<br>";
                echo "商户号：" . $refundResult['mch_id'] . "<br>";
                echo "子商户号：" . $refundResult['sub_mch_id'] . "<br>";
                echo "设备号：" . $refundResult['device_info'] . "<br>";
                echo "签名：" . $refundResult['sign'] . "<br>";
                echo "微信订单号：" . $refundResult['transaction_id'] . "<br>";
                echo "商户订单号：" . $refundResult['out_trade_no'] . "<br>";
                echo "商户退款单号：" . $refundResult['out_refund_no'] . "<br>";
                echo "微信退款单号：" . $refundResult['refund_idrefund_id'] . "<br>";
                echo "退款渠道：" . $refundResult['refund_channel'] . "<br>";
                echo "退款金额：" . $refundResult['refund_fee'] . "<br>";
                echo "现金券退款金额：" . $refund;
            }
        }
    }
}