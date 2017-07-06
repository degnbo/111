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
            
<script type="text/javascript" src="/Public/jcrop/getjcrop.js"></script>
<script type="text/javascript" src="/Public/Home/zdy/Validform.js"></script>
<script src="/Public/laydate/laydate.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>表单验证</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">会员管理</a>
            </li>
            <li>
                <strong>修改会员</strong>
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
                        var url="<?php echo U('Member/lis',array('p'=>I('get.p')));?>";
                        location.reload();
                        location.href=url;
                    }
                    $(function(){
                    })
                </script>
                <div class="ibox-title">
                    <h5>表单验证</h5>
                </div>
                <div class="ibox-content">
                    <div class="">
                        <a onclick="fnClickAddRow();" href="javascript:void(0)" class="btn btn-primary ">会员列表</a>
                    </div>
                    <form role="form" id="form" action="<?php echo U('Member/edit1',array('id'=>$vo['id'],'p'=>I('get.p')));?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>会员头像</label>
                            <input type="text" style="width: 200px;" name="logo" id="logo" value="<?php echo ($vo["logo"]); ?>"/>
                            <button type="button" class="btn btn-success" id="btnUpload">上传</button>
                            <input type="file" name="myfile" style="display:none" id="myfile"/>
                            <!--<span  class="btn btn-primary" ><a href="javascript:;" onclick="GetJcrop('jcrop','logo');return false;" style="color:#fff">裁剪</a></span>
                            <input type="hidden" name="thumb_logo" value="<?php echo ($vo["thumb_logo"]); ?>" id="slt"/>
                            <div><img src="<?php echo ($vo["thumb_logo"]); ?>" id="slturl"/></div>-->
                        </div>
                        <script>
                            $(function () {
                                $('#btnUpload').click(function(){
                                    $('#myfile').click();
                                })
                                $("#myfile").wrap("<form id='myupload' style='display:none' target='ajax_iframe' action=\"<?php echo U('Member/ajax_uploads');?>\" method='post' enctype='multipart/form-data'></form>");
                                $("#myfile").change(function(){ //选择文件
                                    //alert(1);return false;
                                    $("#logo").val('');
                                    $("#myupload").submit();
                                });

                            });
                        </script>
                        <iframe name="ajax_iframe" style="display:none"></iframe>
                        <!--<div class="form-group">
                            <label>图片宽度</label>
                            <input type="text" name="width"  <?php if($vo["width"] ): ?>value=<?php echo ($vo["width"]); endif; ?>
                                   placeholder="请输入图片宽度" class="form-control">
                            <label>图片高度</label>
                            <input type="text" name="height"  <?php if($vo["height"] ): ?>value=<?php echo ($vo["height"]); endif; ?>
                                   placeholder="请输入图片高度" class="form-control">
                        </div>-->
                        <div class="form-group">
                            <label>性别</label><br>
                            <input type="radio" name="sex"  value="1" <?php if(($vo["sex"]) == "1"): ?>checked<?php endif; ?> >男
                            <input type="radio" name="sex" datatype="*" nullmsg="请选择" value="2" <?php if(($vo["sex"]) == "2"): ?>checked<?php endif; ?> >女
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>姓名</label>
                            <input type="text" placeholder="请输入姓名" datatype="*1-30"
                                   name="name" class="form-control" value="<?php echo ($vo["name"]); ?>"
                                   nullmsg="请输入姓名" errormsg="格式错误" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>出生日期</label>
                            <input type="text" name="birth" value="<?php echo ($vo["birth"]); ?>"
                                   datatype="*" onclick="laydate({format: 'YYYY-MM-DD'})"
                                   nullmsg="请输入出生日期" errormsg="请输入出生日期"
                                   placeholder="请输入出生日期" class="laydate-icon">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>联系方式</label>
                            <input type="text" placeholder="请输入联系方式" datatype="*1-50"
                                   name="lianxi" class="form-control" value="<?php echo ($vo["lianxi"]); ?>"
                                   nullmsg="请输入联系方式" errormsg="字数过多" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>联系地址</label>
                            <input type="text" placeholder="请输入详细地址"
                                   name="xxdz" class="form-control" value="<?php echo ($vo["xxdz"]); ?>"
                                   nullmsg="请输入详细地址" errormsg="格式错误" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>签名</label>
                            <input type="text" placeholder="请输入签名"
                                   name="person_sign" class="form-control" value="<?php echo ($vo["person_sign"]); ?>"
                                   nullmsg="请输入签名" errormsg="内容过长" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>形象照片</label><br>
                            <?php if(is_array($urls)): $k = 0; $__LIST__ = $urls;if( count($__LIST__)==0 ) : echo "暂无图片" ;else: foreach($__LIST__ as $key=>$va): $mod = ($k % 2 );++$k;?><span class="tj">
                                <img src="<?php echo ($va); ?>" width="100px" height="80px" style="margin-right: 12px"/>
                                <span class="edit" aid="<?php echo ($vo["id"]); ?>" tp="<?php echo ($va); ?>" style="cursor: pointer">修改</span>
                                <input type="file" name="myfile2" style="display:none" />
                                &nbsp;<span class="del" aid="<?php echo ($vo["id"]); ?>" tp="<?php echo ($va); ?>" style="cursor: pointer">删除</span>
								</span>
                                <?php if(($mod) == "1"): ?><hr><?php endif; endforeach; endif; else: echo "暂无图片" ;endif; ?>
                        </div>
                        <div class="form-group">
                            <label id="tjtp" style="width:80px;height:30px;background:#1AB394;color:white;line-height:30px;text-align:center">添加图片</label>
                        </div>
                        <script>
                            $(function(){
                                $("#tjtp").click(function(){
                                    //alert('s');
                                    $("#ply").click();
                                });
                                $("#ply").change(function(){
                                    $("#form1").submit();
                                });
                                $(".edit").click(function(){
                                    //alert('s');
                                    $(this).next("input").click();
                                });
                                $(".del").click(function(){
                                    if(confirm('您确定要删除该图片')){
                                        var wo=$(this);
                                        var tp=$(this).attr('tp');
                                        var id=$(this).attr('aid');
                                        //alert(id);
                                        $.post("<?php echo U('Member/ajax_del');?>",{id:id,tp:tp},function(e){
                                            if(e==1){
                                                alert('删除成功');
                                                wo.parent('.tj').remove();
                                            }else{
                                                alert('删除失败');
                                            }
                                        },'html');
                                    }
                                });

                                $(".edit").click(function(){
                                    var wo=$(this);
                                    var sc=$(this).next('input');
                                    var tp=$(this).attr('tp');
                                    var id=$(this).attr('aid');
                                    var html="<form style='display:none' id='upload_file' action=\"<?php echo U('Member/ajax_edit',array('id'=>$vo['id']));?>\" method='post' enctype='multipart/form-data'>" +
                                            "<input type='hidden' name='tp'  value='"+tp+"'/>" +
                                            "<input type='hidden' name='id' value='"+id+"'/></form>";
                                    $("body").on("change",sc,function(){ //选择文件
                                        wo.next('input').wrap(html);
                                        var url=wo.next().attr('action');
                                        wo.next().submit();
                                    });
                                });


                            })
                        </script>
                        <!--<div class="form-group">
                            <label>图集每一张图片对应的名字</label>
                            <textarea  name="pic_name" rows="5" cols="50"
                                       nullmsg="请输入图片名称" placeholder="请输入每一张图片的名称，有就填写，没有就不填"
                                       class="form-control"><?php echo ($vo["pic_name"]); ?></textarea>
                            <font color="red">多个|隔开</font>
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>图集每一张图片对应的链接</label>
                            <textarea  name="url_name" rows="5" cols="50"
                                       nullmsg="请输入图片链接" placeholder="请输入每一张图片的链接，有就填写，没有就不填"
                                       class="form-control"><?php echo ($vo["url_name"]); ?></textarea>
                            <font color="red">多个|隔开</font>
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>-->
                        <div>
                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>提交</strong>
                            </button>
                        </div>
                    </form>
                    <form id="form1" action="<?php echo U('Member/add_pic',array('id'=>$vo['id']));?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="myfile1" style="display:none" id="ply" />
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