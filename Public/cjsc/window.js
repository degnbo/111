$(function(){
	//鼠标点击按钮显示中间下部的窗口
	$('#center').click(function() {
		$('#w2').mywin({left:'center' ,top:'center'}).show('slow');
	});

	$('#w1').mywin({left:'right' ,top:'center'}).show();
});
//定义插件

$.fn.mywin = function(position){
	if(position && position instanceof Object){
		var currentWin = this;
		var windowObj = $(window);

		//获取传递进来的left和top
		var positionleft = position.left;
		var positiontop = position.top;
		//alert(typeof positionleft);
		var browserWidth; //浏览器可视区宽度
		var browserHeight; //浏览器可视区域高度
		var wScrollLeft; //下滚动条距离左边的距离
		var wScrollTop; //右滚动条距离上面的距离
		var top;
		var left;
		//获取中间下面的窗口的宽和高
		var cWinWidth = currentWin.outerWidth();
		var cWinHeight = currentWin.outerHeight();

		//定义函数，计算浏览器可视区域的大小和滚动条
		function getWinInfo(){
			//获取浏览器可视区域的宽和高
			browserWidth = windowObj.width();
			browserHeight = windowObj.height();
			//获取横向滚动条距离左边的值
			wScrollLeft = windowObj.scrollLeft();
			//获取纵向滚动条距离上边的值
			wScrollTop = windowObj.scrollTop();
		}

		//先调用获取浏览器的信息，以便后面用到
		getWinInfo();

		//计算窗口真实的左边界值
		function calLeft(positionleft, wScrollLeft, browserWidth, cWinWidth) {
			if (positionleft && typeof positionleft == "string") {
				if (positionleft == "center") {
					left = wScrollLeft + (browserWidth - cWinWidth) / 2;
				} else if (positionleft == "left") {
					left = wScrollLeft;	
				} else if (positionleft == "right") {
					left = wScrollLeft + browserWidth - cWinWidth;
					if ($.browser.safari) {
						left = left - 15;
					}
					if ($.browser.opera) {
						left = left + 15;
					}
					if ($.browser.msie && $.browser.version.indexOf("8") >= 0) {
						left = left - 20;
					}
				} else  {
					left = wScrollLeft + (browserWidth - cWinWidth) / 2;	
				}
			} else if (positionleft && typeof positionleft == "number") {
				left = positionleft;
			} else {
				left = 0;
			}
		}
		
		//计算窗口真实的上边界值		
		function calTop(positiontop, wScrollTop, browserHeight, cWinHeight) {
			if (positiontop && typeof positiontop == "string") {
				if (positiontop == "center") {
					top = wScrollTop + (browserHeight - cWinHeight) / 2;
				} else if (positiontop == "top") {
					top = wScrollTop;
				} else if (positiontop == "bottom") {
					top = wScrollTop + browserHeight - cWinHeight;
					if ($.browser.opera) {
						top = top - 25;
					}
				} else {
					top = wScrollTop + (browserHeight - cWinHeight) / 2;
				}
			} else if (positiontop && typeof positiontop == "number") {
				top = positiontop;
			} else {
				top = 0;
			}
		}

		//调用计算left的方法
		calLeft(positionleft,wScrollLeft,browserWidth,cWinWidth);
		//调用计算top的方法
		calTop(positiontop,wScrollTop,browserHeight,cWinHeight);

		//计算小窗口的left和top的值
		function showWin(){
			currentWin.css('top',top).css('left',left);
		}
		//初次显示，找好位置
		showWin();

		//当滚动条滚动时，从新计算窗口的位置，并赋值
		windowObj.scroll(function(){
			//调用计算left的方法
			calLeft(positionleft,wScrollLeft,browserWidth,cWinWidth);
			//调用计算top的方法
			calTop(positiontop,wScrollTop,browserHeight,cWinHeight);
			getWinInfo();
			showWin();
		});
		//当浏览器窗口改变大小时，从新计算窗口的位置，并赋值
		windowObj.resize(function(){
			//调用计算left的方法
			calLeft(positionleft,wScrollLeft,browserWidth,cWinWidth);
			//调用计算top的方法
			calTop(positiontop,wScrollTop,browserHeight,cWinHeight);
			getWinInfo();
			showWin();
		});

		
		//设置关闭按钮
		currentWin.children('.title').children('img').click(function(){
			currentWin.hide('slow');
		});
		//返回当前对象，以便可以级联的执行其他方法
		return currentWin;
	}
}