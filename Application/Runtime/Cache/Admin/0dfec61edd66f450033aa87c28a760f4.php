<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!-- saved from url=(0043)http://yiicms.co/backend/default/login.html -->
<html lang="zh-CN" style="height: auto;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-param" content="_csrf">
    <meta name="csrf-token" content="SG1vRG0tZEU8PQRpOWEVaAwPOjUIQxw3JD1ZBlhBCmgvVCgXPGYPKg==">
        <title>OHO后台登录</title>
        <link href="/Public/Admin/login/bootstrap.css" rel="stylesheet">
<link href="/Public/Admin/login/font-awesome.min.css" rel="stylesheet">
<link href="/Public/Admin/login/AdminLTE.min.css" rel="stylesheet">
<link href="/Public/Admin/login/_all-skins.min.css" rel="stylesheet">
<link href="/Public/Admin/login/jquery-ui.css" rel="stylesheet">    </head>
<link type="text/css" rel="stylesheet" href="/Public/Admin/css/Validform2.css" />
<link type="text/css" rel="stylesheet" href="/Public/Admin/alert/pop_status.css" />
<script type="text/javascript" src="/Public/Home/zdy/jquery.js"></script>
<script type="text/javascript" src="/Public/Admin/alert/pop_status.js"></script>
<script type="text/javascript" src="/Public/Home/zdy/Validform.js"></script>
<style>
    .Validform_right{
        display:none;
    }
</style>
    <body class="login-page" style="height: auto;">
    <div class="wrap">
        <div class="container">
            <div class="site-login login-box">
    <div class="login-logo">
        <a href="http://yiicms.co/backend/default/login.html#"><b>OHO后台登录</b></a>
    </div>
    <div class="login-box-body">

        <form id="login-form" action="<?php echo U('Login/login');?>" method="post" role="form">
        <div class="form-group has-feedback field-loginform-username required">

<input type="text" id="loginform-username" class="form-control"
       datatype="*1-20" nullmsg="用户名不能为空用" name="username"
       errermsg="用户名太长" ajaxurl="<?php echo U('Login/checkUser');?>"
       autofocus="" aria-required="true">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

<p class="help-block help-block-error"></p>
</div>
        <div class="form-group has-feedback field-loginform-password required">

<input type="password" id="loginform-password"
       nullmsg="密码不能为空" errormsg="6-16位密码" datatype="*6-16"
       class="form-control" name="password" aria-required="true">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

<p class="help-block help-block-error"></p>
</div>
        <div class="row">
            <div class="col-xs-8">
                <div class="form-group field-loginform-rememberme">
<div class="checkbox">
<label for="loginform-rememberme">
<input type="hidden" name="LoginForm[rememberMe]" value="0">
    <input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked="">
自动登录
</label>
<p class="help-block help-block-error"></p>

</div>
</div>            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat" name="login-button">登录</button>            </div>
            <!-- /.col -->
        </div>
        </form>
        <script>
            //alert(1);
            $("#login-form").Validform({
                //ajaxPost:true,
                tiptype: function (msg, o, cssctl) {
                    if (!o.obj.is("form")) {
                        var objtip = o.obj.next().next('p');
                        cssctl(objtip, o.type);
                        objtip.text(msg);
                    }
                },
                //postonce:true,
                ajaxPost:true,
                callback:function(data){
                    if(data.status=="y"){
                        popStatus(1, data.info, 2,"/index.php/Admin/Index/index.html",true);
                    }else{
                        popStatus(4, data.info, 2,'',true);
                    }
                }
            })
        </script>
        <div class="social-auth-links">
            小灏科技 <strong>Welcome...</strong>.<br>
        </div>
    </div>
</div>
        </div>
    </div>

    
</body></html>