/**
 * sun加载中
 * @version 1.0.0
 */

var sunLoading = {};

// 打开
sunLoading.open = function(id, info){
    var dom = "";
    
	dom = '<div class="sun_loading_bg sun_loading_bg_'+id+'"></div>';
	dom += '<div class="sun_loading sun_loading_'+id+'">';
	dom += '	<div class="img"></div>';
	dom += '	<div class="info">'+info+'</div>';
	dom += '</div>';
	$("body").append(dom);
}

// 关闭
sunLoading.close = function(id){
	$(".sun_loading_bg_"+id).remove();
	$(".sun_loading_"+id).remove();
}