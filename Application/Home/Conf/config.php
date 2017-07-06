<?php
$SITE_URL = "http://zyqs.qmchina.com/";
define('URL_CALLBACK', "" . $SITE_URL . "Login/callback/type/");
return array(
    //'SHOW_PAGE_TRACE'=>true,//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'oho', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'oho123', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'beidou_', // 数据库表前缀
    'URL_MODEL' => 1,
    'DB_CHARSET'=> 'utf8', // 字符集
    'ORDER_EXPIRE_TIME'=>1800,//订单的有效时间
	'LAYOUT_ON'=>true,
	'LAYOUT_NAME'=>'Layout/layout',
	'TMPL_ACTION_ERROR' => 'Layout:sb',
	//默认成功跳转对应的模板文件
	'TMPL_ACTION_SUCCESS' => 'Layout:cg',
	'reg_success_email_title' => '界拓潜水会员注册', // 注册成功之后邮件的标题
	'email_chk_expire' => 1800,   // email验证过期时间，30分钟
	//
	'DX_EXPIRE_TIME'=>6000,//短信的有效时间
	'GONGSI_NAME'=>'界拓网',//公司名称
	//'配置项'=>'配置值'
	/*'TMPL_PARSE_STRING'  =>array(
		//'__PUBLIC__' => '/Common',     // 更改默认的/Public 替换规则
		//'__UPLOAD__' => '/Uploads',    // 增加新的上传路径替换规则
		'__IMG__'=>__ROOT__.'/Public/Home/Images/',
		'__CSS__'=>__ROOT__.'/Public/Home/Style/',
		'__JS__' =>__ROOT__.'/Public/Home/Js/',
	),

	'TMPL_ACTION_SUCCESS'   =>  "Public:success", // 默认成功跳转对应的模板文件
	'TMPL_ACTION_ERROR'     =>  "Public:error",   // 默认错误跳转对应的模板文件*/

	//腾讯QQ登录配置
	'THINK_SDK_QQ' => array(
		'APP_KEY' => '101289802', //应用注册成功后分配的 APP ID
		'APP_SECRET' => '3b9a6b28e755b745423cceae9d9ea41e', //应用注册成功后分配的KEY
		'CALLBACK' => URL_CALLBACK . 'qq',
	),

	//新浪微博登录配置
	'THINK_SDK_SINA' => array(
		'APP_KEY' => '2276426246', //应用注册成功后分配的 APP ID
		'APP_SECRET' => '9f3940048f388f44dff19a3d68736a53', //应用注册成功后分配的KEY
		'CALLBACK' => URL_CALLBACK . 'sina',
	),

);
