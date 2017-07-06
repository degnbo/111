<?php
/**
* 	配置账号信息
*/
//echo __DIR__;die;
//echo getcwd();die;
//dump($_SERVER);die;
class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	//const APPID = 'wx225b3bbb6a90fdf5';
	const APPID = 'wxc767748b8c5bfa4b';
	//受理商ID，身份标识
	//const MCHID = '1303436201';
	const MCHID = '1460822502';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'oxygenhoop20161122ZZSmxlSJNzb111';
	//const KEY = 'ZUOmananhongqwertyuiopqwertyuiop';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	//const APPSECRET = '48888888888888888888888888888887';
	const APPSECRET = '16be8797f2b3b7b3bd968416346dcbd8';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'https://oho.oxygenhoop.com/index.php/Home/Index/s_weipay/';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH ='/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = '/cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'https://oho.oxygenhoop.com/index.php/Home/Index/notify_url';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>