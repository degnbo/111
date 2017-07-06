<?php
/*
 * 日志类
 * 每天生成一个日志文件，当文件超过指定大小则备份日志文件并重新生成新的日志文件
*/
class Log {

    private $maxsize = 1024000; //最大文件大小1M

    //写入日志
    public function writeLog($filename,$msg){
        $res = array();
        $res['msg'] = $msg;
        $res['logtime'] = date("Y-m-d H:i:s",time());
        $res['logIp']=get_client_ip();
        if(!file_exists(dirname($filename))){
            mkdir(dirname($filename),0777,true);
        }
        //如果日志文件超过了指定大小则备份日志文件
        if(file_exists($filename) && (abs(filesize($filename)) > $this->maxsize)){
            $newfilename = dirname($filename).'/'.time().'-'.basename($filename);
            rename($filename, $newfilename);
        }
        $ml=pathinfo($filename);
        //一分钟内连续操作只记录一次
        $flag=false;
        $list = file_get_contents($filename);
        $list=json_decode('[' . $list . ']',true);
        //dump($list);die;
        foreach($list as $k=>$v){
            //echo strtotime($v['logtime']);
            if((time()-strtotime($v['logtime']))<120 && $v['msg']['name']==$msg['name'] && $v['msg']['model']==$msg['model'] && $v['msg']['action']==$msg['action']){
                $flag=true;
                break;
            }
        }
        if(!$flag){
            //如果是新建的日志文件，去掉内容中的第一个字符逗号
            if(file_exists($filename) && abs(filesize($filename))>0){
                $content = ",".json_encode($res);
            }else{
                $content = json_encode($res);
            }

            //往日志文件内容后面追加日志内容
            file_put_contents($filename, $content, FILE_APPEND);
        }
    }


    //读取日志
    public function readLog($filename)
    {
        if (file_exists($filename)) {
            //if("./Publc/jour/".)
            $content = file_get_contents($filename);
            $json = json_decode('[' . $content . ']', true);
        } else {
            $json = '{"msg":"The file does not exist."}';
        }
        return $json;
    }
    /*$filename = "logs/log_".date("Ymd",time()).".txt";
    $msg = '写入了日志';
    $Log = new Log(); //实例化
    $Log->writeLog($filename,$msg); //写入日志
    $loglist = $Log->readLog($filename); //读取日志 */
}
?>
