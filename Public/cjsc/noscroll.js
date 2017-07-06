var firefox = navigator.userAgent.indexOf('Firefox') != -1;
function MouseWheel(e) {
///对img按下鼠标滚路，阻止视窗滚动
    e = e || window.event;
    if (e.stopPropagation) e.stopPropagation();
    else e.cancelBubble = true;
    if (e.preventDefault) e.preventDefault();
    else e.returnValue = false;

    //其他代码
}
window.onload = function () {
    var img = document.getElementById('div_avatar');
    firefox ? img.addEventListener('DOMMouseScroll', MouseWheel, false) : (img.onmousewheel = MouseWheel);
    var zhezhao = document.getElementById('zhezhao');
    firefox ? zhezhao.addEventListener('DOMMouseScroll', MouseWheel, false) : (zhezhao.onmousewheel = MouseWheel);
}