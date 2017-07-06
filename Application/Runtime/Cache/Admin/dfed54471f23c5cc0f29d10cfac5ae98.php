<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>界拓自由潜水</title>
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
<body>
<div id="wrapper">
    <div id="page-wrapper" class="gray-bg dashbard-1">

<script type="text/javascript" src="/Public/Home/zdy/Validform.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>栏目管理</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">栏目管理</a>
            </li>
            <li>
                <strong>栏目列表修改</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">

    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-content">
                    <script>
                        function fnClickAddRow() {
                            var url="<?php echo U('Category/lis');?>";
                            location.reload();
                            location.href=url;
                        }
                        $(function(){
                        })
                    </script>
                    <div class="">
                        <a onclick="fnClickAddRow();" href="javascript:void(0)" class="btn btn-primary ">栏目列表</a>
                    </div>
                    <form role="form" id="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>栏目logo</label>
                            <?php if($vo["cate_logo"] != ''): ?><img src="/Public/Uploads/<?php echo ($vo["cate_logo"]); ?>" width="80px" height="30px"/><?php endif; ?>
                            <input type="file" class="form-control"name="myfile"/>
                            <div class="error" style="position:relative;left:365px;top:-25px"><font color="red">*logo可上传，也可不上传</font></div>
                        </div>
                        <div class="form-group">
                                <label>栏目名称</label>
                                <input type="text"
                                       value='<?php echo ($vo["cate_name"]); ?>'
                                       name="cate_name"  datatype="*1-20"
                                       nullmsg="请输入角色名称" errormsg="长度不能超过20"
                                       placeholder="请输入栏目名称" class="form-control">
                                <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>上级栏目</label>
                            <select name="pid">
                                <option value="0">顶级栏目</option>
                                <?php if(is_array($dlist)): foreach($dlist as $key=>$val): ?><option value="<?php echo ($val["id"]); ?>" <?php if($vo["pid"] == $val['id']): ?>selected<?php endif; ?>>
                                    <?php echo (str_repeat("&nbsp;&nbsp;",$val["level"])); echo ($val["cate_name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>是否显示</label><br>
                            <input type="radio" name="is_show" value="1" <?php if($vo["is_show"] == 1): ?>checked<?php endif; ?>>是
                            <input type="radio" name="is_show"   value="0" <?php if($vo["is_show"] == 0): ?>checked<?php endif; ?>>否
                        </div>
                        <div class="form-group">
                            <label>是否导航</label><br>
                            <input type="radio" name="is_nav" value="1" <?php if($vo["is_nav"] == 1): ?>checked<?php endif; ?>>是
                            <input type="radio" name="is_nav"   value="0" <?php if($vo["is_nav"] == 0): ?>checked<?php endif; ?>>否
                        </div>
						<div class="form-group">
                            <label>栏目标题</label>
                                <input type="text" name='cate_title' placeholder="栏目标题" class="form-control" <?php if($vo.cate_des): ?>value='<?php echo ($vo["cate_title"]); ?>'<?php endif; ?>  />
                            <div class="error" style="position:relative;left:365px;top:-25px"><font color="red">*栏目标题可填，也可不填</font></div>
                        </div>
                        <div class="form-group">
                            <label>栏目路径</label>
                            <input type="text" name='link_url' placeholder="栏目路径"  <?php if($vo.link_url): ?>value='<?php echo ($vo["link_url"]); ?>'<?php endif; ?>  />
                            <label><input style="margin-left: 30px;" type="checkbox" name="is_wl"  value="2"
                                          <?php if($vo["is_wl"] == 2): ?>checked<?php endif; ?>
                            />
                            <span style="color:blue">外链</span></label>
                            <div class="error" style="position:relative;left:365px;top:-25px"><font color="red">*栏目路径可填，也可不填</font></div>
                        </div>
                        <div class="form-group">
                            <label>栏目描述</label>
                                <input type="text" name='cate_des' placeholder="栏目描述" class="form-control" <?php if($vo.cate_des): ?>value='<?php echo ($vo["cate_des"]); ?>'<?php endif; ?>  />
                            <div class="error" style="position:relative;left:365px;top:-25px"><font color="red">*栏目描述可填，也可不填</font></div>
                        </div>
                        <!--<div class="form-group">
                            <label>排序数字</label>
                            <input type="text"
                                   name="sort_num"  datatype="n"
                                   nullmsg="请输入排序数字"  value="<?php echo ($vo["sort_num"]); ?>" readonly
                                   placeholder="请输入排序数字" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>-->
                        <div class="form-group">
                            <label>栏目详情</label><br>
                            <textarea name="content"  id="content"><?php echo html_entity_decode($vo['content']);?></textarea>

                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div>
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
<!--引入UMeditor编辑器-->
<link href="/Public/umeditor1_2_2-utf8-php/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    UM.getEditor("content", {
        initialFrameWidth : "120%",
        initialFrameHeight : "500",
    });
</script>