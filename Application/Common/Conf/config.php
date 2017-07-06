<?php
return array(
    //'SHOW_PAGE_TRACE'=>TRUE,
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'oho', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'oho123', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'beidou_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'GONGSINAME'=>'临泽中产创意创新科技有限公司',
	'DEFAULT_MODULE' => 'Home',
	'MODULE_ALLOW_LIST' => array('Home','Admin'),
	/************** 发邮件的配置 ***************/
	'MAIL_ADDRESS' => '18233305970@163.com',   // 发货人的email
	'MAIL_FROM' => '18233305970',      // 发货人姓名
	'MAIL_SMTP' => 'smtp.163.com',      // 邮件服务器的地址
	'MAIL_LOGINNAME' => '18233305970',
	'MAIL_PASSWORD' => 'dengbo513021',
	//
	//'TAGLIB_BUILD_IN'        => 'Cx,Common\Tag\My',              // 加载自定义标签
    //'ORDER_EXPIRE_TIME'=>360,//订单的有效时间


    /* 自动运行配置 */
    /*'CRON_CONFIG_ON' => true, // 是否开启自动运行
    'CRON_CONFIG' => array(
        '测试定时任务' => array('Home/Index/index','0', ''), //路径(格式同R)、间隔秒（0为一直运行）、指定一个开始时间
    ),*/

);