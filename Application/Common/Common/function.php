<?php
function filter_name($str){
    import('Home.dealCard.class#preg',APP_PATH,".php");
    $preg = new \preg($str);
    return $preg -> str();
}
function sendMail($to, $title, $content)
{
    require_once('./phpmailer/class.phpmailer.php');
    $mail = new PHPMailer();
    // 设置为要发邮件
    $mail->IsSMTP();
    // 是否允许发送HTML代码做为邮件的内容
    $mail->IsHTML(TRUE);
    $mail->CharSet='UTF-8';
    // 是否需要身份验证
    $mail->SMTPAuth=TRUE;
    /*  邮件服务器上的账号是什么 -> 到163注册一个账号即可 */
    $mail->From=C('MAIL_ADDRESS');
    $mail->FromName=C('MAIL_FROM');
    $mail->Host=C('MAIL_SMTP');
    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');
    // 发邮件端口号默认25
    $mail->Port = 25;
    // 收件人
    $mail->AddAddress($to);
    // 邮件标题
    $mail->Subject=$title;
    // 邮件内容
    $mail->Body=$content;
    return($mail->Send());
}

function deldir($directory){
    //echo 1;die;
    if(is_dir($directory)) {
    //@防止爆出警告
        if($dir_handle=@opendir($directory)) {
            while(false!==($filename=readdir($dir_handle))) {
                $file=$directory."/".$filename;
                if($filename!="." && $filename!="..") {
                    if(is_dir($file)) {
                        deldir($file);
                    } else {
                        unlink($file);
                    }
                }
            }
            closedir($dir_handle);
        }
        rmdir($directory);
    }
}
function build_order_no($name="Oh"){
    return $name.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
/**
 * 汉字转拼音
 * @param string $str 待转换的字符串
 * @param string $charset 字符串编码
 * @param bool $ishead 是否只提取首字母
 * @return string 返回结果
 */
function GetPinyin($str,$charset="utf-8",$ishead = 0) {
    $restr = '';
    $str = trim($str);
    if($charset=="utf-8"){
        $str=iconv("utf-8","gb2312",$str);
    }

    $slen = strlen($str);
    $pinyins=array();
    if ($slen < 2) {
        return $str;
    }
    $fp = fopen('pinyin.dat', 'r');
    while (!feof($fp)) {
        $line = trim(fgets($fp));
        $pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
    }
    fclose($fp);

    for ($i = 0; $i < $slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i + 1];
            $i++;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= "_";
            }
        } else if (preg_match("/[a-z0-9]/i", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= "_";
        }
    }
    return $restr;
}
//文件大小自动转换
 function format_bytes($size) {
     $units = array(' B', ' KB', ' MB', ' GB', ' TB');
     for ($i = 0; $size >= 1024 && $i < 4; $i++) {
         $size /= 1024;
     }
     return round($size, 2).$units[$i];
 }
function p($data){
// 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}


/**
 * 上传文件类型控制 此方法仅限ajax上传使用
 * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string   $format  文件格式限制
 * @param  integer  $maxSize 允许的上传文件最大值 52428800
 * @return booler   返回ajax的json格式数据
 */
function ajax_upload($path='file',$format='empty',$maxSize='52428800')
{
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path = trim($path, '/');
    // 添加Upload根目录
    $path = strtolower(substr($path, 0, 6)) === 'upload' ? ucfirst($path) : 'Upload/' . $path;
    // 上传文件类型控制
    $ext_arr = array(
        'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        'photo' => array('jpg', 'jpeg', 'png'),
        'flash' => array('swf', 'flv'),
        'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
        'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', 'pdf')
    );
    if (!empty($_FILES)) {
        // 上传文件配置
        $config = array(
            'maxSize' => $maxSize,               // 上传文件最大为50M
            'rootPath' => './',                   // 文件上传保存的根路径
            'savePath' => './' . $path . '/',         // 文件上传的保存路径（相对于根路径）
            'saveName' => array('uniqid', ''),     // 上传文件的保存规则，支持数组和字符串方式定义
            'autoSub' => true,                   // 自动使用子目录保存上传文件 默认为true
            'exts' => isset($ext_arr[$format]) ? $ext_arr[$format] : '',
        );
        // p($_FILES);
        // 实例化上传
        $upload = new \Think\Upload($config);
        // 调用上传方法
        $info = $upload->upload();
        // p($info);
        $data = array();
        if (!$info) {
            // 返回错误信息
            $error = $upload->getError();
            $data['error_info'] = $error;
            echo json_encode($data);die;
        } else {
            // 返回成功信息
            foreach ($info as $file) {
                $data['name'] = trim($file['savepath'] . $file['savename'], '.');
                // p($data);
                echo json_encode($data);die;
            }
        }
    }
    /**
     * 过滤字符串中危险的代码
     */
    function removeXSS($string)
    {
        /**
         * 创建默认配置文件
         * 设置不过滤的规则
         * 使用这个规则生成过滤对象
         * 使用对象过滤数据
         */
        require_once './Htmlpurifier/HTMLPurifier.auto.php';
        // 生成配置对象
        $_clean_xss_config = HTMLPurifier_Config::createDefault();
        // 以下就是配置：
        $_clean_xss_config->set('Core.Encoding', 'UTF-8');
        // 设置允许使用的HTML标签
        $_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
        // 设置允许出现的CSS样式属性
        $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置a标签上是否允许使用target="_blank"
        $_clean_xss_config->set('HTML.TargetBlank', TRUE);
        // 使用配置生成过滤用的对象
        $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
        // 过滤字符串
        return $_clean_xss_obj->purify($string);
    }
    function getTree($model_name="Ctype"){
        $model=M($model_name);
        $data=$model->select();
        return resort($data);
    }
    function resort($data,$pid=0,$level=0,$clear=true){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach($data as $key=>$val){
            if($val['pid']==$pid){
                $val['level']=$level;
                $arr[]=$val;
                resort($data,$val['id'],$level+1,false);
            }
        }
        return $arr;
    }
    function get_client_ip() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                    $ip = getenv("REMOTE_ADDR");
                else
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                        $ip = $_SERVER['REMOTE_ADDR'];
                    else
                        $ip = "unknown";
        return ($ip);
    }
}
?>