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
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>表单验证</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">EBAC管理</a>
            </li>
            <li>
                <strong>修改角色</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">

    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <script>
                function fnClickAddRow() {
                var url="<?php echo U('Admin/lis');?>";
                    location.reload();
                location.href=url;
                }
                $(function(){
                })
                </script>
                <div class="ibox-content">
                    <div class="">
                        <a onclick="fnClickAddRow();" href="javascript:void(0)" class="btn btn-primary ">管理员列表</a>
                    </div>
                    <form role="form" id="form" action="<?php echo U('Admin/edit',array('id'=>$vo['id']));?>" method="post" onsubmit='return checkform()'>
                        <div class="form-group">
                            <?php if(is_array($rolelist)): foreach($rolelist as $k=>$val): ?><input type="radio"  datatype="*"
                                       name="role_id[]" <?php if(in_array($val['id'],$roleId)): ?>checked<?php endif; ?>
                                value="<?php echo ($val["id"]); ?>"/><?php echo ($val["role_name"]); ?><br><?php endforeach; endif; ?>
							
                            <div class="error" style="position:relative;left:395px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>用户姓名</label>
                            <input type="text" placeholder="请输入用户名" datatype="*1-20"
							<?php if($roleId[0] == '1'): ?>readonly<?php endif; ?>
                                   name="username" class="form-control" value="<?php echo ($vo["username"]); ?>"
                                   nullmsg="请输入角色名称" errormsg="长度不能超过20" datatype="*"/>
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>用户密码</label>
                            <input type="password" placeholder="请输入密码"
                                   name="password" class="form-control" datatype="kong"/>
                            <div class="error" style="position:relative;left:365px;top:-25px"><font color="red">*密码为空表示不修改</font></div>
                        </div>
                        <div class="form-group">
                            <label>确认密码</label>
                            <input type="password" placeholder="请再次输入密码" datatype="kong"
                                   name="cpw" class="form-control" recheck="password"
                                   nullmsg="请再次输入密码" errormsg="两次密码不一致" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group" id="jy">
                            <?php if(in_array('1',$roleId) == false): ?><label>是否禁用</label>
                                <input type="radio" name="is_deny" value="1" <?php if($vo['is_deny'] == 1): ?>checked<?php endif; ?>/>是
                                <input type="radio" name="is_deny"  value="0" <?php if($vo['is_deny'] == 0): ?>checked<?php endif; ?>/>否
                                <div class="error" style="position:relative;left:365px;top:-25px"></div><?php endif; ?>

                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary m-t-n-xs"  type="submit"><strong>提交</strong>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
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
                },
                datatype:{
                    'kong':function(gets,obj,curform,regxp){
                        //var max=16 ;var min=6;
                        if(obj.val()==''){
                            return true;
                        }else{
                            if(obj.val().length>=6 && obj.val().length<=16){
                                return true;
                            }else{
                                return '请输入6-16位的字符或数字';
                            }
                        }
                    },
                    "need1":function(gets,obj,curform,regxp){
                        var need=1,
                                numselected=curform.find("input[name='"+obj.attr("name")+"']:checked").length;
                        return  numselected >= need ? true : "请至少选择"+need+"项！";
                    }
                }
               /* datatype:{
                    "max2":function(gets,obj,curform,regxp){
                        var atmax=1,
                                numselected=curform.find("input[name='"+obj.attr("name")+"']:checked").length;

                        if(numselected==0){
                            return false;
                        }else if(numselected>atmax){
                            return "最多只能选择"+atmax+"项！";
                        }
                        return  true;
                    }
                }*/
            })

        </script>
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