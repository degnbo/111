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
            
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>会员管理</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?php echo U('Index/index');?>">会员管理</a>
                        </li>
                        <li>
                            <strong>会员列表</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                 <span class="">
                                    <a href="<?php echo U('Member/add');?>" class="btn btn-primary ">添加教练</a>
                                </span>
                                <form action="<?php echo U('Member/lis1');?>" method="get">
                                    <div class="ibox-tools" style="margin-bottom:35px;position:relative;top:-30px">
                                        <!--会员类型：
                                        <input type="radio" name="type" class="so_input" value="all"  <?php if(I('get.type') == all): ?>checked<?php endif; ?>/>全部
                                        <input type="radio" name="type" class="so_input" value="teacher"  <?php if(I('get.type') == teacher): ?>checked<?php endif; ?>/>教练
                                        <input type="radio" name="type" class="so_input" value="student" <?php if(I('get.type') == student): ?>checked<?php endif; ?>/>学员-->
                                        会员排序：
                                        <input type="radio" name="orderby" class="so_input" value="id_asc"  <?php if(I('get.orderby') == id_asc): ?>checked<?php endif; ?>/>升序排列
                                        <input type="radio" name="orderby" class="so_input" value="id_desc" <?php if(I('get.orderby') == id_desc): ?>checked<?php endif; ?>/>降序排列
                                        <input type="text" name="keyword" class="so_input" placeholder="输入关键字查询" value="<?php echo bian(I('get.keyword'))?>"/>
                                        <input type="submit" value="搜索"
                                               style="height:28px;
                                               margin-right:7px;font-size:13px;
                                               border:1px solid #eee;margin-left:-10px;"/>
                                    </div>
                                </form>
                            </div>
                            <script>
                                function fnClickAddRow() {
                                    var url="<?php echo U('Member/add');?>";
                                    location.href=url;
                                }
                            </script>
                            <div class="ibox-content">
                                <table class="table table-striped table-bordered table-hover " id="editable" >
                                    <thead>
                                        <tr>
                                            <th>会员Id</th>
                                            <th align:center>会员姓名</th>
                                            <th>会员头像</th>
                                            <th>会员性别</th>
                                            <th>会员类型</th>
                                            <th>手机号</th>
                                            <th>出生日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($dlist)): foreach($dlist as $key=>$vo): ?><tr class="gradeU">
                                                <td class="jz"><?php echo ($vo["id"]); ?></td>
                                                <td class="jz"><?php echo ($vo["name"]); ?></td>
                                                <td class="jz"><img width="60px" height="60px" <?php if($vo["logo"] != ''): ?>src="<?php echo ($vo['logo']); ?>"<?php else: ?>src="/Public/Uploads/touxiang.jpg"<?php endif; ?> /></td>
                                                <td class="jz"><?php if($vo["sex"] == 1): ?>男<?php elseif($vo["sex"] == 2): ?>女<?php else: ?>未知<?php endif; ?></td>
                                                <td class="jz"><?php if($vo["type"] == 1): ?>学员<?php else: ?>教练<?php endif; ?></td>
                                                <td class="jz"><?php echo ($vo['phone']); ?></td>
                                                <td class="jz"><?php echo ($vo['birth']); ?></td>
                                                <td class="jz">
                                                    <a href="<?php echo U('Member/edit',array('id'=>$vo['id'],'p'=>I('get.p')));?>">
                                                        修改
                                                    </a>
                                                    <a onclick="return confirm('你确定要删除')" href="<?php echo U('Member/delete',array('id'=>$vo['id']));?>">
                                                        删除
                                                    </a>
                                                </td>
                                        </tr><?php endforeach; endif; ?>
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="editable_paginate">
                        <ul class="pagination"><li class="paginate_button previous disabled" aria-controls="editable" tabindex="0" id="editable_previous">
                            <?php echo ($page); ?>
                        </ul>
                    </div>
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