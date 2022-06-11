/**
 * 页面框架主要
 */

var frameMain = {};

/*
 * 页面左边初始化
 */
frameMain.pageLeftInit = function(){
	var domBody = $("body");
    var cookie = "";
    var windowWidth = $(window).width();
    
    cookie = frameMain.pageLeftGetCookie();
	if(cookie == "close"){
		domBody.addClass("close");
	}
    
    if(windowWidth < 768 && cookie == ""){
        domBody.addClass("close");
    }
}

/**
 * 页面左边打开关闭
 */
frameMain.pageLeftToggle = function(){
    var domBody = $("body");
    
    if(domBody.hasClass("close")){
        domBody.removeClass("close");
        frameMain.pageLeftSetCookie("open");
    }else{
        domBody.addClass("close");
        frameMain.pageLeftSetCookie("close");
    }
};

/*
 * 页面左边得到cookie
 */
frameMain.pageLeftGetCookie = function(){
	var cookies = document.cookie.split(";");
	var length = cookies.length;
    var i = 0;
    var items = [];
    var key = "";
    var value = "";
    
    for(i=0; i < length; i++){
        items = cookies[i].split("=");
        key = $.trim(items[0]);
        value = decodeURI($.trim(items[1]));
        if(key == "frame_main_page_left"){
            return value;
        }
    }
	
	return "";
}

/*
 * 页面左边设置cookie
 */
frameMain.pageLeftSetCookie = function(value){
	var dateMy = new Date();
    dateMy.setDate(dateMy.getDate() + 360);
    document.cookie="frame_main_page_left="+value+"; expires="+dateMy.toDateString()+"; path=/";
}

/**
 * 菜单活跃
 */
frameMain.menuActive = function(){
    var domActiveLi;
    var domActiveParentLis;
    var domActiveParentLiUls;
    
    domActiveLi = $(".page_left .menu li.active"); // 活跃的li
    if(domActiveLi.length == 0){
        return;
    }
    
    domActiveParentLis = domActiveLi.parents(".page_left .menu li"); // 活跃的li的所有父li
    domActiveParentLiUls = $(" > ul", domActiveParentLis); // 活跃的li的所有父li的ul

    domActiveParentLis.addClass("open");
    domActiveParentLiUls.css({"display":"block"});
};

/**
 * 菜单切换
 */
frameMain.menuToggle = function(){
    var domAs = $(".page_left .menu li a");
    
    domAs.on("click", function(){
        var domClickA = $(this); // 点击的a
        var domClickLi = domClickA.parent(); // 点击的li
        var domClickLiUl = domClickA.next("ul"); // 点击的li的ul
        var domSiblingLis = domClickLi.siblings("li"); // 同级li
        var domSiblingLiUls = $(" > ul", domSiblingLis); // 同级li的ul
        var clickLiUlExists = domClickLiUl.length > 0 ? true : false; // 点击的li的ul是否存在
        
        // 没有子菜单什么都不做
        if(!clickLiUlExists){
            return;
        }
        
        // 展开折叠
        if(domClickLi.hasClass("open")){
            domClickLi.removeClass("open"); // 去掉open
			domClickLiUl.slideUp(); // 折叠
		}else{
            domClickLi.addClass("open"); // 添加open
			domClickLiUl.slideDown(); // 展开
            
            domSiblingLis.removeClass("open"); // 同级去掉open
            domSiblingLiUls.slideUp(); // 同级折叠
		}
    });
};

/**
 * 修改密码
 */
frameMain.editPassword = function(){
    var url = "/system/user/self_edit_password.php";
    window.parent.sun.layer.open({
        id: "layer_self_edit_password",
        name: "修改密码",
        url: url,
        width: 500,
        height: 300
    });
};

$(function(){
    frameMain.pageLeftInit();
    frameMain.menuActive();
    frameMain.menuToggle();
});