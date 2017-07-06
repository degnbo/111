<?php
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
function bian(&$pa) {
	 if(!mb_check_encoding($pa, 'utf-8')){
		$pa = iconv('gbk', 'utf-8', $pa);
	}
	return $pa;
}
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
function getChildren($id,$model_name="Ctype"){
	$data=M($model_name)->field('id,pid')->select();
	//return $data;
	$list=myChildren($data,$id);
	//这里这个true与false表示清空刚才那个
	return $list;
}
function myChildren($data,$id,$clear=true)
{
	static $arr =array();
	if ($clear) {
		$arr =array();
	}
	foreach ($data as $val) {
		if ($val['pid'] == $id) {
			$arr[] = $val['id'];
			myChildren($data, $val['id'], false);
		}
	}
	return $arr;
}
//写入日志
function tp_log($msg){
	$filename = "./Public/jour/".date("Y-m-d",time()).".log";
	import('Admin.Log.Log');
	$log=new \Log();
	$log->writeLog($filename,$msg);
}


?>