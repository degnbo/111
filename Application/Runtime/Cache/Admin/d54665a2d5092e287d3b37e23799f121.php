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
                            <a href="index.html">栏目管理</a>
                        </li>
                        <li>
                            <strong>分类栏目</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
<script src="/Public/Admin/layer/layer.js"></script>
        <script>
            function fnClickAddRow() {
                layer.open({
                    type: 2,
                    title: '栏目管理',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['680px', '90%'],
                    content: "<?php echo U('Category/add');?>", //iframe的url
                    end: function () {
                        location.reload();
                    }

                });
            }
        </script>

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="">
                                    <a onclick="fnClickAddRow();" href="javascript:void(0);" class="btn btn-primary ">添加栏目</a>
                                </span>

                                <form action="<?php echo U('Category/lis');?>" method="get">
                                    <div class="ibox-tools" style="margin-bottom:35px;position:relative;top:-30px">
                                        <input type="text" name="keyword" class="so_input" placeholder="输入关键字查询"/>
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
                                            <th align:center>栏目ID</th>
                                            <th>栏目名称</th>
                                            <th>是否显示</th>
                                            <th>是否导航</th>
                                            <th>排序数字</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($dlist)): foreach($dlist as $k=>$vo): ?><tr class="gradeU" id="<?php echo ($vo["id"]); ?>" clas="<?php echo ($vo['level']); ?>"
                                                style="<?php if($vo["level"] > 0): ?>display:none<?php endif; ?>">
                                                    <td class="jz"><?php echo ($vo["id"]); ?></td>
                                                    <td class="" style="cursor:pointer;">
                                                        <?php echo (str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$vo['level']*2)); ?>
                                                        <img class='add' src="/Public/Admin/img/btn_jia.gif"/><?php echo ($vo["cate_name"]); ?>
                                                    </td>
                                                    <td class="jz xs"  mid="<?php echo ($vo["id"]); ?>">
                                                        <?php if($vo['is_show'] == 1): ?><img src="/Public/Admin/img/yes.gif" xs="1" /><?php else: ?>
                                                            <img src="/Public/Admin/img/no.gif" xs="0" /><?php endif; ?>
                                                    </td>
                                                    <td class="jz dh" mid="<?php echo ($vo["id"]); ?>">
                                                        <?php if($vo['is_nav'] == 1): ?><img src="/Public/Admin/img/yes.gif" xs="1" /><?php else: ?>
                                                            <img src="/Public/Admin/img/no.gif" xs="0" /><?php endif; ?>
                                                    </td>
                                                    <td class="jz">
                                                        <span class="up" onclick="ajax_up(this)" pid="<?php echo ($vo["pid"]); ?>" mid="<?php echo ($vo["id"]); ?>" val="<?php echo ($vo["sort_num"]); ?>"   style="font-size: 18px;font-weight: 700;cursor:pointer;" title="提升排序"><</span>
                                                        <span><input class="px" type="text" value="<?php echo ($vo["sort_num"]); ?>" size='2' style="text-align: center;" readonly/></span>
                                                        <span class="down" onclick="ajax_down(this)" mid="<?php echo ($vo["id"]); ?>" pid="<?php echo ($vo["pid"]); ?>" val="<?php echo ($vo["sort_num"]); ?>" style="font-size: 18px;font-weight: 700;cursor:pointer;" alt="下降排序">></span>
                                                    </td>
                                                    <td class="jz"><a href="javascript:void(0)" onclick="laydiv(this,'<?php echo ($vo["id"]); ?>')">
                                                        <img src="/Public/Admin/img/update.png" width="20px"/></a>
                                                        <a onclick="del(this,'<?php echo ($vo["id"]); ?>')" href="javascript:void(0)">
                                                            <img src="/Public/Admin/img/del.png"/>
                                                        </a>
                                                    </td>
                                            </tr><?php endforeach; endif; ?>

                                    </tbody>
                                    <script>
                                        function del(obj,id){
                                            if(confirm('你确定要删除')){
                                                $(obj).parent().parent().remove();
                                                $.ajax({
                                                    url:"<?php echo U('delete');?>",
                                                    data:{id:id},
                                                    type:'get',
                                                })
                                            }else{
                                                return false;
                                            }
                                        }
                                    </script>

                                    <script>
                                        function ajax_up(a){
                                            //alert($(a).parent().parent('.gradeU').index());return false;
                                            var mid=$(a).attr('mid');
                                            //alert(mid);
                                            //return false;
                                            var pid=$(a).attr('pid');
                                            var val=$(a).attr('val');
                                            //alert(val);
                                            var wo=$(a).parent().parent('.gradeU');
                                            $(".up").attr('onclick','');
                                            //alert(wo.html());
                                            var url="<?php echo U('Category/ajax_up');?>";
                                            $.post(url,{mid:mid,val:val,pid:pid},function(e){
                                                //alert(e);
                                                if(e.now){
                                                    $(a).attr('val', e.now.sort_num);
                                                    $(a).parent().find('.down').attr('val', e.now.sort_num);
                                                    $(a).parent().find('.px').val(e.now.sort_num);
                                                    $("#"+e.updata.id).find('.down').attr('val',e.updata.sort_num);
                                                    $("#"+e.updata.id).find('.up').attr('val',e.updata.sort_num);
                                                    $("#"+e.updata.id).find('.px').val(e.updata.sort_num);
                                                    //alert($(a).parent().find('.px input').val());
                                                    $.each(e.updata.js,function(i,v){
                                                        //alert(v);
                                                        $("#"+e.now.js).after($("#"+v));
                                                    })
                                                    //wo.after(wo.prev('.gradeU'));
                                                    $(".up").attr('onclick','ajax_up(this)');
                                                }else{
                                                    alert('没有了');
                                                    $(".up").attr('onclick','ajax_up(this)');
                                                }
                                            },'json');
                                        }
                                        function ajax_down(a){
                                            var mid=$(a).attr('mid');
                                            var val=$(a).attr('val');
                                            var pid=$(a).attr('pid');
                                            //alert(val);
                                            var wo=$(a).parent().parent('.gradeU');
                                            //alert(wo.html());
                                            var url="<?php echo U('Category/ajax_down');?>";
                                            $(".down").attr('onclick','');
                                            $.post(url,{mid:mid,val:val,pid:pid},function(e){
                                                if(e.now){
                                                    $(a).attr('val', e.now.sort_num);
                                                    $(a).parent().find('.up').attr('val', e.now.sort_num);
                                                    $(a).parent().find(".px").val(e.now.sort_num);
                                                    $("#"+e.updata.id).find('.down').attr('val',e.updata.sort_num);
                                                    $("#"+e.updata.id).find('.up').attr('val',e.updata.sort_num);
                                                    $("#"+e.updata.id).find('.px').val(e.updata.sort_num);
                                                    $.each(e.now.js,function(i,v){
                                                        //alert(v);
                                                        $("#"+e.updata.js).after($("#"+v));
                                                    })
                                                    $(".down").attr('onclick','ajax_down(this)');
                                                }else{
                                                    alert('没有了');
                                                    $(".down").attr('onclick','ajax_down(this)');
                                                }
                                            },'json');
                                        }
                                    </script>
                                    <script>
                                        $(function(){
                                            $(".xs").find('img').click(function() {
                                                if(confirm("你确定修改吗?")){
                                                    var url = "<?php echo U('Category/ajaxChange');?>";
                                                    var val = $(this).attr('xs');
                                                    var mid = $(this).parent().attr('mid');
                                                    if (val == 1) {
                                                        $.post(url, {xs: val, mid: mid,type:1});
                                                        $(this).attr('src', "/Public/Admin/img/no.gif");
                                                        $(this).attr('xs', '0');

                                                    } else {
                                                        $.post(url, {xs: val, mid: mid,type:1});
                                                        $(this).attr('src', "/Public/Admin/img/yes.gif");
                                                        $(this).attr('xs', '1');
                                                    }
                                                }else{
                                                    return false;
                                                }
                                            })
                                            $(".dh").find('img').click(function() {
                                                if(confirm("你确定修改吗?")){
                                                    var url = "<?php echo U('Category/ajaxChange');?>";
                                                    var val = $(this).attr('xs');
                                                    var mid = $(this).parent().attr('mid');
                                                    //alert(mid);
                                                    if (val == 1) {
                                                        $.post(url, {xs: val, mid: mid,type:2});
                                                        $(this).attr('src', "/Public/Admin/img/no.gif");
                                                        $(this).attr('xs', '0');
                                                    } else {
                                                        $.post(url, {xs: val, mid: mid,type:2});
                                                        $(this).attr('src', "/Public/Admin/img/yes.gif");
                                                        $(this).attr('xs', '1');
                                                    }
                                                }else{
                                                    return false;
                                                }
                                            })
                                        })
                                        $(function(){
                                            $('.add').click(function(){
                                                var img=$(this);
                                                //alert(img.parent().parent().attr('class'));
                                                //return false;
                                                if( img.attr('src')=="/Public/Admin/img/btn_jia.gif"){
                                                    img.attr('src',"/Public/Admin/img/btn_jian.gif");
                                                    img.parent().parent().nextAll().each(function(i,val){
                                                        //alert($(val).attr('class'));
                                                        if($(val).attr('clas')==parseInt(img.parent().parent().attr('clas'))+1){
                                                            $(val).show();
                                                        }
                                                        if($(val).attr('clas')<=parseInt(img.parent().parent().attr('clas'))){
                                                            return false;
                                                        }
                                                    })
                                                }else{
                                                    $(this).attr('src',"/Public/Admin/img/btn_jia.gif");
                                                    img.parent().parent().nextAll().each(function(i,val){
                                                        //alert($(val).attr('class'));
                                                        if($(val).attr('clas')>parseInt(img.parent().parent().attr('clas'))){
                                                            $(val).hide();
                                                            $(val).children('td').find('img.add').attr('src',"/Public/Admin/img/btn_jia.gif");
                                                        }else{
                                                            return false;
                                                        }
                                                    })
                                                }
                                            })
                                        })

                                    </script>

                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="row">
                    <div class="col-sm-6">
                    <div class="dataTables_info" id="editable_info" role="alert" aria-live="polite" aria-relevant="all">显示 1 到 10 项，共 39 项</div>
                    </div>
                    <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="editable_paginate">
                        <ul class="pagination"><li class="paginate_button previous disabled" aria-controls="editable" tabindex="0" id="editable_previous">
                            <a href="#">上一页</a></li><li class="paginate_button active" aria-controls="editable" tabindex="0"><a href="#">1</a></li>
                            <li class="paginate_button " aria-controls="editable" tabindex="0"><a href="#">2</a></li>
                            <li class="paginate_button " aria-controls="editable" tabindex="0"><a href="#">3</a></li>
                            <li class="paginate_button " aria-controls="editable" tabindex="0"><a href="#">4</a></li>
                            <li class="paginate_button next" aria-controls="editable" tabindex="0" id="editable_next"><a href="#">下一页</a></li>
                        </ul>
                    </div>
                    </div>
                </div>-->
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
<script src="/Public/Admin/layer/layer.js"></script>
<script>
    /*<?php echo U('Category/edit',array('id'=>$vo['id']));?>*/
    function laydiv(obj,id){
        layer.open({
            type: 2,
            title: '栏目管理',
            shadeClose: true,
            shade: 0.8,
            area: ['680px', '90%'],
            content: '/index.php/Admin/Category/edit?id='+id, //iframe的url
        });
    }
</script>