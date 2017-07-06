<?php
function bian(&$pa) {
    if(!mb_check_encoding($pa, 'utf-8')){
        $pa = iconv('gbk', 'utf-8', $pa);
    }
    return $pa;
}
function time_format ($time) {
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}
//上午或下午
function get_sxtime($time){
    $t=date("a",$time);
    $sj='';
    if($t=='am'){
        $sj= "上午".date("H:i",$time);
    }else{
        $sj= "下午".date("H:i",$time);
    }
    return $sj;
}
//星期日
function get_xqtime($time){
    $sz=date("w",$time);
    $rq=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
    return $rq[$sz];
}
//微信登录curl请求
function getCurl($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    $data = curl_exec($curl);
    curl_close($curl);
    //echo $data;
    $arr=json_decode($data);
    $arr=(array)$arr;
    return $arr;
}
function WSevent($type,$event){
    global $websocket;
    if('in'==$type){
        $websocket->log('客户进入id:'.$event['k']);
    }elseif('out'==$type){
        $websocket->log('客户退出id:'.$event['k']);
    }elseif('msg'==$type){
        $websocket->log($event['k'].'消息:'.$event['msg']);
        roboot($event['sign'],$event['msg']);
    }
}

function roboot($sign,$t){
    global $websocket;
    switch ($t)
    {
        case 'hello':
            $show='hello,GIt @ OSC';
            break;
        case 'name':
            $show='Robot';
            break;
        case 'time':
            $show='当前时间:'.date('Y-m-d H:i:s');
            break;
        case '再见':
            $show='( ^_^ )/~~拜拜';
            $websocket->write($sign,'Robot:'.$show);
            $websocket->close($sign);
            return;
            break;
        case '天王盖地虎':
            $array = array('小鸡炖蘑菇','宝塔震河妖','粒粒皆辛苦');
            $show = $array[rand(0,2)];
            break;
        default:
            $show='( ⊙o⊙?)不懂,你可以尝试说:hello,name,time,再见,天王盖地虎.';
    }
    $websocket->write($sign,'Robot:'.$show);
}


?>