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
            

<!--<script type="text/javascript" src="/Public/Home/js/jquery-1.8.3.min.js"></script>-->
<script type="text/javascript" src="/Public/Home/zdy/Validform.js"></script>
<script src="/Public/laydate/laydate.js"></script>
<script type="text/javascript" src="/Public/jcrop/getjcrop.js"></script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>表单验证</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">资讯列表</a>
            </li>
            <li>
                <strong>添加资讯</strong>
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
                            var url="<?php echo U('Course/lis');?>";
                            location.reload();
                            location.href=url;
                        }
                        $(function(){
                        })
                    </script>
                    <div class="">
                        <a onclick="fnClickAddRow();" href="javascript:void(0)" class="btn btn-primary ">课程列表</a>
                    </div>
                    <form role="form" id="form" action="<?php echo U('Course/add');?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>活动图片</label>
                            <input type="file" name="myfile" class="form-control" datatype="*" />
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动分类</label>
                            <select name="type_id" onchange="get_list(this)" datatype="*">
                                <option value="">选择分类</option>
                                <?php if(is_array($clist)): foreach($clist as $key=>$va): ?><option value="<?php echo ($va["id"]); ?>"><?php echo ($va["cate_name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <script>
                            //对象用for-in遍历 不能用for(var i=0;遍历
                            /*var dd={"first":{'name':"first"},"zoo":"zoo","2":"2","34":"34","1":"1","second":"second"};
                             for(var i in dd){
                                 console.log(dd[i].name);
                             }*/
                        </script>
                        <div class="form-group">
                            <label>开始时间</label>
                            <input type="text" name="start_time"
                                   datatype="*" onclick="laydate({istime:true,format: 'YYYY-MM-DD hh:mm:ss'})"
                                   nullmsg="请输入开始时间" errormsg="请输入时间"
                                   placeholder="请输入开始时间" class="laydate-icon">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动名称</label>
                            <input type="text" name="tname"
                                   datatype="*1-30"
                                   nullmsg="请输入活动名称" errormsg="长度不能超过30"
                                   placeholder="请输入活动名称" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>教练列表</label>
                            <select datatype="*" nullmsg="请选择教练" errormsg="请选择教练" class="form-control" name="member_id">
                                <option value="">选择教练</option>
                                <?php if(is_array($jlist)): foreach($jlist as $key=>$va): ?><option value="<?php echo ($va["id"]); ?>"><?php echo ($va["name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=nLTOGjrnicsbxqsLDChNXgoS&services=&t=20160804144823"></script>
                        <!--<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>-->
                        <div class="form-group">
                            <label>选择地点</label>
                        </div>
                        <div style="width:730px;margin:auto;">
                            要查询的地址：<input id="text_" type="text" value="" style="margin-right:100px;"/>
                            查询结果(经纬度)：<input id="result_" type="text" />
                            <input type="button" value="查询" onclick="searchByStationName();"/>
                        </div>
                        <div style="width:697px;height:550px;border:#ccc solid 1px;" id="dituContent"></div>
                        <script type="text/javascript">
                            //创建和初始化地图函数：
                            function initMap(){
                                createMap();//创建地图
                                setMapEvent();//设置地图事件
                                addMapControl();//向地图添加控件
                            }
                            //创建地图函数：
                            function createMap(){
                                var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
                                var point = new BMap.Point(116.395645,39.929986);//定义一个中心点坐标
                                map.centerAndZoom(point,12);//设定地图的中心点和坐标并将地图显示在地图容器中
                                window.map = map;//将map变量存储在全局
                            }
                            //地图事件设置函数：
                            function setMapEvent(){
                                map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
                                map.enableScrollWheelZoom();//启用地图滚轮放大缩小
                                map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
                                map.enableKeyboard();//启用键盘上下左右键移动地图
                            }

                            //地图控件添加函数：
                            function addMapControl(){
                                //向地图中添加缩放控件
                                var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
                                map.addControl(ctrl_nav);
                                //向地图中添加缩略图控件
                                var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
                                map.addControl(ctrl_ove);
                                //向地图中添加比例尺控件
                                var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
                                map.addControl(ctrl_sca);
                            }


                            initMap();//创建和初始化地图
                            var geoc = new BMap.Geocoder();
                            function showInfo(e){
                                //alert(e.point.lat+''+ e.point.lng);
                                document.getElementById('lat').value= e.point.lat;
                                document.getElementById('lng').value= e.point.lng;
                                var pt = e.point;
                                geoc.getLocation(pt, function (rs) {
                                    var addComp = rs.addressComponents;
                                    //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
                                    document.getElementById('address').value=addComp.province +addComp.city+addComp.district+addComp.street+addComp.streetNumber;
                                })
                            }
                            map.addEventListener('click',showInfo);
                            var localSearch = new BMap.LocalSearch(map);
                            localSearch.enableAutoViewport(); //允许自动调节窗体大小
                            function searchByStationName() {
                                var keyword = document.getElementById("text_").value;
                                localSearch.setSearchCompleteCallback(function (searchResult) {
                                    var poi = searchResult.getPoi(0);
                                    document.getElementById("result_").value = poi.point.lng + "," + poi.point.lat; //获取经度和纬度，将结果显示在文本框中
                                    document.getElementById('address').value=keyword;
                                    document.getElementById('lat').value= poi.point.lat;
                                    document.getElementById('lng').value=poi.point.lng;

                                    map.centerAndZoom(poi.point, 13);
                                });
                                localSearch.search(keyword);
                            }
                            /*map.addEventListener("click", function(e) {
                             var pt = e.point;
                             geoc.getLocation(pt, function (rs) {
                             var addComp = rs.addressComponents;
                             alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
                             })
                             })*/
                        </script>
                        <!--<script type="text/javascript">
                            // 百度地图API功能
                            var map = new BMap.Map("allmap");
                            var point = new BMap.Point(116.331398,39.897445);
                            map.centerAndZoom(point,12);
                            var geoc = new BMap.Geocoder();

                            map.addEventListener("click", function(e){
                                var pt = e.point;
                                geoc.getLocation(pt, function(rs){
                                    var addComp = rs.addressComponents;
                                    alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
                                });
                            });
                        </script>-->
                        <div class="form-group">
                            <label>经度</label>
                            <input type="text" name="lat" id="lat"
                                   datatype="*" value="" readonly
                                   nullmsg="请输入活动人数" errormsg="请输入数字"
                                   placeholder="请输入活动人数" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>纬度</label>
                            <input type="text" name="lng" id="lng"
                                   datatype="*" value=""  readonly
                                   nullmsg="请输入活动人数" errormsg="请输入数字"
                                   placeholder="请输入活动人数" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动地点</label>
                            <input type="text" name="address" id="address"
                                   datatype="*1-50" value="<?php echo ($vo["address"]); ?>"
                                   nullmsg="请输入课程地点" errormsg="长度不能超过50"
                                   placeholder="请输入课程地点" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动人数</label>
                            <input type="text" name="number"
                                   datatype="n"
                                   nullmsg="请输入活动人数" errormsg="请输入数字"
                                   placeholder="请输入活动人数" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>联系方式</label>
                            <input type="text" name="phone"
                                   datatype="*"
                                   nullmsg="请输入联系方式" errormsg="请输入联系方式"
                                   placeholder="请输入联系方式" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动价格</label>
                            <input type="text" name="price"
                                   datatype="/^\d+(\.\d{1,2})?$/"
                                   nullmsg="请输入活动价格" errormsg="请输入数字"
                                   placeholder="请输入活动价格" class="form-control">
                            <div class="error" style="position:relative;left:365px;top:-25px"></div>
                        </div>
                        <div class="form-group">
                            <label>活动详情</label><br>
                            <textarea name="content" id="content" cols="50" rows="5" ></textarea>
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
<!--
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
</script>-->