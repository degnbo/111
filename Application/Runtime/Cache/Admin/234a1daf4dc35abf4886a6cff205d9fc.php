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
                            <a href="<?php echo U('Index/index');?>">首页</a>
                        </li>
                        <li>
                            <strong>评论管理</strong>
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
                                <form action="<?php echo U('Comment/lis');?>" method="get">
                                    <div class="ibox-tools" style="margin-bottom:35px;position:relative;top:0px">
                                    </select>
                                        <input type="text" name="keyword" class="so_input" placeholder="输入关键字查询" 
										value="<?php echo bian(I('get.keyword'))?>"/>
                                        <input type="submit" value="搜索"
                                               style="height:28px;
                                               margin-right:7px;font-size:13px;
                                               border:1px solid #eee;margin-left:-10px;"/>
                                    </div>
                                </form>
                            </div>
                            <div class="ibox-content">
							     <form action="<?php echo U('Comment/delmany');?>" method="post" onsubmit="return confirm('你确定要删除')"/>
                                <table class="table table-striped table-bordered table-hover " id="editable" >
                                    <thead>
                                        <tr>
										    <th style="align:left"><input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="qx" id="qx"/>全选</th>
                                            <th align:center>ID</th>
                                            <th>会员姓名</th>
                                            <th>活动名称</th>
                                            <th>活动地点</th>
                                            <th align:center>评论内容</th>
                                            <th>评论星级</th>
                                            <th>评论时间</th>
                                            <!--<th>是否审核</th>-->
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr class="gradeU">
											    <td class="jz"><input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="plsc[]" class="plsc"/></td>
                                                <td class="jz"><?php echo ($vo["id"]); ?></td>
                                                <td class="jz"><?php echo ($vo["name"]); ?></td>
                                                <td class="jz">
                                                    <a href="<?php echo U('Comment/adetial',array('aid'=>$vo['active_id']));?>"><?php echo ($vo["tname"]); ?></a>
                                                </td>
                                                <td class="jz"><?php echo ($vo["address"]); ?></td>
                                                <td class="jz"><?php echo mb_substr($vo['content'],0,30,'utf-8');?></td>
                                                <td class="jz"><?php echo ($vo["star"]); ?>颗星</td>
                                                <td class="jz"><?php echo date("Y-m-d H:i:s",$vo['uptime']);?></td>
                                                <!--<td class="jz xs"  mid="<?php echo ($vo["id"]); ?>">
                                                    <?php if($vo['is_rec'] == 1): ?><img src="/Public/Admin/img/true.png" xs="1" /><?php else: ?>
                                                        <img src="/Public/Admin/img/error.png" xs="2" /><?php endif; ?>
                                                </td>
                                                <td class="jz dh" mid="<?php echo ($vo["id"]); ?>">
                                                    <?php if($vo['is_hot'] == 2): ?><img src="/Public/Admin/img/true.png" xs="2" /><?php else: ?>
                                                        <img src="/Public/Admin/img/error.png" xs="1" /><?php endif; ?>
                                                </td>-->
                                               <td class="jz">
                                                    <a title="删除" onclick="return confirm('你确定要删除')" href="<?php echo U('Comment/delete',array('id'=>$vo['id'],'p'=>I('get.p')));?>">
                                                        <img src="/Public/Admin/img/icon_trash.gif"/>
                                                    </a>
                                                </td>
                                            </tr><?php endforeach; endif; ?>
                                    </tbody>
									<script type="text/javascript">
										var cd=$('.plsc').length;
										$("#qx").click(function(){
											if($(this).prop('checked')){
												//$(this).prop('checked',true);
												$('.plsc').prop('checked',true);
											}else{
												//$(this).prop('checked',false);
												$('.plsc').prop('checked',false);
											}
										})
										$('.plsc').click(function(){
											var gs = 0;
											var arr = [];
											$('.plsc').each(function (i, v) {
												if ($(v).prop('checked')) {
													arr.push($(v).val());
												}
											})
											gs = arr.length;
											if(gs<cd){
											   $('#qx').prop('checked',false);
											}else{
												$('#qx').prop('checked',true);
											}
										})
                                    </script>
                                    <script>
                                        $(function(){
                                            $(".xs").find('img').click(function() {
                                                if(confirm("你确定修改吗?")){
                                                    var url = "<?php echo U('Comment/ajaxChange');?>";
                                                    var val = $(this).attr('xs');
                                                    var mid = $(this).parent().attr('mid');
                                                    if (val == 1) {
                                                        $.post(url, {xs: val, mid: mid,type:1});
                                                        $(this).attr('src', "/Public/Admin/img/error.png");
                                                        $(this).attr('xs', '2');

                                                    } else {
                                                        $.post(url, {xs: val, mid: mid,type:1});
                                                        $(this).attr('src', "/Public/Admin/img/true.png");
                                                        $(this).attr('xs', '1');
                                                    }
                                                }else{
                                                    return false;
                                                }
                                            })
                                            $(".dh").find('img').click(function() {
                                                if(confirm("你确定修改吗?")){
                                                    var url = "<?php echo U('Comment/ajaxChange');?>";
                                                    var val = $(this).attr('xs');
                                                    var mid = $(this).parent().attr('mid');
                                                    //alert(mid);
                                                    if (val == 2) {
                                                        $.post(url, {xs: val, mid: mid,type:2});
                                                        $(this).attr('src', "/Public/Admin/img/error.png");
                                                        $(this).attr('xs', '1');
                                                    } else {
                                                        $.post(url, {xs: val, mid: mid,type:2});
                                                        $(this).attr('src', "/Public/Admin/img/true.png");
                                                        $(this).attr('xs', '2');
                                                    }
                                                }else{
                                                    return false;
                                                }
                                            })
                                        })
                                    </script>
                                </table>
								 <div>
									<input class="btn btn-primary " value="批量删除" type="submit" />
								</div>
								</form>

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