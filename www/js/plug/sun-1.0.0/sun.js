/**
 * sun ui
 */

var sun = {};

/**
 * 打开自动消失的提示框
 * @param type 类型 success | error | prompt
 * @param msg 内容
 * @param time 时间（毫秒）
 * @param callback 回调函数
 */
sun.toast = function(type, info, time, callback){
	var node = "";
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
		node += '<div class="sun_toast success">';
		node += '	<div class="iconfont icon-success icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else if(type == "error"){
		node += '<div class="sun_toast error">';
		node += '	<div class="iconfont icon-error icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else{
		node += '<div class="sun_toast prompt">';
		node += '	<div class="iconfont icon-prompt icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}
	
	domBody.append(node);
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

/**
 * 加载中
 */
sun.loading = {};
/**
 * 加载中打开
 * @param id id
 * @param info 描述
 */
sun.loading.open = function(id, info){
    var node = "";
    
	node = '<div class="sun_loading_bg sun_loading_bg_'+id+'"></div>';
	node += '<div class="sun_loading sun_loading_'+id+'">';
	node += '	<div class="img"></div>';
	node += '	<div class="info">'+info+'</div>';
	node += '</div>';
	$("body").append(node);
}

/**
 * 加载中关闭
 * @param id id
 */
sun.loading.close = function(id){
	$(".sun_loading_bg_"+id).remove();
	$(".sun_loading_"+id).remove();
}
