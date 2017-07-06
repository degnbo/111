<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>oho后台管理</title>
    <meta name="keywords" content="界拓自由潜水">
    <meta name="description" content="界拓自由潜水">
    <link href="/Public/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/Public/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.css?v=2.2.0" rel="stylesheet">
    <!-- 表单插件 -->
    <link type="text/css" rel="stylesheet" href="/Public/Admin/css/Validform1.css" />
    <!-- Data Tables -->
    <link href="/Public/Admin/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <script src="/Public/Admin/js/jquery-2.1.1.min.js"></script>
    <style>
        .ede{
            width:20px;height:20px;
            background:red;
        }
        .num{
            padding:5px 8px;background:white;border:1px solid #ccc;
            width:25px;height:5px;margin:12px;
        }
        .current{
            padding:5px 8px;background:lightseagreen;
            width:25px;height:5px;margin:8px;
        }
        .prev,.next{
            padding:5px 8px;background:white;border:1px solid #ccc;
            width:20px;height:5px;margin:8px;
        }
		.first,.end{
            padding:5px 8px;background:white;border:1px solid #ccc;
            width:20px;height:5px;margin:8px;
        }
        .jz{
            text-align: center;
        }
    .nav.nav-second-level{
    display:none;
    }
    </style>

<script>
$(function(){
$('.nav-label').click(function(){
$(this).parent().next().stop().slideToggle();

})

})
</script>
</head>
<?php
$model=D('Admin'); $id=I('session.admin_id'); $navlist=$model->getNav($id); $logo=$model->where(array('id'=>$id))->find(); ?>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">

                        <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="/Public/Uploads/<?php echo ($logo["logo"]); ?>" width="80px" height="80px"/>
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo I('session.admin_us');?></strong>
                             </span>  <span class="text-muted text-xs block">欢迎登录 <b class="caret"></b></span> </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="javascript:void(0)" id='xg'>修改头像</a>
                                </li>
								<li style='display:none'>
								<form method='post' enctype="multipart/form-data" id='bd' action="<?php echo U('Index/xgtx');?>">
								<input type='file' name='myfile'  id='sc'/>
								</form>	
                                 </li>								
                              <script>
							      $('#xg').click(function(){
								       $('#sc').click();
								  })
								  $('#sc').change(function(){
								       $('#bd').submit();
								  })
                                  var ww='http://web.oho.com/index.php/Home/Index/search?keyword=12&lat=39.90403&lng=116.407526';
							  </script>
                                <li><a href="<?php echo U('Login/logout');?>">安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            H+
                        </div>

                    </li>
                    <li class='show'>
                        <a href="<?php echo U('Index/index');?>" ><i class="fa fa-envelope"></i>
                        <span class="nav-label">后台首页</span>
                       </a>
                    </li>
					<li class='show'>
                        <a href="<?php echo U('Home/Index/index');?>" target="_blank"><i class="fa fa-envelope"></i>
                        <span class="nav-label">前台首页</span>
                       </a>
                    </li>
                    <?php foreach($navlist as $nav){?>
                     <li class='show'>
                        <a href="javascript:void(0)" >
                        <i class="fa fa-envelope">
                        </i> <span class="nav-label">
                        <?php echo $nav['pname']?></span>
                        </a>
                        <ul class="nav nav-second-level">
                       <?php foreach($nav['chilren'] as $vv){?>
                        <li><a href="<?php echo U($vv['purl']) ?>">
                        <?php echo $vv['pname']?>
                        </a>
                           </li>
                        <?php }?>

                        </ul>
                    </li>
                    <?php }?>
                    <li class='show'>
                        <a href="<?php echo U('Index/clearCache');?>" ><i class="fa fa-envelope"></i>
                            <span class="nav-label">清除缓存</span>
                        </a>
                    </li>
                </ul>

            </div>
        </nav>
   <div id="page-wrapper" class="gray-bg dashbard-1">
            
