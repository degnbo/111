<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title></title>
    <meta name="keywords" content="北斗">
    <meta name="description" content="北斗">

    <link href="/Public/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">

    <link href="/Public/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.css?v=2.2.0" rel="stylesheet">
    <style>
        .gray-bg{
            margin-top:150px;
        }
        .middle-box h1 {
            font-size: 40px;
        }
    </style>

</head>

<body class="gray-bg">


<div class="middle-box text-center animated fadeInDown">
    <h1><?php echo($message); ?></h1>
    <h3 class="font-bold"><?php echo($error); ?></h3>

    <div class="error-desc">
       页面跳转中<a id='href' href="<?php echo($jumpUrl); ?>">跳转</a>
        等待时间： <b id="wait"><?php echo($waitSecond); ?></b>...
        <br/>您可以返回主页看看
        <br/><a href="<?php echo U('Index/index');?>" class="btn btn-primary m-t">主页</a>
    </div>
</div>

<!-- Mainly scripts -->
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>

</html>