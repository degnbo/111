$(function () {

	//获取要上次文件的大小
	function getFileSize(fileName) {
		var byteSize = 0;
		//console.log($("#" + fileName).val());
		if($("#" + fileName)[0].files) {
			var byteSize  = $("#" + fileName)[0].files[0].size;
		}else {

		}
		byteSize = Math.ceil(byteSize / 1024) //KB
		return byteSize;//KB
	}


	//点击上传按钮时，检测上传文件的大小和类型，满足条件，调用上传函数ajaxFileUpload()
	$("#btnUpload").click(function () {
	    //alert('123');
		var allowImgageType = ['jpg', 'jpeg', 'png', 'gif'];
		var file = $("#file1").val();
		//获取大小
		var byteSize = getFileSize('file1');
		//获取后缀
		if (file.length > 0) {
			if(byteSize > 1024*5) {
				alert("上传的附件文件不能超过2M");
				return;
			}

			var pos = file.lastIndexOf(".");
			//截取点之后的字符串
			var ext = file.substring(pos + 1).toLowerCase();
			//console.log(ext);
			if($.inArray(ext, allowImgageType) != -1) {

				ajaxFileUpload();
			}else {
				alert("请选择jpg,jpeg,png,gif类型的图片");
			}
		}else{
			alert("请选择jpg,jpeg,png,gif类型的图片");
		}
	});

	//异步上传函数
	function ajaxFileUpload() {
        //alert('wo');
		$.ajaxFileUpload({
			url:"/index.php/Admin/Course/action", //用于文件上传的服务器端请求地址
			secureuri: false, //一般设置为false
			fileElementId: 'file1', //文件上传空间的id属性  <input type="file" id="file" name="file" />
			dataType: 'json', //返回值类型 一般设置为json
			success: function (data, status){  //服务器成功响应处理函数
            alert('123');
			$("#picture_original>img").attr({src: data.src, width: data.width, height: data.height});
			$('#imgsrc').val(data.path);
			$('#pic').val(data.datapath);
			
			//同时启动裁剪操作，触发裁剪框显示，让用户选择图片区域
			var cutter = new jQuery.UtrialAvatarCutter({
				//主图片所在容器ID
				content : "picture_original",
				//缩略图配置,ID:所在容器ID;width,height:缩略图大小
				//purviews : [{id:"picture_200",width:200,height:200}],
				//选择器默认大小
				selector : {width:400,height:300},
				showCoords : function(c) { //当裁剪框变动时，将左上角相对图片的X坐标与Y坐标 宽度以及高度
					$("#x1").val(c.x);
					$("#y1").val(c.y);
					$("#cw").val(Math.round(c.w));
					$("#ch").val(Math.round(c.h));
				},

				cropattrs : {boxWidth: 600, boxHeight: 0}
			});

			cutter.reload(data.src);
			
			//显示遮罩层
			var winWidth = $(window).width()-$(window).scrollLeft();
			var winHeight = $(window).height()-$(window).scrollTop();
			$('#zhezhao').width(winWidth).height(winHeight).show();
			//显示图片的对话框
			$('#div_avatar').mywin({left:'center' ,top:'center'}).show();
			},
			error: function (data, status, e){//服务器响应失败处理函数
				alert(e);
			}
		})
		return false;
	}
	//剪裁上传
	$('#btnCrop').click(function() {
		$.getJSON('"/index.php/Admin/Base/action2"', {x: $('#x1').val(), y: $('#y1').val(), w: $('#cw').val(), h: $('#ch').val(), src: $('#imgsrc').val()}, function(data) {

            alert(data.msg);
			$('#zhezhao').hide();
			$('#div_avatar').hide();
			$('#qx').val(0);
		});
		return false;
	});
	//不剪裁，使用原图
	$('#noresize').click(function(){
		$('#zhezhao').hide();
		$('#div_avatar').hide();
		$('#qx').val(0);
	});
	//取消上传
	$('#quxiao').click(function(){
		$('#zhezhao').hide();
		$('#div_avatar').hide();
		$('#qx').val(1);//取消上传，让服务器端删除已经上传到源文件
	});
});