<script type="text/javascript" src="/Public/Home/zdy/Validform.js"></script>
<script src="/Public/ckeditor/ckeditor.js"></script>
<script src="/Public/laydate/laydate.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>表单验证</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">系统配置</a>
            </li>
            <li>
                <strong>配置信息</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="">
                        <a  href="javascript:void(0)" class="btn btn-primary ">配置信息</a>
                    </div>
                    <form role="form" id="form" action="<?php echo U('Peizhi/lis',array('id'=>$list['id']));?>" method="post" enctype="multipart/form-data">
                        <!--<div style="width:860px;">
                            <label>配置logo展示</label>
                            <?php if($list): ?><img src="/Public/Uploads/<?php echo ($list["logo"]); ?>"
                                        width="200px" height="60px"/><?php endif; ?>
                            <input type="file" name="myfile" nullmsg="请选择"
                                <?php if($list == ''): ?>datatype="*"<?php endif; ?> />
                                <div class="error"
                                     style="position:relative;left:620px;top:-25px"></div>
                        </div>
                        <div style="width:860px;">
                            <label>logo下面的小标题</label>
                            <input type="text" name="smtitle"
                                   datatype="*1-100" value="<?php echo ($list["smtitle"]); ?>"
                                   nullmsg="请输入小标题" errormsg="长度不能超过100"
                                   placeholder="请输入小标题" class="form-control">
                            <div class="error"
                                 style="position:relative;left:620px;top:-25px"></div>
                        </div>
                        <div style="width:860px;">
                            <label>网站标题</label>
                            <input type="text" name="title"
                                   datatype="*1-100" value="<?php echo ($list["title"]); ?>"
                                   nullmsg="请输入标题" errormsg="长度不能超过100"
                                   placeholder="请输入标题" class="form-control">
                            <div class="error"
                                 style="position:relative;left:620px;top:-25px"></div>
                        </div>
                        <div style="width:860px;">
                            <label>网站描述</label>
                            <input type="text" name="des"
                                   datatype="*1-100"value="<?php echo ($list["des"]); ?>"
                                   nullmsg="请输入网站描述" errormsg="长度不能超过100"
                                   placeholder="请输入网站描述" class="form-control">
                            <div class="error"
                                 style="position:relative;left:620px;top:-25px"></div>
                        </div>
                        <div style="width:860px;">
                            <label>网站关键字</label>
                            <input type="text" name="keywords"
                                   datatype="*1-50" value="<?php echo ($list["keywords"]); ?>"
                                   nullmsg="请输入网站关键字" errormsg="长度不能超过50"
                                   placeholder="请输入网站关键字" class="form-control">
                            <div class="error" style="position:relative;left:620px;top:-25px"></div>
                        </div>-->
                        <div style="width:860px;">
                            <label>活动押金</label>
                            <input type="text" name="yajin"
                                   datatype="n" value="<?php echo ($list["yajin"]); ?>"
                                   nullmsg="请输入活动押金" errormsg="请填写数字"
                                   placeholder="请输入活动押金" class="form-control">
                            <div class="error" style="position:relative;left:620px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>关于OHO</label><br>
                            <textarea name="content" cols="50" rows="5" style="width:1000px;height:500px"><?php echo html_entity_decode($list['content']);?></textarea>
                             <!--引入kindeditor编辑器-->
                            <script charset="utf-8" src="/Public/kindeditor/kindeditor.js"></script>
                            <script charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>
                            <script type="text/javascript">
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor = K.create('textarea[name="content"]', {
                                        allowFileManager : true,
                                        afterBlur : function(){
                                            //编辑器失去焦点时直接同步，可以取到值
                                            this.sync();
                                        }
                                    });
                                });
                            </script>
                            <!--引入kindeditor编辑器-->
                        </div>
                        <div>
                            <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>"/>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>提交</strong>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                $("#form").Validform({
                    tiptype:function(msg,o,cssctl) {
                        if (!o.obj.is("form")) {
                            var objtip = o.obj.siblings('div.error');
                            cssctl(objtip, o.type);
                            objtip.text(msg);
                        }
                    }
                })
            </script>


        </div>
    </div>

</div>
<div class="footer">
    <div class="pull-right">
        By：<a href="http://www.zi-han.net" target="_blank">小灏科技有限公司</a>
    </div>
    <div>
        <strong>Copyright</strong> MrBo &copy; 2017
    </div>
</div>

</div>
</div>


</div>

<!-- Mainly scripts -->
<script src="/Public/Admin/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js?v=3.4.0"></script>

<script src="/Public/Admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


<!-- Custom and plugin javascript -->
<!--<script src="/Public/Admin/js/hplus.js?v=2.2.0"></script>-->
<script src="/Public/Admin/js/plugins/pace/pace.min.js"></script>
<!-- Page-Level Scripts -->

</body>

</html>