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
                <a href="index.html">订单列表</a>
            </li>
            <li>
                <strong>订单详情</strong>
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
                                    <a href="<?php echo U('Order/lis1',array('p'=>I('get.p')));?>" class="btn btn-primary ">订单列表
                                    </a>
                                </span>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <tbody>
                        <tr>
                            <td>订单号:</td>
                            <td><?php if(($list["ordernum"]) == ""): ?>--<?php else: ?>
                                <?php echo ($list["ordernum"]); ?>
                                <span style="color:red">
                                    <a onclick="fnClickAddRow(this,'<?php echo ($list["ordernum"]); ?>')">退款查询
                                    </a>
                                </span>
                                <span style="color:red">
                                    <a onclick="fnClickAddRow1(this,'<?php echo ($list["ordernum"]); ?>')">支付查询
                                    </a>
                                </span><?php endif; ?>
                            </td>
                        </tr>
                        <script src="/Public/Admin/layer/layer.js"></script>
                        <script>
                            function fnClickAddRow(obj,onum) {
                                layer.open({
                                    type: 2,
                                    title: '栏目管理',
                                    shadeClose: true,
                                    shade: 0.8,
                                    area: ['680px', '90%'],
                                    content: '/index.php/Admin/Order/find_order?onum='+onum, //iframe的url
                                    end: function () {
                                        //location.reload();
                                    }

                                });
                            }
                            function fnClickAddRow1(obj,onum) {
                                layer.open({
                                    type: 2,
                                    title: '栏目管理',
                                    shadeClose: true,
                                    shade: 0.8,
                                    area: ['680px', '90%'],
                                    content: '/index.php/Admin/Order/chaxun_order?onum='+onum, //iframe的url
                                    end: function () {
                                        //location.reload();
                                    }

                                });
                            }
                        </script>
                        <tr>
                            <td>发布活动名称:</td>
                            <td><?php echo ($list["tname"]); ?></td>
                        </tr>
                        <tr>
                            <td>活动发布者:</td>
                            <td><?php echo ($list["pname"]); ?></td>
                        </tr>
                        <tr>
                            <td>活动ID:</td>
                            <td><?php echo ($list["id"]); ?></td>
                        <tr>
                            <td>活动发布时间:</td>
                            <td><?php echo date("Y年m月d日 H:i:s",$list['addtime']);?></td>
                        </tr>

                        <tr>
                            <td>活动地点:</td>
                            <td><?php echo ($list["address"]); ?></td>
                        </tr>
                        <tr>
                            <td>logo图标:</td>
                            <td>
                                <?php if($list["pic"] != ''): ?><img src="/Public/Uploads/<?php echo ($list["pic"]); ?>" width="100px" height="80px"/><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>活动开始时间:</td>
                            <td><?php echo date("Y年m月d日 H:i:s",$list['start_time']);?></td>
                        </tr>
                        <tr>
                            <td>发布者交押金总额:</td>
                            <td>￥<?php echo ($list['number']*$list['price']); ?></td>
                        </tr>
                        <tr>
                            <td>允许最大参与人数:</td>
                            <td><?php echo ($list["number"]); ?>人</td>
                        </tr>

                        <tr>
                            <td>活动已经参与人数:</td>
                            <td><?php echo ($list["sl"]); ?>人</td>
                        </tr>

                        <tr>
                            <td>参与者每人需要缴纳的押金:</td>
                            <td>￥<?php echo ($list["price"]); ?></td>
                        </tr>

                        <tr>
                            <td>支付状态:</td>
                            <td>
                                <?php if($list["acode"] == 1): ?>--
                                    <?php else: ?>
                                    <?php if($list["is_pay"] == 0): ?>待支付
                                        <?php elseif($list["is_pay"] == 1 ): ?>
                                        已支付
                                        （<a onclick="return_money('<?php echo ($list["id"]); ?>')" href="javascript:void(0);">手动退款</a>）
                                        <?php elseif($list["is_pay"] == 2 ): ?>
                                        退款中
                                        <?php elseif($list["is_pay"] == 3 ): ?>
                                        已退款
                                        <?php elseif($list["is_pay"] == 4 ): ?>
                                        活动取消
                                        <?php elseif($list["is_pay"] == 5 ): ?>
                                        非签到，已退款
                                        <?php elseif($list["is_pay"] == 6 ): ?>
                                        未退款<?php endif; endif; ?>
                            </td>
                        </tr>
                        <script>
                            function return_money(id){
                                $.ajax({
                                    url:"<?php echo U('Order/pub_return');?>",
                                    data:{id:id},
                                    dataType:'json',
                                    type:'get',
                                    success:function(data){
                                        if(data.status){
                                            alert(data.msg);
                                            location.reload();
                                        }else{
                                            alert(data.msg);
                                        }
                                    },
                                    error:function(){
                                        alert('错误');
                                    }
                                });
                            }
                        </script>
                        <?php if($list["pay_status"] == 1): ?><tr>
                                <td>支付时间:</td>
                                <td><?php echo (date("Y-m-d H:i:s",$list["pay_time"])); ?></td>
                            </tr><?php endif; ?>

                        <tr>
                            <td>活动举报消息:</td>
                            <td>
                                <?php if($alist1 != ''): ?><p>举报者<span style="color: green">(<?php echo ($list["pname"]); ?>)</span> 举报内容：<span style="color: green"><?php echo ($alist1["content"]); ?></span>
                                        <span>举报时间：<?php echo date("Y-m-d H:i:s",$alist1['addtime']);?></span>
                                    </p><?php endif; ?>
                                <?php if(!empty($alist2)): if(is_array($alist2)): foreach($alist2 as $key=>$vo): ?><p>举报者<span style="color: red">(<?php echo ($vo["name"]); ?>)</span> 举报内容：<span style="color: red"><?php echo ($vo["content"]); ?></span>
                                            <span>举报时间：<?php echo date("Y-m-d H:i:s",$vo['addtime']);?></span>
                                        </p><?php endforeach; endif; endif; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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