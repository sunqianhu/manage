/**
 * sun toast提示
 * @version 1.0.0
 */
var sunToast = {};

/**
 * 打开自动消失的提示框
 * @param type 类型 success | error | prompt
 * @param msg 内容
 * @param time 时间（毫秒）
 */
sunToast.open = function(type, info, time, callback){
	var note = "";
    var domBody = $("body");
    var domToast = null;
	var windowHeight = $(window).height();
	var scrollHeight = $(document).scrollTop();
    var toastWidth = 0;
    var toastHegiht = 0;
    
	if(!type || !info || !time){
		return;
	}
    
    domToast = $(".sun_toast");
    if(domToast.length > 0){
        domToast.remove();
    }
    
	if(type == "success"){
		note += '<div class="sun_toast success">';
		note += '	<div class="iconfont icon-success icon"></div>';
		note += '	<div class="info">'+info+'</div>';
		note += '</div>';
	}else if(type == "error"){
		note += '<div class="sun_toast error">';
		note += '	<div class="iconfont icon-error icon"></div>';
		note += '	<div class="info">'+info+'</div>';
		note += '</div>';
	}else{
		note += '<div class="sun_toast prompt">';
		note += '	<div class="iconfont icon-prompt icon"></div>';
		note += '	<div class="info">'+info+'</div>';
		note += '</div>';
	}
	
	domBody.append(note);
    domToast = $(".sun_toast");
    toastWidth = domToast.outerWidth();
    toastHegiht = domToast.outerHeight();
	domToast.css({"left":"50%", "top":((windowHeight - toastHegiht) / 2 + scrollHeight)+"px", "margin-left":"-"+(toastWidth / 2)+"px"});
    
	setTimeout(function(){
		domToast.animate({top:"-50px", opacity:"0"}, 500, function(){
			domToast.remove();
			if(typeof(callback) != "undefined") {
				callback();
			}
		});
	}, time);
}