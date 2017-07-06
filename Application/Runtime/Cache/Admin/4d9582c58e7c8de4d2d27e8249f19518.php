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
                    <h2>数据表格</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?php echo U('Index/index');?>">订单管理</a>
                        </li>
                        <li>
                            <strong>参与活动订单列表</strong>
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
                                    <a href="javascript:void(0);" class="btn btn-primary ">参与活动订单列表
                                    </a>
                                    <!--<a href="<?php echo U('Order/lis',array('paystatus'=>'0'));?>">已支付</a>
                                    <a href="<?php echo U('Order/lis',array('paystatus'=>'1'));?>">|待支付</a>
                                    <a href="<?php echo U('Order/lis',array('paystatus'=>'2'));?>">|已删除</a>-->
                                </span>
                                <form action="<?php echo U('Order/lis');?>" method="get">
                                    <div class="ibox-tools" style="margin-bottom:35px;position:relative;top:-30px">
                                        <input type="text" name="keyword" class="so_input"
                                               placeholder="输入订单号" value="<?php echo I('get.keyword');?>"/>
                                        <input type="submit" value="搜索"
                                               style="height:28px;
                                               margin-right:7px;font-size:13px;
                                               border:1px solid #eee;margin-left:-10px;"/>
                                    </div>
                                </form>
                            </div>
                            <div class="ibox-content">
                                <table class="table table-striped table-bordered table-hover " id="editable" >
                                    <thead>
                                        <tr>
                                            <th align:center>订单号</th>
                                            <th width='10%'>活动名称</th>
                                            <th>报名人</th>
                                            <!--<th>发布人</th>-->
                                            <th>下单时间</th>
                                            <th>支付金额</th>
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($dlist)): foreach($dlist as $k=>$vo): ?><tr class="gradeU">
                                                <td class="jz"><?php echo ($vo["ordernum"]); ?></td>
                                                <td class="jz"><?php echo ($vo["tname"]); ?></td>
                                                <td class="jz"><?php echo ($vo["name"]); ?></td>
                                                <td class="jz"><?php if($vo['pay_time'] != '0'): echo date("Y-m-d H:i:s",$vo['pay_time']); else: ?>没下单<?php endif; ?></td>
                                                <td class="jz"><?php echo ($vo["active_price"]); ?></td>
                                                <td class="jz"><?php if($vo["pay_status"] == 1): ?>已支付
                                                    <?php elseif($vo["pay_status"] == 0 ): ?>
                                                    未支付
                                                    <?php elseif($vo["pay_status"] == 2 ): ?>
                                                    退款中
                                                    <?php elseif($vo["pay_status"] == 3): ?>
                                                    签到，已退款
                                                    <?php elseif($vo["pay_status"] == 4): ?>
                                                    活动取消
                                                    <?php elseif($vo["pay_status"] == 5): ?>
                                                    非签到，已退款
                                                    <?php elseif($vo["pay_status"] == 6): ?>
                                                    未退款<?php endif; ?>
                                                </td>
                                                <td class="jz">
                                                    <a href="<?php echo U('Order/join_detail',array('id'=>$vo['id'],'p'=>I('get.p'),'paystatus'=>I('get.paystatus')));?>" >
                                                    <img src="/Public/Admin/img/icon_edit.gif"/></a>
													<?php if($vo["pay_status"] == 1 && $vo["is_show"] == 1): ?><a onclick="return confirm('你确定要删除')" href="<?php echo U('Order/delete',array('id'=>$vo['id'],'p'=>I('get.p')));?>">
                                                        <img src="/Public/Admin/img/icon_trash.gif"/>
                                                    </a><?php endif; ?>
                                                </td>
                                            </tr><?php endforeach; endif; ?>

                                    </tbody>

                                    <script>
                                        $(function(){
                                            $(".xqy").find('img').click(function() {
                                                var url = "<?php echo U('Information/ajaxChange');?>";
                                                var val = $(this).attr('xs');
                                                var mid = $(this).parent().attr('mid');
                                                if (val == 1) {
                                                    $.post(url, {val: val, mid: mid});
                                                    $(this).attr('src', "/Public/Admin/img/error.png");
                                                    $(this).attr('xs', '0');

                                                } else {
                                                    $.post(url, {val: val, mid: mid});
                                                    $(this).attr('src', "/Public/Admin/img/true.png");
                                                    $(this).attr('xs', '1');
                                                }
                                            })
                                        })
                                   </script>
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
							<ul class="pagination">
							<li class="paginate_button previous disabled" aria-controls="editable" tabindex="0" id="editable_previous">
								<?php echo ($page); ?>
								</li>
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